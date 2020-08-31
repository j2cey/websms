<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SmscampaignStatus
 * @package App
 *
 * @property integer $id
 *
 * @property string $titre
 * @property integer $code
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class SmscampaignStatus extends Model
{
    protected $guarded = [];
}
