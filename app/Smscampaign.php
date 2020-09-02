<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Smscampaign
 * @package App
 *
 * @property integer $id
 *
 * @property string $titre
 * @property string $expediteur
 * @property string $description
 * @property string $message
 * @property string $separateur_colonnes
 *
 * @property integer|null $smscampaign_type_id
 * @property integer|null $smscampaign_status_id
 *
 * @property \Illuminate\Support\Carbon $importstart_at
 * @property \Illuminate\Support\Carbon $importend_at
 *
 * @property integer $nb_to_import
 * @property integer $nb_import_success
 * @property integer $nb_import_failed
 *
 * @property integer $planning_sending
 * @property integer $planning_done
 * @property integer $planning_waiting
 *
 * @property \Illuminate\Support\Carbon $sendingstart_at
 * @property \Illuminate\Support\Carbon $sendingend_at
 *
 * @property integer $nb_to_send
 * @property integer $nb_send_success
 * @property integer $nb_send_failed
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Smscampaign extends Model
{
    protected $guarded = [];

    public function type() {
        return $this->belongsTo('App\SmscampaignType', 'smscampaign_type_id');
    }

    public function status() {
        return $this->belongsTo('App\SmscampaignStatus', 'smscampaign_status_id');
    }

    public function planningsAll() {
        return $this->hasMany('App\SmscampaignPlanning');
    }
    public function plannings() {
        return $this->hasMany('App\SmscampaignPlanning')->where('current', 1);
    }
    public function planningsPast() {
        return $this->hasMany('App\SmscampaignPlanning')->where('current', 0);
    }

    public function planningsSending() {
        // traitement en cours
        return $this->plannings()->where('smscampaign_status_id', SmscampaignStatus::coded("2")->first()->id);
    }

    public function planningsDone() {
        // fin traitement
        return $this->plannings()->where('smscampaign_status_id', SmscampaignStatus::coded("3")->first()->id);
    }

    public function planningsWaiting() {
        // fin traitement
        return $this->plannings()->where('smscampaign_status_id', SmscampaignStatus::coded("1")->first()->id);
    }

    public function scopeSearch($query,$titre,$exp,$campstatus,$descript,$dt_deb,$dt_fin) {
        if ($titre == null && ($dt_deb == null || $dt_fin == null) && $campstatus == null && $exp == null && $descript == null) return $query;

        if (!($campstatus == null)) {
            $query->whereIn('smscampaign_status_id', $campstatus);
        }

        if ( (!($dt_deb == null)) && (!($dt_fin == null)) ) {
            $query->whereBetween('created_at', [Carbon::createFromFormat('d/m/Y', $dt_deb)->format('Y-m-d'),Carbon::createFromFormat('d/m/Y', $dt_fin)->format('Y-m-d')]);
        }

        if (!($titre == null)) {
            $query->where('titre', $titre);
        }
        if (!($exp == null)) {
            $query->where('expediteur', 'LIKE', '%' . $exp . '%');
        }

        return $query;
    }

    public function setStatus($save = true) {

        $this->nb_to_import = $this->plannings()->sum('nb_to_import');
        $this->nb_import_success = $this->plannings()->sum('nb_import_success');
        $this->nb_import_failed = $this->plannings()->sum('nb_import_failed');

        if ($this->nb_to_import == $this->nb_import_failed) {
            // échec importation fichier(s)
            $this->smscampaign_status_id = SmscampaignStatus::coded("5")->first()->id;
            $this->setImportEnd(false);
        } elseif ($this->nb_to_import == ($this->nb_import_success + $this->nb_import_failed)) {

            $this->setImportEnd(false);

            $this->nb_to_send = $this->plannings()->sum('nb_to_send');
            $this->nb_send_success = $this->plannings()->sum('nb_send_success');
            $this->nb_send_failed = $this->plannings()->sum('nb_send_failed');

            if ($this->nb_to_send == ($this->nb_send_success + $this->nb_send_failed)) {
                // Traitement terminé
                if ($this->nb_to_send == $this->nb_send_failed) {
                    // échec envoi
                    $this->smscampaign_status_id = SmscampaignStatus::coded("10")->first()->id;
                } elseif ($this->nb_send_failed > 0) {
                    // traitement effectué avec erreur(s)
                    $this->smscampaign_status_id = SmscampaignStatus::coded("9")->first()->id;
                } else {
                    // succès traitement
                    $this->smscampaign_status_id = SmscampaignStatus::coded("8")->first()->id;
                }
                // Set End Date
                $this->setSendingEnd(false);
            } elseif (($this->nb_send_success + $this->nb_send_failed) > 0) {
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
            $last_date = DB::table('smscampaign_plannings')->where('smscampaign_id', $this->id)->max('importend_at');
            $this->importend_at = $last_date;
        }

        if ($save) {
            $this->save();
        }
    }

    public function setImportStart($save = true) {
        if (!$this->importstart_at) {
            $first_date = DB::table('smscampaign_plannings')->where('smscampaign_id', $this->id)->min('importstart_at');
            $this->importstart_at = $first_date;
        }
    }

    public function setSendingEnd($save = true) {
        if (!$this->sendingend_at) {
            $last_date = DB::table('smscampaign_plannings')->where('smscampaign_id', $this->id)->max('sendingend_at');
            $this->sendingend_at = $last_date;
        }

        if ($save) {
            $this->save();
        }
    }

    public function setSendingStart($save = true) {
        if (!$this->sendingstart_at) {
            $first_date = DB::table('smscampaign_plannings')->where('smscampaign_id', $this->id)->min('sendingstart_at');
            $this->sendingstart_at = $first_date;
        }
    }

    public function unsetCurrentPlannings() {
        $this->plannings()->update(['current' => 0]);
    }
}
