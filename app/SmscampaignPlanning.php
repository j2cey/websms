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

    public function results() {
        return $this->hasMany('App\SmscampaignPlanningResult');
    }

    public function setStatus() {
        $this->stat_all = $this->results()->count();
        $this->stat_success = $this->results()->where('stat_success', 1)->count();
        $this->stat_failed = $this->results()->where('stat_failed', 1)->count();
        $this->stat_done = $this->results()->where('stat_done', 1)->count();

        if ($this->stat_all == $this->stat_done) {
            // fin traitement
            $this->smscampaign_status_id = SmscampaignStatus::coded("3")->first()->id;

            // Set End Date
            if (!$this->sendingend_at) {
                $last_result = $this->results()->max('sendingend_at')->first();
                $this->sendingend_at = $last_result->sendingend_at;
            }
        } elseif ($this->stat_done > 0) {
            // traitement en cours
            $this->smscampaign_status_id = SmscampaignStatus::coded("2")->first()->id;

            // Set Start Date
            if (!$this->sendingstart_at) {
                $first_result = $this->results()->min('sendingstart_at')->first();
                $this->sendingstart_at = $first_result->sendingstart_at;
            }
        } else {
            // attente traitement
            $this->smscampaign_status_id = SmscampaignStatus::coded("1")->first()->id;
        }

        $this->save();
    }
}
