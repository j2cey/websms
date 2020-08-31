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
 * @property integer|null $smscampaign_status_id
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Smscampaign extends Model
{
    protected $guarded = [];

    public function status() {
        return $this->belongsTo('App\SmscampaignStatus', 'smscampaign_status_id');
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
}
