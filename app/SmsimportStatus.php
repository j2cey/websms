<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTrait;

/**
 * Class SmsimportStatus
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
class SmsimportStatus extends Model
{
    use UuidTrait;

    protected $guarded = [];
    public function getRouteKeyName() { return 'uuid'; }

    public function scopeCoded($query, $code) {
        return $query
            ->where('code', $code)
            ;
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
