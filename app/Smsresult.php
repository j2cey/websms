<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTrait;
use Illuminate\Support\Carbon;

/**
 * Class Smsresult
 * @package App
 *
 * @property integer $id
 * @property string $uuid
 *
 * @property Carbon $importstart_at
 * @property Carbon $importend_at
 * @property integer $nb_to_import
 * @property integer $nb_import_processing
 * @property integer $nb_import_success
 * @property integer $nb_import_failed
 * @property integer $nb_import_processed
 *
 * @property Carbon $sendingstart_at
 * @property Carbon $sendingend_at
 * @property integer $nb_to_send
 * @property integer $nb_send_processing
 * @property integer $nb_send_success
 * @property integer $nb_send_failed
 * @property integer $nb_send_processed
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Smsresult extends Model
{
    use UuidTrait;

    protected $guarded = [];
    public function getRouteKeyName() { return 'uuid'; }

    public function campaign() {
        return $this->hasOne('App\Smscampaign');
    }

    public function planning() {
        return $this->hasOne('App\SmscampaignPlanning');
    }

    public function setParentStatus() {
        if ($this->campaign) {
            $this->campaign->setStatus();
        } else {
            $this->planning->setStatus();
            //$this->planning->campaign->setStatus();
        }
    }

    public static function boot(){
        parent::boot();

        // Avant creation
        self::creating(function($model){
            // On crée et assigne l'uuid
            $model->setUuid();
        });

        // Après création
        self::created(function($model){
            // On met à jour le statut de la campagne parente
            //$model->setParentStatus();
        });

        // Après chaque modification
        self::updated(function($model){
            // On met à jour le statut de la campagne parente
            //$model->setParentStatus();
        });
    }
}
