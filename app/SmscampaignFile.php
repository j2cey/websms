<?php

namespace App;

use App\Traits\SmsImportFileTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class SmscampaignFile
 * @package App
 *
 * @property integer $id
 *
 * @property string $name
 * @property boolean $imported
 *
 * @property \Illuminate\Support\Carbon $importstart_at
 * @property \Illuminate\Support\Carbon $importend_at
 *
 * @property integer|null $smscampaign_planning_id
 * @property integer|null $smscampaign_status_id
 *
 * @property integer $nb_rows
 * @property integer $nb_rows_imported
 * @property integer $nb_rows_failed
 *
 * @property string $row_last_processed
 * @property string $import_report
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class SmscampaignFile extends Model
{
    use SmsImportFileTrait;

    protected $guarded = [];
    protected $casts = [
        'import_report' => 'array'
    ];

    public function planning() {
        return $this->belongsTo('App\SmscampaignPlanning', 'smscampaign_planning_id');
    }

    public function status() {
        return $this->belongsTo('App\SmscampaignStatus', 'smscampaign_status_id');
    }

    public function setStatus($save = true) {

        $this->imported = ($this->nb_rows == ($this->nb_rows_imported + $this->nb_rows_failed));

        if ($this->imported) {
            // Importation terminé
            if ($this->nb_rows == $this->nb_rows_failed) {
                // échec importation fichier(s)
                $this->smscampaign_status_id = SmscampaignStatus::coded("5")->first()->id;
            } elseif ($this->nb_rows_failed > 0) {
                // fichier(s) importé(s) avec erreur(s)
                $this->smscampaign_status_id = SmscampaignStatus::coded("4")->first()->id;
            } else {
                // succès importation fichier(s)
                $this->smscampaign_status_id = SmscampaignStatus::coded("3")->first()->id;
            }
            // Set End Date
            $this->setEnd(false);
        } elseif (($this->nb_rows_imported + $this->nb_rows_failed) > 0) {
            // importation fichier(s) en cours
            $this->smscampaign_status_id = SmscampaignStatus::coded("2")->first()->id;
            // Set Start Date
            $this->setStart(false);
        } else {
            // attente traitement
            $this->smscampaign_status_id = SmscampaignStatus::coded("1")->first()->id;
        }

        if ($save) {
            $this->save();
        }
    }

    public function setEnd($save = true) {
        if (!$this->importend_at) {
            $this->importend_at = Carbon::now();
        }

        if ($save) {
            $this->save();
        }
    }

    public function setStart($save = true) {
        if (!$this->importstart_at) {
            $this->importstart_at = Carbon::now();
        }

        if ($save) {
            $this->save();
        }
    }

    public static function boot(){
        parent::boot();

        // Après chaque modification
        self::updated(function($model){
            // On met à jour le statut du planning parent
            $model->planning->setStatus();
        });
    }
}
