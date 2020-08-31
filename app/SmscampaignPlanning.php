<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SmscampaignPlanning
 * @package App
 *
 * @property integer $id
 *
 * @property \Illuminate\Support\Carbon $plan_at
 * @property \Illuminate\Support\Carbon $plandone_at
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
}
