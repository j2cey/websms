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
}
