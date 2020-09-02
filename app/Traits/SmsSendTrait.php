<?php


namespace App\Traits;


use App\Traits\SMS\protocol\SmppClient;
use App\Traits\SMS\protocol\GsmEncoder;
use App\Traits\SMS\protocol\SmppAddress;
use App\Traits\SMS\protocol\SMPP;

use App\Traits\SMS\CustomEncoder;
use App\Traits\SMS\transport\TSocket;
use Illuminate\Support\Carbon;
use Exception;

trait SmsSendTrait
{
    use ReportableTrait;
    public function sendSms() {
        $receiver = $this->receiver;
        $planning = $this->planning;
        $campaign = $planning->campaign;

        // Start
        $this->sendingstart_at = Carbon::now();

        $from_rqst = $campaign->expediteur;
        $to_rqst=isset($receiver->mobile)?(string)htmlentities($receiver->mobile,ENT_QUOTES,'utf-8'):'';
        $msg_rqst=isset($this->message)?utf8_encode(CustomEncoder::encode_accented_chars($this->message)):'';

        $msg = $msg_rqst;

        $indicatif = "241";
        $mobile_local = substr($to_rqst, -8);
        $mobile_inter = $indicatif.$mobile_local;

        $this->send_processing = true;
        $this->save();

        $report_msg = "";
        //$send_ok = $this->rawSend($from_rqst,$msg,$mobile_inter,$report_msg);
        $send_ok = $this->rawSendTest($from_rqst,$msg,$mobile_inter,$report_msg);

        $this->send_processing = false;
        $this->save();

        if ($send_ok) {
            $this->send_success = true;
            $this->addToReport(0,"Succès Envoie");
        } else {
            $this->send_success = false;
            $this->addToReport(0, $report_msg);
        }

        $this->send_processed = true;
        $this->sendingend_at = Carbon::now();
        $this->save();
    }

    private function rawSend($from_rqst,$msg,$mobile_inter,&$report_msg) {
        $send_ok = false;
        try {
            // Construct transport and client, customize settings
            $transport = new TSocket(config('app.SMPP_HOST'),2775,false,[$this, 'printDebug']); // hostname/ip (ie. localhost) and port (ie. 2775)
            $transport->setRecvTimeout(10000);
            $transport->setSendTimeout(10000);
            $smpp = new SmppClient($transport,[$this, 'printDebug']);

            // Activate debug of server interaction
            $smpp->debug = true; 		// binary hex-output
            $transport->setDebug(true);	// also get TSocket debug

            // Open the connection
            $transport->open();
            $smpp->bindTransmitter(config('app.SMSC_SYSTEMID'),config('app.SMSC_PASSWORD'));

            // Optional: If you get errors during sendSMS, try this. Needed for ie. opensmpp.logica.com based servers.
            SmppClient::$sms_null_terminate_octetstrings = false;

            // Optional: If your provider supports it, you can let them do CSMS (concatenated SMS)
            //SmppClient::$sms_use_msg_payload_for_csms = true;

            // Prepare message
            $message = 'Hello world';
            $encodedMessage = GsmEncoder::utf8_to_gsm0338($msg);
            $from = new SmppAddress(GsmEncoder::utf8_to_gsm0338($from_rqst),SMPP::TON_ALPHANUMERIC);
            $to = new SmppAddress($mobile_inter,SMPP::TON_INTERNATIONAL,SMPP::NPI_E164);

            // Send
            $smpp->sendSMS($from,$to,$encodedMessage);

            /*$this->stat_failed = false;
            $this->stat_success = true;*/

            // Close connection
            $smpp->close();

        } catch (Exception $e) {
            // Try to unbind
            $this->stat_failed = true;
            try {
                $smpp->close();
                $report_msg = $e->getMessage();
                //$this->save();
            } catch (Exception $ue) {
                // if that fails just close the transport
                $this->printDebug("Failed to unbind; '".$ue->getMessage()."' closing transport");
                $report_msg = $ue->getMessage();
                if ($transport->isOpen()) $transport->close();
                //$this->save();
            }
            //$this->save();
            // Rethrow exception, now we are unbound or transport is closed
            //CustomLog::logEnd($logfile, $starttime_gbl, $e->getMessage());
            throw $e;
        }

        return $send_ok;
    }

    private function rawSendTest($from_rqst,$msg,$mobile_inter,&$report_msg) {
        $results = [
            [true,"Succès Envoie"],
            [false, "Time Out"],
            [true, "Succès Envoie"],
            [false, "SMSC server unreachable"],
        ];
        sleep(0.5);
        $rst = rand(0, 3);
        $report_msg = $results[$rst][1];
        return $results[$rst][0];
    }
}