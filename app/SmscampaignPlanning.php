<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class SmscampaignPlanning
 * @package App
 *
 * @property integer $id
 *
 * @property boolean $current
 *
 * @property \Illuminate\Support\Carbon $plan_at
 * @property \Illuminate\Support\Carbon $plandone_at
 *
 * @property \Illuminate\Support\Carbon $importstart_at
 * @property \Illuminate\Support\Carbon $importend_at
 * @property integer $nb_to_import
 * @property integer $nb_import_success
 * @property integer $nb_import_failed
 *
 * @property \Illuminate\Support\Carbon $sendingstart_at
 * @property \Illuminate\Support\Carbon $sendingend_at
 * @property integer $stat_all
 * @property integer $stat_sending
 * @property integer $stat_success
 * @property integer $stat_failed
 * @property integer $stat_done
 *
 * @property integer|null $smscampaign_id
 * @property integer|null $smscampaign_status_id
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class SmscampaignPlanning extends Model
{
    protected $guarded = [];

    public function campaign() {
        return $this->belongsTo('App\Smscampaign');
    }

    public function files() {
        return $this->hasMany('App\SmscampaignFile');
    }

    public function results() {
        return $this->hasMany('App\SmscampaignPlanningResult');
    }

    public function setStatus($save = true) {

        $this->nb_to_import = $this->files()->sum('nb_rows');
        $this->nb_import_success = $this->files()->sum('nb_rows_imported');
        $this->nb_import_failed = $this->files()->sum('nb_rows_failed');

        if ($this->nb_to_import == $this->nb_import_failed) {
            // échec importation fichier(s)
            $this->smscampaign_status_id = SmscampaignStatus::coded("5")->first()->id;
            $this->setImportEnd(false);
        } elseif ($this->nb_to_import == ($this->nb_import_success + $this->nb_import_failed)) {

            $this->setImportEnd(false);

            $this->stat_all = $this->results()->count();
            $this->stat_success = $this->results()->where('stat_success', 1)->count();
            $this->stat_failed = $this->results()->where('stat_failed', 1)->count();
            $this->stat_done = $this->results()->where('stat_done', 1)->count();

            if ($this->stat_all == $this->stat_done) {
                // Traitement terminé
                if ($this->stat_all == $this->stat_failed) {
                    // échec traitement
                    $this->smscampaign_status_id = SmscampaignStatus::coded("10")->first()->id;
                } elseif ($this->stat_failed > 0) {
                    // traitement effectué avec erreur(s)
                    $this->smscampaign_status_id = SmscampaignStatus::coded("9")->first()->id;
                } else {
                    // succès traitement
                    $this->smscampaign_status_id = SmscampaignStatus::coded("8")->first()->id;
                }
                // Set End Date
                $this->setSendingEnd(false);
            } elseif ($this->stat_done > 0) {
                // traitement en cours
                $this->smscampaign_status_id = SmscampaignStatus::coded("7")->first()->id;
                // Set Start Date
                $this->setSendingStart(false);
            } else {
                // attente traitement
                $this->smscampaign_status_id = SmscampaignStatus::coded("6")->first()->id;
            }
        } elseif (($this->nb_import_success + $this->nb_import_failed) > 0) {
            // importation fichier(s) en cours
            $this->smscampaign_status_id = SmscampaignStatus::coded("2")->first()->id;
            $this->setImportStart(false);
        } else {
            // attente importation fichier(s)
            $this->smscampaign_status_id = SmscampaignStatus::coded("1")->first()->id;
        }

        if ($save) {
            $this->save();
        }
    }

    public function setImportEnd($save = true) {
        if (!$this->importend_at) {
            $last_date = DB::table('smscampaign_files')->where('smscampaign_planning_id', $this->id)->max('importend_at');
            $this->importend_at = $last_date;
        }

        if ($save) {
            $this->save();
        }
    }

    public function setImportStart($save = true) {
        if (!$this->importstart_at) {
            $first_date = DB::table('smscampaign_files')->where('smscampaign_planning_id', $this->id)->min('importstart_at');
            $this->importstart_at = $first_date;
        }
    }

    public function setSendingEnd($save = true) {
        if (!$this->sendingend_at) {
            $last_date = DB::table('smscampaign_planning_results')->where('smscampaign_planning_id', $this->id)->max('sendingend_at');
            $this->sendingend_at = $last_date;
        }

        if ($save) {
            $this->save();
        }
    }

    public function setSendingStart($save = true) {
        if (!$this->sendingstart_at) {
            $first_date = DB::table('smscampaign_planning_results')->where('smscampaign_planning_id', $this->id)->min('sendingstart_at');
            $this->sendingstart_at = $first_date;
        }
    }
}
