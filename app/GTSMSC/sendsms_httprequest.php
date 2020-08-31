<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

// http://10.32.15.237/GTSMSC/sendsms_httprequest.php?Mobile=24105300354&Request=hello_test_one

require_once ('class/Sms.php');

$Mobile = trim($_GET['Mobile']);
$Request = trim($_GET['Request']); // *131*0#

$msg = $Request;

/* PARAMS */
$settings = parse_ini_file("config/settings.ini.php");

/* SMS */
$sms = new Sms($settings);
$default_sender = "GTSmsc";

/* SUBSCRIBER */
$indicatif = "241";
$mobile_local = substr($Mobile, -8);
$mobile_inter = $indicatif.$mobile_local;

$send_rslt = $sms->send($msg,$default_sender,$mobile_inter,null,1);

d([$sms,$msg,$default_sender,$mobile_inter,$send_rslt],"SMS Object and Result:");

function d($v,$t){
    echo '<pre>';
    echo '<h1>' . $t. '</h1>';
    var_dump($v);
    echo '</pre>';
}