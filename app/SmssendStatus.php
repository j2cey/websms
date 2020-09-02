<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SmssendStatus
 * @package App
 *
 * @property integer $id
 *
 * @property string $titre
 * @property integer $code
 * @property integer $description
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class SmssendStatus extends Model
{
    protected $guarded = [];

    public function scopeCoded($query, $code) {
        return $query
            ->where('code', $code)
            ;
    }
}
