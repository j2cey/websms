<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ImportStatusTrait;
use App\Traits\SendStatusTrait;
use Illuminate\Support\Carbon;
use App\Traits\UuidTrait;
use App\Traits\SmsResultTrait;
use App\Traits\SmscampaignTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Smscampaign
 * @package App
 *
 * @property integer $id
 * @property string $uuid
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
 * @property integer $planning_sending
 * @property integer $planning_done
 * @property integer $planning_waiting
 *
 * @property integer|null $user_id
 * @property integer|null $smsresult_id
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $deleted_at
 */
class Smscampaign extends Model
{
    use ImportStatusTrait, SendStatusTrait, UuidTrait, SmscampaignTrait, SoftDeletes, SmsResultTrait;

    protected $guarded = [];
    public function getRouteKeyName() { return 'uuid'; }

    #region Eloquent Relations

    public function type() {
        return $this->belongsTo('App\SmscampaignType', 'smscampaign_type_id');
    }

    public function importstatus() {
        return $this->belongsTo('App\SmsimportStatus', 'smsimport_status_id');
    }

    public function sendstatus() {
        return $this->belongsTo('App\SmssendStatus', 'smssend_status_id');
    }

    public function smsresult() {
        return $this->belongsTo('App\Smsresult', 'smsresult_id');;
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

    #endregion

    #region Validation Rules

    public static function defaultRules() {
        return [
            'titre' => 'required',
            'type' => 'required',
            'expediteur' => 'required',
            'message'  => 'required_unless:type,1'
        ];
    }
    public static function createRules() {
        return array_merge(self::defaultRules(), [
            'fichier_destinataires' => 'required|mimes:csv,txt',
            'separateur_colonnes' => 'required',
        ]);
    }
    public static function updateRules($model) {
        return array_merge(self::defaultRules(), [
            'separateur_colonnes'  => 'required_with:fichier_destinataires'
        ]);
    }

    #endregion

    #region Scopes

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

    #endregion

    #region Customs Functions

    public function setStatus($save = true) {

        $this->setImportStatus($save);

        $this->setSendStatus($save);
    }

    public function getPlanningsImportResultsIds() {
        $this->plannings()->pluck('smsimport_result_id');
    }
    public function getPlanningsSendResultsIds() {
        $this->plannings()->pluck('smssend_result_id');
    }

    public function getImportPercentage() {
        if ($this->smsresult && ($this->smsresult->nb_to_import > 0)) {
            return round((($this->smsresult->nb_import_processed) / $this->smsresult->nb_to_import) * 100, 0);
        } else {
            return 0;
        }
    }

    public function getSendPercentage() {
        if ($this->smsresult && ($this->smsresult->nb_to_send > 0)) {
            return round(($this->smsresult->nb_send_processed / $this->smsresult->nb_to_send) * 100, 0);
        } else {
            return 0;
        }
    }

    public function unsetCurrentPlannings() {
        $this->plannings()->update(['current' => 0]);
    }

    public function resetFailedFilesCursor() {
        foreach ($this->plannings as $planning) {
            $planning->resetFailedFilesCursor();
        }
    }

    public function resetFailedLinesCursor() {
        foreach ($this->plannings as $planning) {
            $planning->resetFailedLinesCursor();
        }
    }

    public function suspend() {
        foreach ($this->plannings as $planning) {
            $planning->suspend();
        }
    }

    #endregion

    public static function boot(){
        parent::boot();

        // Avant creation
        self::creating(function($model){
            // On crée et assigne l'uuid
            $model->setUuid();
        });
    }

    #region Import results
    public function getNbToImport(): int
    {
        $nb_to_import = 0;
        foreach ($this->plannings as $planning) {
            $nb_to_import += $planning->getNbToImport();
        }
        return $nb_to_import;
    }
    public function getNbImportProcessing(): int
    {
        $nb_import_processing = 0;
        foreach ($this->plannings as $planning) {
            $nb_import_processing += $planning->getNbImportProcessing();
        }
        return $nb_import_processing;
    }
    public function getNbImportSuccess(): int
    {
        $nb_import_success = 0;
        foreach ($this->plannings as $planning) {
            $nb_import_success += $planning->getNbImportSuccess();
        }
        return $nb_import_success;
    }
    public function getNbImportFailed(): int
    {
        $nb_import_failed = 0;
        foreach ($this->plannings as $planning) {
            $nb_import_failed += $planning->getNbImportFailed();
        }
        return $nb_import_failed;
    }
    public function getNbImportProcessed(): int
    {
        $nb_import_processed = 0;
        foreach ($this->plannings as $planning) {
            $nb_import_processed += $planning->getNbImportProcessed();
        }
        return $nb_import_processed;
    }
    #endregion

    #region Send results
    public function getNbToSend(): int
    {
        $nb_to_send = 0;
        foreach ($this->plannings as $planning) {
            $nb_to_send += $planning->getNbToSend();
        }
        return $nb_to_send;
    }
    public function getNbSendProcessing(): int
    {
        $nb_send_processing = 0;
        foreach ($this->plannings as $planning) {
            $nb_send_processing += $planning->getNbSendProcessing();
        }
        return $nb_send_processing;
    }
    public function getNbSendSuccess(): int
    {
        $nb_send_success = 0;
        foreach ($this->plannings as $planning) {
            $nb_send_success += $planning->getNbSendSuccess();
        }
        return $nb_send_success;
    }
    public function getNbSendFailed(): int
    {
        $nb_send_failed = 0;
        foreach ($this->plannings as $planning) {
            $nb_send_failed += $planning->getNbSendFailed();
        }
        return $nb_send_failed;
    }
    public function getNbSendProcessed(): int
    {
        $nb_send_processed = 0;
        foreach ($this->plannings as $planning) {
            $nb_send_processed += $planning->getNbSendProcessed();
        }
        return $nb_send_processed;
    }
    #endregion
}
