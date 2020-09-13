<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SmsSendTrait;
use App\Traits\UuidTrait;
use App\Traits\SuspendableTrait;
use Illuminate\Support\Carbon;

/**
 * Class SmscampaignPlanningLine
 * @package App
 *
 * @property integer $id
 * @property string $uuid
 *
 * @property string $message
 * @property \Illuminate\Support\Carbon $sendingstart_at
 * @property \Illuminate\Support\Carbon $sendingend_at
 * @property boolean $send_processing
 * @property boolean $send_success
 * @property boolean $send_processed
 * @property integer $nb_try
 *
 * @property integer|null $smscampaign_planning_id
 * @property integer|null $smscampaign_receiver_id
 *
 * @property string $report
 *
 * @property \Illuminate\Support\Carbon $suspended_at
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class SmscampaignPlanningLine extends Model
{
    use SmsSendTrait, UuidTrait, SuspendableTrait;

    protected $guarded = [];
    protected $casts = [
        'report' => 'array'
    ];
    public function getRouteKeyName() { return 'uuid'; }

    public function planning() {
        return $this->belongsTo('App\SmscampaignPlanning','smscampaign_planning_id');
    }

    public function receiver() {
        return $this->belongsTo('App\SmscampaignReceiver','smscampaign_receiver_id');
    }

    #region Scopes

    public function scopeSearch($query,$campaign_id,$treatmentresult,$dt_deb,$dt_fin) {
        if ($campaign_id == null && ($dt_deb == null || $dt_fin == null) && $treatmentresult == null) return $query;

        if (!($campaign_id == null)) {
            $planning_ids = SmscampaignPlanning::where('smscampaign_id', $campaign_id)->pluck('id');
            $query->whereIn('smscampaign_planning_id', $planning_ids);
        }

        if (!($treatmentresult == null)) {
            //$query->whereIn('smsimport_status_id', $treatmentresult);
            if (in_array("0", $treatmentresult)) {
                // élément non traité
                $query->where('send_processed', 0);
            }
            if (in_array("-1", $treatmentresult)) {
                // échec de traitement
                $query->where('send_processed', 1)->where('send_success', 0);
            }
            if (in_array("1", $treatmentresult)) {
                // succès de traitement
                $query->where('send_processed', 1)->where('send_success', 1);
            }
        }

        if ( (!($dt_deb == null)) && (!($dt_fin == null)) ) {
            $query->whereBetween('created_at', [Carbon::createFromFormat('d/m/Y', $dt_deb)->format('Y-m-d'),Carbon::createFromFormat('d/m/Y', $dt_fin)->format('Y-m-d')]);
        }

        return $query;
    }

    #endregion

    // Simple debug callback
    public function printDebug($str) {
        echo date('Ymd H:i:s ').$str."\r\n";
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
