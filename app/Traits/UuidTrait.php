<?php


namespace App\Traits;


use Illuminate\Support\Str;

trait UuidTrait
{
    public function setUuid($save = false, $prefix = "") {
        // Si le préfixe est vide ...
        if ($prefix == "") {
            // ... on utilise l'id unique de PHP et l'id du modèle pour créer le code
            $this->uuid = Str::slug( (string) Str::orderedUuid() );//uniqid("", true) . ( isset($this->id) ? "_" . $this->id : "");
        } else {
            // Sinon, on concatène le préfixe et l'id pour créer le code
            $this->uuid = Str::slug( $prefix . "_" . ((string) Str::orderedUuid()) );//Str::slug($prefix) . "_" . ( isset($this->id) ? "_" . $this->id : "");
        }

        if ($save) {
            // On enregistre les modifications apportées au modèle
            $this->save();
        }
    }
}
