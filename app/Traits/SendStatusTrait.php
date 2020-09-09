<?php


namespace App\Traits;


use App\Smsresult;
use App\SmssendStatus;

trait SendStatusTrait
{
    public function setSendStatus($save = true) {

        $smsresult = Smsresult::where('id', $this->smsresult_id)->first();

        if (is_null($smsresult) ||$smsresult->nb_to_send == 0) {
            // aucun élément à traiter
            $this->smssend_status_id = SmssendStatus::coded("0")->first()->id;
        } else {
            if ($smsresult->nb_to_send == $smsresult->nb_send_processed) {
                // Envioie terminée
                if ($smsresult->nb_to_send == $smsresult->nb_send_failed) {
                    // échec envoie
                    $this->smssend_status_id = SmssendStatus::coded("5")->first()->id;
                } elseif ($smsresult->nb_send_failed > 0) {
                    // traitement effectué avec erreur(s)
                    $this->smssend_status_id = SmssendStatus::coded("4")->first()->id;
                } else {
                    // succès traitement
                    $this->smssend_status_id = SmssendStatus::coded("3")->first()->id;
                }
                // Set End Date
                //$this->setSendEnd($send_source_table, $ref_key, $ref_vals_arr);
            } elseif ($smsresult->nb_send_processed > 0) {
                // envoie en cours
                $this->smssend_status_id = SmssendStatus::coded("2")->first()->id;
                // Set Start Date
                //$this->setSendStart($send_source_table, $ref_key, $ref_vals_arr);
            } else {
                // attente traitement
                $this->smssend_status_id = SmssendStatus::coded("1")->first()->id;
            }
        }

        if ($save) {
            $this->save();
        }
    }
}
