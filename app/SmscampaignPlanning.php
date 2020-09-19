<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ImportStatusTrait;
use App\Traits\SendStatusTrait;
use App\Traits\UuidTrait;
use App\Traits\SmsResultTrait;

/**
 * Class SmscampaignPlanning
 * @package App
 *
 * @property integer $id
 * @property string $uuid
 *
 * @property boolean $current
 *
 * @property \Illuminate\Support\Carbon $plan_at
 * @property \Illuminate\Support\Carbon $plandone_at
 *
 * @property integer|null $smsimport_status_id
 * @property integer|null $smssend_status_id
 *
 * @property integer|null $smscampaign_id
 * @property integer|null $smsresult_id
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class SmscampaignPlanning extends Model
{
    use ImportStatusTrait, SendStatusTrait, UuidTrait, SmsResultTrait;

    protected $guarded = [];
    public function getRouteKeyName() { return 'uuid'; }

    #region Eloquent Relations

    public function campaign() {
        return $this->belongsTo('App\Smscampaign', 'smscampaign_id');
    }

    public function files() {
        return $this->hasMany('App\SmscampaignFile');
    }

    public function smsresult() {
        return $this->belongsTo('App\Smsresult', 'smsresult_id');;
    }

    public function importstatus() {
        return $this->belongsTo('App\SmsimportStatus', 'smsimport_status_id');
    }

    public function lines() {
        return $this->hasMany('App\SmscampaignPlanningLine');
    }

    public function sendstatus() {
        return $this->belongsTo('App\SmssendStatus', 'smssend_status_id');
    }

    #endregion

    #region Customs Functions

    public function setStatus($save = true) {

        $this->setImportStatus($save);

        $this->setSendStatus($save);
    }

    public function resetFailedFilesCursor() {
        $this->files()->where('nb_rows_failed', '>', 0)->update([
            'imported' => 0,
            'row_last_processed' => 0,
        ]);
    }

    public function resetFailedLinesCursor() {
        $this->lines()->where('send_success', 0)->update([
            'send_processed' => 0,
        ]);
    }

    public function suspend() {
        // Suspend all files
        foreach ($this->files as $file) {
            $file->suspend();
        }

        // Suspend all sends
        foreach ($this->lines as $result) {
            $result->suspend();
        }
    }

    #endregion

    public static function boot(){
        parent::boot();

        // Après chaque modification
        self::updated(function($model){
            // On met à jour le statut de la campagne parente
            //$model->campaign->setStatus();
        });

        // Avant creation
        self::creating(function($model){
            // On crée et assigne l'uuid
            $model->setUuid();
        });
    }
}
