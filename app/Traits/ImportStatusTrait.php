<?php


namespace App\Traits;


use App\SmsimportStatus;
use Illuminate\Support\Facades\DB;

trait ImportStatusTrait
{
    public function setImportStatus($import_source_table, $ref_key, $save = true) {

        if ($this->nb_to_import == 0) {
            // aucun élément à traiter
            $this->smsimport_status_id = SmsimportStatus::coded("0")->first()->id;
        } else {
            if ($this->nb_to_import == ($this->nb_import_success + $this->nb_import_failed)) {
                // Importation terminée
                if ($this->nb_to_import == $this->nb_import_failed) {
                    // échec importation
                    $this->smsimport_status_id = SmsimportStatus::coded("5")->first()->id;
                } elseif ($this->nb_send_failed > 0) {
                    // traitement effectué avec erreur(s)
                    $this->smsimport_status_id = SmsimportStatus::coded("4")->first()->id;
                } else {
                    // succès traitement
                    $this->smsimport_status_id = SmsimportStatus::coded("3")->first()->id;
                }
                // Set End Date
                $this->setImportEnd($import_source_table, $ref_key, false);
            } elseif (($this->nb_send_success + $this->nb_send_failed) > 0) {
                // importation en cours
                $this->smsimport_status_id = SmsimportStatus::coded("2")->first()->id;
                // Set Start Date
                $this->setImportStart($import_source_table, $ref_key, false);
            } else {
                // attente traitement
                $this->smsimport_status_id = SmsimportStatus::coded("1")->first()->id;
            }
        }

        if ($save) {
            $this->save();
        }
    }

    public function setImportStart($import_source_table, $ref_key, $save = true) {
        if (!$this->importstart_at) {
            //$first_date = DB::table('smscampaign_plannings')->where('smscampaign_id', $this->id)->min('importstart_at');
            $first_date = DB::table($import_source_table)->where($ref_key, $this->id)->min('importstart_at');
            $this->importstart_at = $first_date;
        }
    }

    public function setImportEnd($import_source_table, $ref_key, $save = true) {
        if (!$this->importend_at) {
            //$last_date = DB::table('smscampaign_plannings')->where('smscampaign_id', $this->id)->max('importend_at');
            $last_date = DB::table($import_source_table)->where($ref_key, $this->id)->max('importend_at');
            $this->importend_at = $last_date;
        }

        if ($save) {
            $this->save();
        }
    }
}
