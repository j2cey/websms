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

/*$from_rqst = trim($_GET['From']);
$to_rqst = trim($_GET['To']);
$msg_rqst = trim($_GET['Msg']);*/

/*
$from_rqst=isset($_GET['From'])?(string)htmlentities($_GET['From'],ENT_QUOTES,'utf-8'):'';
$to_rqst=isset($_GET['To'])?(string)htmlentities($_GET['To'],ENT_QUOTES,'utf-8'):'';
$msg_rqst=isset($_GET['Msg'])?utf8_encode($_GET['Msg']):'';
$msg_smpl=$_GET['Msg'];
*/

// ADD 20-08-2020
$from_rqst = trim($argv[1]);
$to_rqst = trim($argv[2]);
$msg_rqst = trim($argv[3]);

$from_rqst=isset($from_rqst)?(string)htmlentities($from_rqst,ENT_QUOTES,'utf-8'):'';
$to_rqst=isset($to_rqst)?(string)htmlentities($to_rqst,ENT_QUOTES,'utf-8'):'';
$msg_rqst=isset($msg_rqst)?utf8_encode($msg_rqst):'';


$msg = $msg_rqst;
dd_sendsms($msg_smpl,"MSG a ENVOYER**");
/* decompte SUBSCRIBER

/* SUBSCRIBER */
$indicatif = "241";
$mobile_local = substr($to_rqst, -8);
$mobile_inter = $indicatif.$mobile_local;

$settings = parse_ini_file("config/settings.ini.php");

// Simple debug callback
function printDebug($str) {
    echo date('Ymd H:i:s ').$str."\r\n";
}

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
    throw $e;
}

function dd_sendsms($v,$t){
	echo '<pre>';
	echo '<h1>' . $t. '</h1>';
	var_dump($v);
	echo '</pre>';
}