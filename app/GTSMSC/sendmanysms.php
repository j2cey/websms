<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

// http://10.32.15.237/GTSMSC/send_smppclient.php?From=GTsmsc&To=24105300354&Msg=hello_test_one
// http://localhost:8080/GTSMSC/sendmanysms.php?From=GTsmsc&To=24105300354_05300355_05300089_05300120&Msg=hello_test_one


/*$from_rqst = trim($_GET['From']);
$to_rqst = trim($_GET['To']);
$msg_rqst = trim($_GET['Msg']);*/

$from_rqst=isset($_GET['From'])?(string)htmlentities($_GET['From'],ENT_QUOTES,'utf-8'):'';
$to_rqst=isset($_GET['To'])?(string)htmlentities($_GET['To'],ENT_QUOTES,'utf-8'):'';
$msg_rqst=isset($_GET['Msg'])?utf8_encode($_GET['Msg']):'';

$msg = $msg_rqst;

/* decompte SUBSCRIBER */
$sepcount = substr_count($to_rqst, '_');

$receip_list = explode("_", $to_rqst);
$sending_list = [];

foreach ($receip_list as $key => $receip){
    // Execution de l'envoi
	dd("","Envoi " . $key);
	dd($from_rqst,"From");
	dd($msg_rqst,"Msg");
	dd($receip,"To");
	$urlresult = file_get_contents("http://localhost:8080/GTSMSC/send_smppclient.php?From=$from_rqst&To=$receip&Msg=$msg_rqst");
	dd($urlresult,"Send Result");
}

function dd($v,$t){
	echo '<pre>';
	echo '<h1>' . $t. '</h1>';
	var_dump($v);
	echo '</pre>';
}


