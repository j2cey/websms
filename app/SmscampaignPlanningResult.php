<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SmsSendTrait;
use App\Traits\UuidTrait;

/**
 * Class SmscampaignPlanningResult
 * @package App
 *
 * @property integer $id
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
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class SmscampaignPlanningResult extends Model
{
    use SmsSendTrait, UuidTrait;

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

    // Simple debug callback
    public function printDebug($str) {
        echo date('Ymd H:i:s ').$str."\r\n";
    }

    public static function boot(){
        parent::boot();

        // Après chaque modification
        self::updated(function($model){
            // On met à jour le statut du planning parent
            $model->planning->setStatus();
        });

        // Avant creation
        self::creating(function($model){
            // On crée et assigne l'uuid
            $model->setUuid();
        });
    }
}
