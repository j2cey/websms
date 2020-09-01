<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SmscampaignFile
 * @package App
 *
 * @property integer $id
 *
 * @property string $name
 * @property boolean $imported
 * @property \Illuminate\Support\Carbon $imported_at
 *
 * @property integer|null $smscampaign_id
 * @property integer|null $smscampaign_status_id
 *
 * @property integer $nb_rows
 * @property integer $nb_rows_imported
 * @property integer $nb_rows_failed
 * @property string $import_report
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class SmscampaignFile extends Model
{
    protected $guarded = [];

    public function campaign() {
        return $this->belongsTo('App\Smscampaign', 'smscampaign_id');
    }

    public function status() {
        return $this->belongsTo('App\SmscampaignStatus', 'smscampaign_status_id');
    }
}
