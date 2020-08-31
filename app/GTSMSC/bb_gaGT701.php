 <?php

/**
 * @author fabrice Ondo
 * @copyright 2016
 */

//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
///////////////                 P R I N C I P A L              /////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////

//require_once "class_smpp.php";//SMPP protocol
require_once "configurations.php";//Paramèes
require_once "fonctions.php";//Fonctions
require_once "/srv/www/htdocs/BB/bbtest/protocol/smppclient.class.php";//SMPP protocol
require_once "/srv/www/htdocs/BB/bbtest/protocol/gsmencoder.class.php";
require_once "/srv/www/htdocs/BB/bbtest/transport/tsocket.class.php";
  
/*  function printDebug($str) {
    echo date('Ymd H:i:s ').$str."\r\n";
}*/
    //////////Log de controle des executions des batchs
    $fichier_log = $batch_recuperation_SMS;
    $info = "Execution batch enregistrement SMS";
    log_fichier($fichier_log, $info);
    //////////Fin log
echo "-------------------------------DEBUT EXECUTION BATCH D'ENREGISTREMENT DES SMS - ".date('Y-m-d H:i:s')."------------------------------------\n";

////////////////////////////////////////////////////////////////////////
//MECANISME D'EXCLUSION POUR LE SCRIPT
//But : empecher qu'il y ait plusieurs instances d'execution de ce script en meme temps
echo $nom_script = $_SERVER['PHP_SELF'];
$heure_actuelle = date('H:i');
//Identifier tous les scripts qui sont actuellement en cours d'execution
//et qui ont é lancéàne heure/minute diffénte de la minute actuelle
echo $cmd="ps -ef|grep -i $nom_script|grep -v $heure_actuelle";
exec($cmd,$resp);
$instances_execution_script = count($resp);
print_r($resp);
/*if ($instances_execution_script>=1)
    exit("--------------------BATCH D'ENREGISTREMENT DES SMS DEJA EN COURS D'EXECUTION - ABANDON - ".date('Y-m-d H:i:s')."-------------------------\n");*/
////////////////////////////////////////////////////////////////////////

echo "debut </br>";
//bind the receiver
    $transport = new TSocket($smpp_host,'2775',false,'printDebug'); // hostname/ip (ie. localhost) and port (ie. 2775)
    $transport->setRecvTimeout(10000);
    //$transport->setSendTimeout(10000);
    $smpp = new SmppClient($transport,'printDebug');
    $smpp->debug = true; 		// binary hex-output
    $transport->setDebug(true);	// also get TSocket debug
    // Open the connection
    $transport->open();
    $smpp->bindReceiver($smpp_systemid_jour,$smpp_pass_jour);
$nombre = 0;
// Read SMS and output   
        echo "RECEPTIONS<br>\n";
do      {
        //Lecture des sms entrants

        $sms = $smpp->readSMS();
         //var_dump($sms);        
//Véf des donné sms
        if($sms && !empty($sms->source->value) && !empty($sms->destination->value) && !empty($sms->message)){
                $le_jour = date("Y-m-d");
                $l_heure = date("H:i:s");
        //lecture des valeurs sms
                echo $sender=$sms->source->value;       //echo ",0,";
                $receiver=$sms->destination->value;
                $message=$sms->message;
                $message = str_replace(" ","",trim(strtoupper($message)));
                $message = str_replace("'","",$message);
                $message = str_replace(",","",$message);
                $message = str_replace("*","",$message);
                $message = str_replace("#","",$message);
                $message = str_replace("^D","",$message);
                $message = str_replace("^E","",$message);
                 $message = str_replace("$","",$message);
                echo "R,".$le_jour.",".$l_heure.",".$sender.",".$receiver.",".$message."\n";

            //////////Log dans un fichier
            $fichier_log = $GLOBALS['log_reception_SMS'];
            $info = "From : $sender\n To : $receiver\n Message : $message";
            log_fichier($fichier_log, $info);
            //////////Fin log
              store_incoming_sms($sender,$receiver,$message);
              send_message($receiver,$sender,$message_ack);
              $nombre = $nombre + 1;
        }
//Tant qu'il y a des message en attente au niveau de la SMSC
}       while($sms);
        //////////Log dans un fichier
        $fichier_log = $GLOBALS['log_reception_SMS'];
        $info = "---------------$nombre SMS lus.---------------\n";
        log_fichier($fichier_log, $info);
        //////////Fin log
//Fermeture de la connexion smpp
$smpp->close();
unset($smpp);
echo "fin </br>";

echo "fin </br>";

echo "-----------------------------FIN D'EXECUTION DU BATCH D'ENREGISTREMENT DES SMS - ".date('Y-m-d H:i:s')."------------------------------------\n";

 

?>
