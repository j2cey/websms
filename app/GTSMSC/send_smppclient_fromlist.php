<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

// http://10.32.15.237/GTSMSC/send_smppclient.php?From=GTsmsc&To=24105300354&Msg=hello_test_one
// http://localhost:8080/GTSMSC/send_smppclient.php?From=GTsmsc&To=24105300354&Msg=hello_test_one

$GLOBALS['SMPP_ROOT'] = dirname(__FILE__); // assumes this file is in the root
require_once $GLOBALS['SMPP_ROOT'].'/protocol/smppclient.class.php';
require_once $GLOBALS['SMPP_ROOT'].'/protocol/gsmencoder.class.php';
require_once $GLOBALS['SMPP_ROOT'].'/transport/tsocket.class.php';

require_once $GLOBALS['SMPP_ROOT'].'/class/customencoder.class.php';
require_once $GLOBALS['SMPP_ROOT'].'/class/customlog.class.php';

/*$from_rqst = trim($_GET['From']);
$to_rqst = trim($_GET['To']);
$msg_rqst = trim($_GET['Msg']);*/

/*
$from_rqst=isset($_GET['From'])?(string)htmlentities($_GET['From'],ENT_QUOTES,'utf-8'):'';
$to_rqst=isset($_GET['To'])?(string)htmlentities($_GET['To'],ENT_QUOTES,'utf-8'):'';
$msg_rqst=isset($_GET['Msg'])?utf8_encode($_GET['Msg']):'';
$msg_smpl=$_GET['Msg'];
*/

$settings = parse_ini_file("config/settings.ini.php");
$nb_done = 0;


// ADD 20-08-2020

// lecture du fichier liste
$listfile = "nums_sms_list.txt";
$listtext = file_get_contents("C:\\wamp64\\www\\GTSMSC\\" . $listfile);
$listtext_arr = explode("\n", $listtext);

// Simple debug callback
function printDebug($str) {
    echo date('Ymd H:i:s ').$str."\r\n";
}

if(empty($listtext_arr)){
	dd_sendsms($listtext_arr,"File EMPTY !!!");
} else {
	
	$logfile = "C:\\wamp64\\www\\GTSMSC\\logExec_MobiCash.log";
	$starttime_gbl = new DateTime(); //microtime(true);
	CustomLog::logStart($logfile , "Global Start");
	
	foreach($listtext_arr as $tmp_line){
		$num_sms = get_num_and_sms($tmp_line);
		
		if ($num_sms == "") {
			// empty
			dd_sendsms($num_sms,"Num Sms EMPTY !!!");
		} else {
			
			if ($nb_done >= 15) {
				// sleep for 20 seconds
				sleep(20);
				$nb_done = 0;
			}
			
			$from_rqst="MobiCash";
			$to_rqst=isset($num_sms[0])?(string)htmlentities($num_sms[0],ENT_QUOTES,'utf-8'):'';
			
			$msg_rqst=isset($num_sms[1])?utf8_encode(CustomEncoder::encode_accented_chars($num_sms[1])):'';
			//dd_sendsms($msg_rqst,"SMS UTF8");
			//$msg_rqst=html_entity_decode(htmlentities($msg_rqst, ENT_QUOTES, 'UTF-8'), ENT_QUOTES , 'ISO-8859-1');
			//$msg = mb_convert_encoding($msg_rqst,'UTF-8','HTML-ENTITIES');
			//dd_sendsms($msg,"HTML-ENTITIES");
			
			//$encodedMessage = mb_convert_encoding($msg_rqst, "UCS-2", "utf8");

			$msg = $msg_rqst;
			
			$starttime_sngl = new DateTime(); //microtime(true);
			CustomLog::logStart($logfile , "Start Envoi vers " . $to_rqst);
			// sleep for 10 seconds
			//sleep(10);
			
			/* decompte SUBSCRIBER

			/* SUBSCRIBER */
			$indicatif = "241";
			$mobile_local = substr($to_rqst, -8);
			$mobile_inter = $indicatif.$mobile_local;
			
			try {
			    // Construct transport and client, customize settings
			    $transport = new TSocket($settings['SMPP_HOST'],2775,false,'printDebug'); // hostname/ip (ie. localhost) and port (ie. 2775)
			    $transport->setRecvTimeout(10000);
			    $transport->setSendTimeout(10000);
			    $smpp = new SmppClient($transport,'printDebug');

			    // Activate debug of server interaction
			    $smpp->debug = true; 		// binary hex-output
			    $transport->setDebug(true);	// also get TSocket debug

			    // Open the connection
			    $transport->open();
			    $smpp->bindTransmitter($settings['SMSC_SYSTEMID'],$settings['SMSC_PASSWORD']);

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

			    // Close connection
			    $smpp->close();
			    
			    $nb_done = $nb_done + 1;
			    CustomLog::logEnd($logfile, $starttime_sngl, "End Envoi vers " . $to_rqst);

			} catch (Exception $e) {
			    // Try to unbind
			    try {
			        $smpp->close();
			    } catch (Exception $ue) {
			        // if that fails just close the transport
			        printDebug("Failed to unbind; '".$ue->getMessage()."' closing transport");
			        if ($transport->isOpen()) $transport->close();
			    }

			    // Rethrow exception, now we are unbound or transport is closed
			    CustomLog::logEnd($logfile, $starttime_gbl, $e->getMessage());
			    throw $e;
			}
		}
	}
	CustomLog::logEnd($logfile, $starttime_gbl, "Global End");
}

function dd_sendsms($v,$t){
	echo '<pre>';
	echo '<h1>' . $t. '</h1>';
	var_dump($v);
	echo '</pre>';
}


function get_num_and_sms($linetext){
	if ($linetext == "" || $linetext == " ") {
		return "";
	} else {
		return explode("|", $linetext);
	}
}