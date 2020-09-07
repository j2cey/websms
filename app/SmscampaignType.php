<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidTrait;

/**
 * Class SmscampaignPlanningResult
 * @package App
 *
 * @property integer $id
 * @property string $uuid
 *
 * @property string $code
 * @property string $titre
 *
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class SmscampaignType extends Model
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
