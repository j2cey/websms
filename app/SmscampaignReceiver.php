<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SmscampaignReceiver
 * @package App
 *
 * @property integer $id
 *
 * @property string $mobile
 * @property string|null $identite
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class SmscampaignReceiver extends Model
{
    protected $guarded = [];
}
