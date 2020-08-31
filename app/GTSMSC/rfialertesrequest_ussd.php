<?php

/*
$MsgNotification=urlencode($MsgNotification);
$url = "http://localhost/MoovInter/apps/send_sms/send_sms.php?Mobile=$Mobile&De=$ExpdSMS&MsgRx=$MsgNotification";
@file($url);
*/

/*
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
*/


$mobile = $argv[1];;
$USSDServiceCode = $argv[2];;
$USSDRequest = $argv[3];;


require_once ('functions/rfialertes_functions.php');
require_once ('functions/ucip_functions.php');
require_once ('functions/vas_traits.php');
require_once ('class/Sms.php');
require_once ("class/Db.php");


/*
$Mobile = trim($_GET['Mobile']);
$message = trim($_GET['MsgRx']);
$from = trim($_GET['De']);
*/

/*
$mobile = '24105019849';
$USSDServiceCode = "131";
$USSDRequest = '*0#';
*/

$diez = '#';
$asterisk = '*';

$USSDRequest = $asterisk . '' . $USSDServiceCode . '' . $USSDRequest ;


//$param = urlencode($mobile."|ussd|".$USSDRequest);

//d($param,"Param");

//$construct_url="http://10.32.10.230/rfialertesrequests/add/{$param}";

//file_get_contents($construct_url);



/* PARAMS */
$settings = parse_ini_file("config/settings.ini.php");
$vasparams = array();//getArrayConfig("config/vas.php");

include("config/vas_array.php");

$vasparams = $vasparams_array;//( is_file("config/vas.php") ? include "config/vas.php" : false );
$uciprespcodes = $ucip_respcode_array;

/* SMS */
$sms = new Sms($settings);
$default_sender = "MoovRFI";


/* SUBSCRIBER */
$indicatif = "241";
$mobile_local = substr($mobile, -8);
$mobile_inter = $indicatif.$mobile_local;

/* REQUEST */

$request = array(
	'applicant'=>$mobile,
	'type'=>"ussd",
	'code'=>$USSDRequest,
	'request'=>$USSDRequest,
	'service'=>'non defini',
	'cost'=>0,
	'trace'=>"",
	'status'=>0,
	'result'=>"0",
	'start_at'=>date('Y-m-d H:i:s', time()),
	'end_at'=>null,
	'created_at'=>date('Y-m-d H:i:s', time()),
	'updated_at'=>null
	);
$request_live = array('applicant'=>$mobile);




### 1. Enregistrement de la requete
$step_rslt = enregistrerRequete($settings,$request);

### 2. Determine service
$vasservice = getService($vasparams,"rfialertes","ussd",$USSDRequest);
$request['trace'] = addTrace('Spécification du service',$vasservice['resp_string'],$request['trace']);

//$sms->send("Mobile ".$mobile.", USSDServiceCode ".$USSDServiceCode.", USSDRequest ".$USSDRequest.", Param vas count ".count($vasparams).", Resp code ".$vasservice['resp_code'],"TestAdmin","24105300354");

if ( !($vasservice['resp_code'] == 1) ){
	$request['status'] = -1;
	$request['result'] = "Erreur requete";
	$sms->send("Cher abonne, votre requete n est pas correcte. Veuillez recommencer SVP.",$default_sender,$mobile_inter,null,1);
}else{
	$request['service'] = $vasservice['service']['name'];
	
	### 3. Get Account Infos
	$ucip_rst = getAccountInformations($settings,$mobile_local);
	$account_infos = assignAccountInfos($ucip_rst[3]);
	
	$request['trace'] = addTrace('Consultation compte',['resp code'=>( (isset($ucip_rst[0]) ? $ucip_rst[0] : 'X' ) ),'fault code'=>( (isset($ucip_rst[1]) ? $ucip_rst[1] : 'X' ) ),'fault string'=>( (isset($ucip_rst[2]) ? $ucip_rst[2] : 'X' ) ),'main account'=>$account_infos['MainAccount']],$request['trace']);
	
	if ( !($account_infos['responseCode'] == 0) ){
		$request['status'] = -2;
		$request['result'] = "Erreur consultation compte".( isset($uciprespcodes[$ucip_rst[0]]) ? " - ".$uciprespcodes[$ucip_rst[0]] : "" );
		$sms->send($vasservice['sms']['erreur_avant_solde'],$vasservice['sms']['sender'],$mobile_inter,null,1);
	}else{

		### 4. Debit Main Account
		if ($vasservice['service']['cost'] > 0){
			if ($account_infos['MainAccount'] <= $vasservice['service']['cost']){
				$request['status'] = -3;
				$rslt_adjust = -1;
				$request['result'] = "Erreur Solde insuffisant";
				$sms->send($vasservice['sms']['solde_insuffisant'],$vasservice['sms']['sender'],$mobile_inter,array("{0}"=>$vasservice['service']['cost']),1);
			}else{
				$ucip_rst = adjustMainAccount($settings,$mobile_local,$vasservice['service']['cost'],$vasservice['service']['transaction_id']);
				$rslt_adjust = assignAdjustAccountResult($ucip_rst[3]);
				$request['trace'] = addTrace('Débit compte principal',['resp code'=>( (isset($ucip_rst[0]) ? $ucip_rst[0] : 'X' ) ),'fault code'=>( (isset($ucip_rst[1]) ? $ucip_rst[1] : 'X' ) ),'fault string'=>( (isset($ucip_rst[2]) ? $ucip_rst[2] : 'X' ) ),'result adjust'=>$rslt_adjust],$request['trace']);
			}
		}else{
			$rslt_adjust = 0;
		}
		
		if ( (!($rslt_adjust == 0)) && ($request['status'] <= 0) ){

			if ($request['status'] == 0){
				$request['status'] = -4;
				$request['result'] = "Echec Debit compte principal".( isset($uciprespcodes[$rslt_adjust]) ? " - ".$uciprespcodes[$rslt_adjust] : "" );
				$sms->send($vasservice['sms']['erreur_avant_solde'],$vasservice['sms']['sender'],$mobile_inter,null,1);

				// Consultation compte après opération
				$ucip_rst = getAccountInformations($settings,$mobile_local);
				$account_infos = assignAccountInfos($ucip_rst[3]);

				$request['trace'] = addTrace('Consultation compte (après échec adjust main account)',['resp code'=>( (isset($ucip_rst[0]) ? $ucip_rst[0] : 'X' ) ),'fault code'=>( (isset($ucip_rst[1]) ? $ucip_rst[1] : 'X' ) ),'fault string'=>( (isset($ucip_rst[2]) ? $ucip_rst[2] : 'X' ) ),'main account'=>$account_infos['MainAccount']],$request['trace']);
			}else{
				// Solde insuffisant
			}

		}else{
			$request['cost'] = $vasservice['service']['cost'];
			
			if ($vasservice['service']['name'] === 'annulation'){
				$step_rslt = deleteLives($settings,$request);
				
				if ($step_rslt > 0){
					$request['status'] = 1;
					$request['result'] = "Succes annulation";
					
					$sms_ok_msg = $vasservice['sms']['annulation_reussie'];
				}elseif($step_rslt == 0){
					$request['status'] = 1;
					$request['result'] = "Abonne non inscrit";
					
					$sms_ok_msg = $vasservice['sms']['annulation_nulle'];
				}else{
					$request['status'] = -5;
					$request['result'] = "Erreur annulation";
					$sms_ok_msg = "";
					$sms->send($vasservice['sms']['erreur_avant_solde'],$vasservice['sms']['sender'],$mobile_inter,null,1);
				}
			}else{
				$request['status'] = 1;
				$request['result'] = "Succes inscription";
				
				### 5. Mise en LIVE
				$live_rslt = insertLives($settings,$request,$vasservice);
				
				$sms_ok_msg = strtr( $vasservice['sms']['inscription_reussie'], array('{0}'=>$vasservice['service']['name'],'{1}'=>$live_rslt[2]) );
				
				### 6. Insert SMS Sending
				$urlresult = file_get_contents("http://10.32.10.230/rfialertesrequests/addsending/$mobile_inter");
				if($urlresult == 1){
					$request['result'] = $request['result'].", Succes insertion liste envoi";
				}else{
					$request['result'] = $request['result'].", Echec insertion liste envoi";
				}
			}
			
			if ( ($request['status'] === 1) && (!(empty($sms_ok_msg)))){
				
				### 7. Notify Succes
				$sms->send($sms_ok_msg,$vasservice['sms']['sender'],$mobile_inter,null,1);
			}else{
			}
		}
	}
}

### 8. Cloture requête
$request['end_at'] = date('Y-m-d H:i:s', time());
$db_rslt = mettreAjourRequete($settings,$request);






/* LOCAL FUNCs */

function getArrayConfig($in)
{
  if(is_file($in)){ 
       return include $in;
  }
  return false;
}

function d($v,$t){
	echo '<pre>';
	echo '<h1>' . $t. '</h1>';
	var_dump($v);
	echo '</pre>';
}