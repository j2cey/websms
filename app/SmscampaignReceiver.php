<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTrait;

/**
 * Class SmscampaignReceiver
 * @package App
 *
 * @property integer $id
 * @property string $uuid
 *
 * @property string $mobile
 * @property string|null $identite
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class SmscampaignReceiver extends Model
{
    use UuidTrait;

    protected $guarded = [];
    public function getRouteKeyName() { return 'uuid'; }

    public static function boot(){
        parent::boot();

        // Avant creation
        self::creating(function($model){
            // On crée et assigne l'uuid
            $model->setUuid();
        });
    }
}
