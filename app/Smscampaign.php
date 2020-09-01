<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
 * @property boolean $messages_individuels
 *
 * @property integer|null $smscampaign_type_id
 * @property integer|null $smscampaign_status_id
 *
 * @property integer $planning_sending
 * @property integer $planning_done
 * @property integer $planning_waiting
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Smscampaign extends Model
{
    protected $guarded = [];

    public function type() {
        return $this->belongsTo('App\SmscampaignType', 'smscampaign_type_id');
    }

    public function status() {
        return $this->belongsTo('App\SmscampaignStatus', 'smscampaign_status_id');
    }

    public function plannings() {
        return $this->hasMany('App\SmscampaignPlanning');
    }

    public function planningsSending() {
        // traitement en cours
        return $this->plannings()->where('smscampaign_status_id', SmscampaignStatus::coded("2")->first()->id);
    }

    public function planningsDone() {
        // fin traitement
        return $this->plannings()->where('smscampaign_status_id', SmscampaignStatus::coded("3")->first()->id);
    }

    public function planningsWaiting() {
        // fin traitement
        return $this->plannings()->where('smscampaign_status_id', SmscampaignStatus::coded("1")->first()->id);
    }

    public function scopeSearch($query,$titre,$exp,$campstatus,$descript,$dt_deb,$dt_fin) {
        if ($titre == null && ($dt_deb == null || $dt_fin == null) && $campstatus == null && $exp == null && $descript == null) return $query;

        if (!($campstatus == null)) {
            $query->whereIn('smscampaign_status_id', $campstatus);
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

    public function setStatus() {
        $this->planning_sending = $this->planningsSending()->count();
        $this->planning_done = $this->planningsDone()->count();
        $this->planning_waiting = $this->planningsWaiting()->count();

        if ($this->planning_waiting > 0) {
            // attente traitement
            $this->smscampaign_status_id = SmscampaignStatus::coded("1")->first()->id;

        } elseif ($this->planning_sending > 0) {
            // traitement en cours
            $this->smscampaign_status_id = SmscampaignStatus::coded("2")->first()->id;
        } else {
            // fin traitement
            $this->smscampaign_status_id = SmscampaignStatus::coded("3")->first()->id;
        }

        $this->save();
    }
}
