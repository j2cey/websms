<?php

namespace App\Traits;

use App\Smsresult;
use Illuminate\Support\Carbon;

trait SmsResultTrait
{
    public function addImportResult($nb_to_import, $nb_import_processing, $nb_import_success, $nb_import_failed, $nb_import_processed) {

        if (is_null($this->smsresult_id)) {
            $new_importresult = Smsresult::create([
                'nb_to_import' => $nb_to_import,
                'nb_import_processing' => $nb_import_processing,
                'nb_import_success' => $nb_import_success,
                'nb_import_failed' => $nb_import_failed,
                'nb_import_processed' => $nb_import_processed,
                'importstart_at' => Carbon::now(),
            ]);
            $this->update([
                'smsresult_id' => $new_importresult->id,
            ]);
        } else {
            $old_importresult = $this->smsresult;
            $data_array = [
                'nb_to_import' => ($old_importresult->nb_to_import + $nb_to_import),
                'nb_import_processing' => ($old_importresult->nb_import_processing + $nb_import_processing),
                'nb_import_success' => ($old_importresult->nb_import_success + $nb_import_success),
                'nb_import_failed' => ($old_importresult->nb_import_failed + $nb_import_failed),
                'nb_import_processed' => ($old_importresult->nb_import_processed + $nb_import_processed),
            ];
            if ($data_array['nb_to_import'] > 0 && ($data_array['nb_to_import']== $data_array['nb_import_processed'])) {
                $data_array['importend_at'] = Carbon::now();
            }
            $this->smsresult->update($data_array);
        }

        $this->setStatus(true);

        // si l'appellant est un planning, on met à jour la partie importation de son smsresult de sa campagne parente
        if (isset($this->campaign)) {
            $this->campaign->addImportResult($nb_to_import, $nb_import_processing, $nb_import_success, $nb_import_failed, $nb_import_processed);
        }
    }

    public function addSendResult($nb_to_send, $nb_send_processing, $nb_send_success, $nb_send_failed, $nb_send_processed) {

        if (is_null($this->smsresult_id)) {
            $new_sendresult = Smsresult::create([
                'nb_to_send' => $nb_to_send,
                'nb_send_processing' => $nb_send_processing,
                'nb_send_success' => $nb_send_success,
                'nb_send_failed' => $nb_send_failed,
                'nb_send_processed' => $nb_send_processed,
                'sendingstart_at' => Carbon::now(),
            ]);
            $this->update([
                'smsresult_id' => $new_sendresult->id,
            ]);
        } else {
            $old_sendresult =  $this->smsresult; //Smsresult::where('id', $this->smsresult_id)->first();
            $data_array = [
                'nb_to_send' => ($old_sendresult->nb_to_send + $nb_to_send),
                'nb_send_processing' => ($old_sendresult->nb_send_processing + $nb_send_processing),
                'nb_send_success' => ($old_sendresult->nb_send_success + $nb_send_success),
                'nb_send_failed' => ($old_sendresult->nb_send_failed + $nb_send_failed),
                'nb_send_processed' => ($old_sendresult->nb_send_processed + $nb_send_processed),
            ];
            if ($data_array['nb_to_send'] > 0 && ($data_array['nb_to_send'] == $data_array['nb_send_processed'])) {
                $data_array['sendingend_at'] = Carbon::now();
            }
            $this->smsresult->update($data_array);//$old_sendresult->update($data_array);
        }

        $this->setStatus(true);

        // si l'appellant est un planning, on met à jour la partie envoie de son smsresult de sa campagne parente
        if (isset($this->campaign)) {
            $this->campaign->addSendResult($nb_to_send, $nb_send_processing, $nb_send_success, $nb_send_failed, $nb_send_processed);
        }
    }
}
