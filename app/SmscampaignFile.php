<?php

namespace App;

use App\Traits\SmsImportFileTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Traits\UuidTrait;
use App\Traits\SuspendableTrait;

/**
 * Class SmscampaignFile
 * @package App
 *
 * @property integer $id
 * @property string $uuid
 *
 * @property string $name
 * @property boolean $imported
 *
 * @property \Illuminate\Support\Carbon $importstart_at
 * @property \Illuminate\Support\Carbon $importend_at
 *
 * @property integer|null $smscampaign_planning_id
 * @property integer|null $smsimport_status_id
 *
 * @property integer $nb_rows
 * @property integer $nb_rows_success
 * @property integer $nb_rows_failed
 * @property integer $nb_rows_processing
 * @property integer $nb_rows_processed
 *
 * @property string $row_last_processed
 * @property integer $nb_try
 * @property string $report
 *
 * @property \Illuminate\Support\Carbon $suspended_at
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class SmscampaignFile extends Model
{
    use SmsImportFileTrait, UuidTrait, SuspendableTrait;

    protected $guarded = [];
    protected $casts = [
        'report' => 'array'
    ];
    public function getRouteKeyName() { return 'uuid'; }

    public function planning() {
        return $this->belongsTo('App\SmscampaignPlanning', 'smscampaign_planning_id');
    }

    public function importstatus() {
        return $this->belongsTo('App\SmsimportStatus', 'smsimport_status_id');
    }

    public function setStatus($save = true) {

        $this->imported = ($this->nb_rows == ($this->nb_rows_success + $this->nb_rows_failed));

        if ($this->imported) {
            // Importation terminé
            if ($this->nb_rows == $this->nb_rows_failed) {
                // échec importation fichier(s)
                $this->smsimport_status_id = SmsimportStatus::coded("5")->first()->id;
            } elseif ($this->nb_rows_failed > 0) {
                // fichier(s) importé(s) avec erreur(s)
                $this->smsimport_status_id = SmsimportStatus::coded("4")->first()->id;
            } else {
                // succès importation fichier(s)
                $this->smsimport_status_id = SmsimportStatus::coded("3")->first()->id;
            }
            // Set End Date
            $this->setEnd(false);
        } elseif (($this->nb_rows_success + $this->nb_rows_failed) > 0) {
            // importation fichier(s) en cours
            $this->smsimport_status_id = SmsimportStatus::coded("2")->first()->id;
            // Set Start Date
            $this->setStart(false);
        } else {
            // attente traitement
            $this->smsimport_status_id = SmsimportStatus::coded("1")->first()->id;
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
            //$model->planning->setStatus();
        });

        // Avant creation
        self::creating(function($model){
            // On crée et assigne l'uuid
            $model->setUuid();
        });
    }
}
