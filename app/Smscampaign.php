<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ImportStatusTrait;
use App\Traits\SendStatusTrait;
use Illuminate\Support\Carbon;
use App\Traits\UuidTrait;

/**
 * Class Smscampaign
 * @package App
 *
 * @property integer $id
 *
 * @property string $titre
 * @property string $expediteur
 * @property string $description
 * @property string $message
 * @property string $separateur_colonnes
 *
 * @property integer|null $smscampaign_type_id
 * @property integer|null $smsimport_status_id
 * @property integer|null $smssend_status_id
 *
 * @property \Illuminate\Support\Carbon $importstart_at
 * @property \Illuminate\Support\Carbon $importend_at
 *
 * @property integer $nb_to_import
 * @property integer $nb_import_success
 * @property integer $nb_import_failed
 *
 * @property integer $planning_sending
 * @property integer $planning_done
 * @property integer $planning_waiting
 *
 * @property \Illuminate\Support\Carbon $sendingstart_at
 * @property \Illuminate\Support\Carbon $sendingend_at
 *
 * @property integer $nb_to_send
 * @property integer $nb_send_processing
 * @property integer $nb_send_success
 * @property integer $nb_send_failed
 * @property integer $nb_send_processed
 *
 * @property integer|null $user_id
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Smscampaign extends Model
{
    use ImportStatusTrait, SendStatusTrait, UuidTrait;

    protected $guarded = [];
    public function getRouteKeyName() { return 'uuid'; }

    public function type() {
        return $this->belongsTo('App\SmscampaignType', 'smscampaign_type_id');
    }

    public function importstatus() {
        return $this->belongsTo('App\SmsimportStatus', 'smsimport_status_id');
    }
    public function sendstatus() {
        return $this->belongsTo('App\SmssendStatus', 'smssend_status_id');
    }

    public function planningsAll() {
        return $this->hasMany('App\SmscampaignPlanning');
    }
    public function plannings() {
        return $this->hasMany('App\SmscampaignPlanning')->where('current', 1);
    }
    public function planningsPast() {
        return $this->hasMany('App\SmscampaignPlanning')->where('current', 0);
    }

    public function scopeSearch($query,$titre,$exp,$importstatus,$sendstatus,$descript,$dt_deb,$dt_fin) {
        if ($titre == null && ($dt_deb == null || $dt_fin == null) && $importstatus == null && $sendstatus == null && $exp == null && $descript == null) return $query;

        if (!($importstatus == null)) {
            $query->whereIn('smsimport_status_id', $importstatus);
        }

        if (!($sendstatus == null)) {
            $query->whereIn('smssend_status_id', $sendstatus);
        }

        if ( (!($dt_deb == null)) && (!($dt_fin == null)) ) {
            $query->whereBetween('created_at', [Carbon::createFromFormat('d/m/Y', $dt_deb)->format('Y-m-d'),Carbon::createFromFormat('d/m/Y', $dt_fin)->format('Y-m-d')]);
        }

        if (!($titre == null)) {
            $query->where('titre', $titre);
        }
        if (!($exp == null)) {
            $query->where('expediteur', 'LIKE', '%' . $exp . '%');
        }

        return $query;
    }

    public function setStatus($save = true) {
        $this->nb_to_import = $this->plannings()->sum('nb_to_import');
        $this->nb_import_success = $this->plannings()->sum('nb_import_success');
        $this->nb_import_failed = $this->plannings()->sum('nb_import_failed');

        $this->setImportStatus('smscampaign_plannings', 'smscampaign_id',$save);

        $this->nb_to_send = $this->plannings()->sum('nb_to_send');
        $this->nb_send_success = $this->plannings()->sum('nb_send_success');
        $this->nb_send_failed = $this->plannings()->sum('nb_send_failed');
        $this->nb_send_processing = $this->plannings()->sum('nb_send_processing');
        $this->nb_send_processed = $this->plannings()->sum('nb_send_processed');

        $this->setSendStatus('smscampaign_plannings', 'smscampaign_id',$save);
    }

    public function unsetCurrentPlannings() {
        $this->plannings()->update(['current' => 0]);
    }

    public static function boot(){
        parent::boot();

        // Avant creation
        self::creating(function($model){
            // On crée et assigne l'uuid
            $model->setUuid();
        });
    }
}
