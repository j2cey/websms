<?php

namespace App\GTSMSC;

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

$GLOBALS['SMPP_ROOT'] = dirname(__FILE__); // assumes this file is in the root
require_once $GLOBALS['SMPP_ROOT'].'/protocol/smppclient.class.php';
require_once $GLOBALS['SMPP_ROOT'].'/protocol/gsmencoder.class.php';
require_once $GLOBALS['SMPP_ROOT'].'/transport/tsocket.class.php';

require_once $GLOBALS['SMPP_ROOT'].'/class/customencoder.class.php';
require_once $GLOBALS['SMPP_ROOT'].'/class/customlog.class.php';

trait SmssendableTrait
{
    public function sendSms($from_rqst,$to_rqst,$msg_rqst) {

        // Simple debug callback
        function printDebug($str) {
            echo date('Ymd H:i:s ').$str."\r\n";
        }

        //$receiver = SmscampaignReceiver::where('id',$this->smscampaign_receiver_id)->first();
        //$planning = SmscampaignPlanning::where('id',$this->smscampaign_planning_id)->first();
        //$campaign = Smscampaign::where('id',$planning->smscampaign_id)->first();

        // Start
        //$this->sendingstart_at = Carbon::now();

        //$from_rqst = $campaign->expediteur;
        $to_rqst=isset($to_rqst)?(string)htmlentities($to_rqst,ENT_QUOTES,'utf-8'):'';
        $msg_rqst=isset($msg_rqst)?utf8_encode(CustomEncoder::encode_accented_chars($msg_rqst)):'';

        $msg = $msg_rqst;

        $indicatif = "241";
        $mobile_local = substr($to_rqst, -8);
        $mobile_inter = $indicatif.$mobile_local;

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

            $this->stat_failed = false;
            $this->stat_success = true;

            // Close connection
            $smpp->close();

        } catch (\Exception $e) {
            // Try to unbind
            $this->stat_failed = true;
            try {
                $smpp->close();
                //$this->stat_failed_msg = $e->getMessage();
                //$this->save();
            } catch (\Exception $ue) {
                // if that fails just close the transport
                $this->printDebug("Failed to unbind; '".$ue->getMessage()."' closing transport");
                //$this->stat_failed_msg = $ue->getMessage();
                if ($transport->isOpen()) $transport->close();
                //$this->save();
            }
            $this->save();
            // Rethrow exception, now we are unbound or transport is closed
            //CustomLog::logEnd($logfile, $starttime_gbl, $e->getMessage());
            throw $e;
        }

        // Start
        //$this->sendingend_at = Carbon::now();
        //$this->save();
    }
}
