<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ImportStatusTrait;
use App\Traits\SendStatusTrait;
use App\Traits\UuidTrait;

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
 * @property integer $nb_to_send
 * @property integer $nb_send_processing
 * @property integer $nb_send_success
 * @property integer $nb_send_failed
 * @property integer $nb_send_processed
 *
 * @property integer|null $smscampaign_id
 * @property integer|null $smscampaign_status_id
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class SmscampaignPlanning extends Model
{
    use ImportStatusTrait, SendStatusTrait, UuidTrait;

    protected $guarded = [];
    public function getRouteKeyName() { return 'uuid'; }

    public function campaign() {
        return $this->belongsTo('App\Smscampaign', 'smscampaign_id');
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

        $this->setImportStatus('smscampaign_files','smscampaign_planning_id',$save);

        $this->nb_to_send = $this->results()->count();
        $this->nb_send_success = $this->results()->where('send_processed', 1)->where('send_success', 1)->count();
        $this->nb_send_failed = $this->results()->where('send_processed', 1)->where('send_success', 0)->count();
        $this->nb_send_processing = $this->results()->where('send_processing', 1)->count();
        $this->nb_send_processed = $this->results()->where('send_processed', 1)->count();

        $this->setSendStatus('smscampaign_planning_results','smscampaign_planning_id',$save);
    }

    public static function boot(){
        parent::boot();

        // Après chaque modification
        self::updated(function($model){
            // On met à jour le statut de la campagne parente
            $model->campaign->setStatus();
        });

        // Avant creation
        self::creating(function($model){
            // On crée et assigne l'uuid
            $model->setUuid();
        });
    }
}
