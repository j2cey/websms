<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use App\GTSMSC\SmssendableTrait;
use App\Traits\Sms;

/**
 * Class SmscampaignPlanningResult
 * @package App
 *
 * @property integer $id
 *
 * @property string $message
 * @property \Illuminate\Support\Carbon $sendingstart_at
 * @property \Illuminate\Support\Carbon $sendingend_at
 * @property boolean $stat_sending
 * @property boolean $stat_success
 * @property boolean $stat_failed
 * @property string $stat_failed_msg
 * @property boolean $stat_done
 *
 * @property integer|null $smscampaign_planning_id
 * @property integer|null $smscampaign_receiver_id
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class SmscampaignPlanningResult extends Model
{
    //use SmssendableTrait;
    use Sms;
    protected $guarded = [];

    public function planning() {
        return $this->belongsTo('App\SmscampaignPlanning');
    }

    public function receiver() {
        return $this->belongsTo('App\SmscampaignPlanningReceiver');
    }

    // Simple debug callback
    public function printDebug($str) {
        echo date('Ymd H:i:s ').$str."\r\n";
    }
}
