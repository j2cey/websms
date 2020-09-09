<?php


namespace App\Traits;

use App\SmsimportStatus;
use App\Smsresult;

trait ImportStatusTrait
{
    public function setImportStatus($save = true) {

        $smsresult = Smsresult::where('id', $this->smsresult_id)->first();

        if (is_null($smsresult) || $smsresult->nb_to_import == 0) {
            // aucun élément à traiter
            $this->smsimport_status_id = SmsimportStatus::coded("0")->first()->id;
        } else {
            if ($smsresult->nb_to_import == ($smsresult->nb_import_success + $smsresult->nb_import_failed)) {
                // Importation terminée
                if ($smsresult->nb_to_import == $smsresult->nb_import_failed) {
                    // échec importation
                    $this->smsimport_status_id = SmsimportStatus::coded("5")->first()->id;
                } elseif ($smsresult->nb_import_failed > 0) {
                    // traitement effectué avec erreur(s)
                    $this->smsimport_status_id = SmsimportStatus::coded("4")->first()->id;
                } else {
                    // succès traitement
                    $this->smsimport_status_id = SmsimportStatus::coded("3")->first()->id;
                }
            } elseif (($smsresult->nb_import_processed) > 0) {
                // importation en cours
                $this->smsimport_status_id = SmsimportStatus::coded("2")->first()->id;
            } else {
                // attente traitement
                $this->smsimport_status_id = SmsimportStatus::coded("1")->first()->id;
            }
        }

        if ($save) {
            $this->save();
        }
    }
}
