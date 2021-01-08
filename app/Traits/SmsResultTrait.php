<?php

namespace App\Traits;

use App\Smsresult;
use Illuminate\Support\Carbon;

trait SmsResultTrait
{
    /**
     * Get the number of elements to import.
     * @return int
     */
    abstract public function getNbToImport(): int;
    /**
     * Get the number of importation processing.
     * @return int
     */
    abstract public function getNbImportProcessing(): int;
    /**
     * Get the number of importation successed.
     * @return int
     */
    abstract public function getNbImportSuccess(): int;
    /**
     * Get the number of importation failed.
     * @return int
     */
    abstract public function getNbImportFailed(): int;
    /**
     * Get the number of importation processed.
     * @return int
     */
    abstract public function getNbImportProcessed(): int;


    /**
     * Get the number of elements to send.
     * @return int
     */
    abstract public function getNbToSend(): int;
    /**
     * Get the number of send processing.
     * @return int
     */
    abstract public function getNbSendProcessing(): int;
    /**
     * Get the number of send successed.
     * @return int
     */
    abstract public function getNbSendSuccess(): int;
    /**
     * Get the number of send failed.
     * @return int
     */
    abstract public function getNbSendFailed(): int;
    /**
     * Get the number of send processed.
     * @return int
     */
    abstract public function getNbSendProcessed(): int;


    public function setImportResult() {
        if (is_null($this->smsresult_id)) {
            $this->createNewResult(true);
        }

        $this->updateImportResult();

        $this->setStatus(true);

        // Import rate
        if ($this->smsresult && ($this->smsresult->nb_to_import > 0)) {
            $this->smsresult->update([
                'import_rate' => round((($this->smsresult->nb_import_processed) / $this->smsresult->nb_to_import) * 100, 0)
            ]);
        } else {
            $this->smsresult->update([ 'import_rate' => 0 ]);
        }

        // si l'appellant est un planning, on met à jour la partie importation de son smsresult de sa campagne parente
        if (isset($this->campaign)) {
            $this->campaign->setImportResult();
        }
    }

    public function setSendResult() {
        if (is_null($this->smsresult_id)) {
            $this->createNewResult(false);
        }

        $this->updateSendResult();

        $this->setStatus(true);

        // Send Rate
        if ($this->smsresult && ($this->smsresult->nb_to_send > 0)) {
            $this->smsresult->update([
                'send_rate' => round(($this->smsresult->nb_send_processed / $this->smsresult->nb_to_send) * 100, 0)
            ]);
        } else {
            $this->smsresult->update([ 'send_rate' => 0 ]);
        }

        // si l'appellant est un planning, on met à jour la partie envoie de son smsresult de sa campagne parente
        if (isset($this->campaign)) {
            $this->campaign->setSendResult();
        }
    }

    private function createNewResult($importing = false) {
        $default_values = [
            'nb_to_import' => 0,
            'nb_import_processing' => 0,
            'nb_import_success' => 0,
            'nb_import_failed' => 0,
            'nb_import_processed' => 0,

            'nb_to_send' => 0,
            'nb_send_processing' => 0,
            'nb_send_success' => 0,
            'nb_send_failed' => 0,
            'nb_send_processed' => 0,
        ];
        if ($importing) {
            $default_values['importstart_at'] = Carbon::now();
        } else {
            $default_values['sendingstart_at'] = Carbon::now();
        }
        $new_result = Smsresult::create($default_values);
        $this->update([
            'smsresult_id' => $new_result->id,
        ]);
    }

    private function updateImportResult() {
        $data_array = [
            'nb_to_import' => $this->getNbToImport(),
            'nb_import_processing' => $this->getNbImportProcessing(),
            'nb_import_success' => $this->getNbImportSuccess(),
            'nb_import_failed' => $this->getNbImportFailed(),
            'nb_import_processed' => $this->getNbImportProcessed(),
        ];

        if ($data_array['nb_to_import'] > 0 && ($data_array['nb_to_import']== $data_array['nb_import_processed'])) {
            $data_array['importend_at'] = Carbon::now();
        }
        $upd_rslt = $this->smsresult->update($data_array);
        //dump($data_array);
        //dump($upd_rslt);
    }
    private function updateSendResult() {
        $data_array = [
            'nb_to_send' => $this->getNbToSend(),
            'nb_send_processing' => $this->getNbSendProcessing(),
            'nb_send_success' => $this->getNbSendSuccess(),
            'nb_send_failed' => $this->getNbSendFailed(),
            'nb_send_processed' => $this->getNbSendProcessed(),
        ];

        // set sending start if not yet
        if (! $this->smsresult->sendingstart_at) {
            $data_array['sendingstart_at'] = Carbon::now();
        }

        if ($data_array['nb_to_send'] > 0 && ($data_array['nb_to_send'] == $data_array['nb_send_processed'])) {
            $data_array['sendingend_at'] = Carbon::now();
        }
        $this->smsresult->update($data_array);
    }







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

        // Import rate
        if ($this->smsresult && ($this->smsresult->nb_to_import > 0)) {
            $this->smsresult->update([
                'import_rate' => round((($this->smsresult->nb_import_processed) / $this->smsresult->nb_to_import) * 100, 0)
            ]);
        } else {
            $this->smsresult->update([ 'import_rate' => 0 ]);
        }

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

        // Send Rate
        if ($this->smsresult && ($this->smsresult->nb_to_send > 0)) {
            $this->smsresult->update([
                'send_rate' => round(($this->smsresult->nb_send_processed / $this->smsresult->nb_to_send) * 100, 0)
            ]);
        } else {
            $this->smsresult->update([ 'send_rate' => 0 ]);
        }

        // si l'appellant est un planning, on met à jour la partie envoie de son smsresult de sa campagne parente
        if (isset($this->campaign)) {
            $this->campaign->addSendResult($nb_to_send, $nb_send_processing, $nb_send_success, $nb_send_failed, $nb_send_processed);
        }
    }
}
