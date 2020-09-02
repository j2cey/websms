<?php


namespace App\Traits;


use App\SmssendStatus;
use Illuminate\Support\Facades\DB;

trait SendStatusTrait
{
    public function setSendStatus($send_source_table, $ref_key, $save = true) {

        if ($this->nb_to_send == 0) {
            // aucun élément à traiter
            $this->smssend_status_id = SmssendStatus::coded("0")->first()->id;
        } else {
            if ($this->nb_to_send == $this->nb_send_processed) {
                // Envioie terminée
                if ($this->nb_to_send == $this->nb_send_failed) {
                    // échec envoie
                    $this->smssend_status_id = SmssendStatus::coded("5")->first()->id;
                } elseif ($this->nb_send_failed > 0) {
                    // traitement effectué avec erreur(s)
                    $this->smssend_status_id = SmssendStatus::coded("4")->first()->id;
                } else {
                    // succès traitement
                    $this->smssend_status_id = SmssendStatus::coded("3")->first()->id;
                }
                // Set End Date
                $this->setSendEnd($send_source_table, $ref_key, false);
            } elseif ($this->nb_send_processed > 0) {
                // envoie en cours
                $this->smssend_status_id = SmssendStatus::coded("2")->first()->id;
                // Set Start Date
                $this->setSendStart($send_source_table, $ref_key, false);
            } else {
                // attente traitement
                $this->smssend_status_id = SmssendStatus::coded("1")->first()->id;
            }
        }

        if ($save) {
            $this->save();
        }
    }

    public function setSendStart($send_source_table, $ref_key, $save = true) {
        if (!$this->sendingstart_at) {
            //$first_date = DB::table('smscampaign_plannings')->where('smscampaign_id', $this->id)->min('sendingstart_at');
            $first_date = DB::table($send_source_table)->where($ref_key, $this->id)->min('sendingstart_at');
            $this->sendingstart_at = $first_date;
        }
    }

    public function setSendEnd($send_source_table, $ref_key, $save = true) {
        if (!$this->sendingend_at) {
            //$last_date = DB::table('smscampaign_plannings')->where('smscampaign_id', $this->id)->max('sendingend_at');
            $last_date = DB::table($send_source_table)->where($ref_key, $this->id)->max('sendingend_at');
            $this->sendingend_at = $last_date;
        }

        if ($save) {
            $this->save();
        }
    }
}
