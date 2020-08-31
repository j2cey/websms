<?php
/**
 * Created by PhpStorm.
 * User: jngom
 * Date: 19/10/2016
 * Time: 14:37
 */

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

// http://10.32.15.237/GTSMSC/send_smppclient.php?From=GTsmsc&To=24105300354&Msg=hello_test_one

$GLOBALS['SMPP_ROOT'] = dirname(__FILE__); // assumes this file is in the root
require_once $GLOBALS['SMPP_ROOT'].'/protocol/smppclient.class.php';
require_once $GLOBALS['SMPP_ROOT'].'/protocol/gsmencoder.class.php';
require_once $GLOBALS['SMPP_ROOT'].'/transport/tsocket.class.php';

$settings = parse_ini_file("config/settings.ini.php");

// Simple debug callback
function printDebug($str) {
    echo date('Ymd H:i:s ').$str."\r\n";
}

try {
    echo "-------------------------------DEBUT EXECUTION BATCH D'ENREGISTREMENT DES SMS - " . date('Y-m-d H:i:s') . "------------------------------------\n";

////////////////////////////////////////////////////////////////////////
//MECANISME D'EXCLUSION POUR LE SCRIPT
//But : empecher qu'il y ait plusieurs instances d'execution de ce script en meme temps
    echo $nom_script = $_SERVER['PHP_SELF'];
    $heure_actuelle = date('H:i');
//Identifier tous les scripts qui sont actuellement en cours d'execution
//et qui ont é lancéàne heure/minute diffénte de la minute actuelle
    echo $cmd = "ps -ef|grep -i $nom_script|grep -v $heure_actuelle";

    /*if ($instances_execution_script>=1)
        exit("--------------------BATCH D'ENREGISTREMENT DES SMS DEJA EN COURS D'EXECUTION - ABANDON - ".date('Y-m-d H:i:s')."-------------------------\n");*/
////////////////////////////////////////////////////////////////////////

    echo "debut </br>";
//bind the receiver
    $transport = new TSocket($settings['SMPP_HOST'],2775,false,'printDebug'); // hostname/ip (ie. localhost) and port (ie. 2775)
    $transport->setRecvTimeout(10000);
    //$transport->setSendTimeout(10000);
    $smpp = new SmppClient($transport, 'printDebug');
    $smpp->debug = true;        // binary hex-output
    $transport->setDebug(true);    // also get TSocket debug
    // Open the connection
    $transport->open();
    $smpp->bindReceiver($settings['SMSC_SYSTEMID'],$settings['SMSC_PASSWORD']);
    $nombre = 0;
// Read SMS and output
    echo "RECEPTIONS<br>\n";
    do {
        //Lecture des sms entrants

        $sms = $smpp->readSMS();
        //var_dump($sms);
//Véf des donné sms
        if ($sms && !empty($sms->source->value) && !empty($sms->destination->value) && !empty($sms->message)) {
            $le_jour = date("Y-m-d");
            $l_heure = date("H:i:s");
            //lecture des valeurs sms
            echo $sender = $sms->source->value;       //echo ",0,";
            $receiver = $sms->destination->value;
            $message = $sms->message;
            $message = str_replace(" ", "", trim(strtoupper($message)));
            $message = str_replace("'", "", $message);
            $message = str_replace(",", "", $message);
            $message = str_replace("*", "", $message);
            $message = str_replace("#", "", $message);
            $message = str_replace("^D", "", $message);
            $message = str_replace("^E", "", $message);
            $message = str_replace("$", "", $message);
            echo "R," . $le_jour . "," . $l_heure . "," . $sender . "," . $receiver . "," . $message . "\n";

            //////////Log dans un fichier
            $info = "From : $sender\n To : $receiver\n Message : $message";
            $nombre = $nombre + 1;
        }
//Tant qu'il y a des message en attente au niveau de la SMSC
    } while ($sms);
    //////////Log dans un fichier
    $info = "---------------$nombre SMS lus.---------------\n";
    //////////Fin log
//Fermeture de la connexion smpp
    $smpp->close();
    unset($smpp);
    echo "fin </br>";

    echo "fin </br>";

    echo "-----------------------------FIN D'EXECUTION DU BATCH D'ENREGISTREMENT DES SMS - " . date('Y-m-d H:i:s') . "------------------------------------\n";

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