<?php
/*
$json_string = "[{"date":"2016-03-18 13:46:03","name":"enregistrement","result":true},{"date":"2016-03-18 13:46:03","name":"Sp\u00e9cification du service","result":true},{"date":"2016-03-18 13:46:33","name":"Consultation compte","result":"{\"responseCode\":-2,\"MainAccount\":-1,\"DedicatedAccounts\":[],\"ServiceClass\":[]}"}]";
*/

$t = '[{"date":"2016-03-18 14:03:53","name":"enregistrement","result":true},{"date":"2016-03-18 14:03:53","name":"Sp\u00e9cification du service","result":true},{"date":"2016-03-18 14:04:23","name":"Consultation compte","result":-2}]';

$t2 = '[{"date":{"date":"2016-03-18 13:16:51","timezone_type":3,"timezone":"UTC"},"name":"enregistrement","result":true}]';

$t3 = '[{"date":"2016-03-18 14:14:59","name":"enregistrement","result":true},{"date":"2016-03-18 14:14:59","name":"Sp\u00e9cification du service","result":true},{"date":"2016-03-18 14:15:29","name":"Consultation compte","result":0},{"date":"2016-03-18 14:16:00","name":"D\u00e9bit compte principal","result":null}]';

//d(json_decode($t2),"T2");


$urlparam = urlencode($mobile_inter);
$construct_url="http://10.32.10.230/rfialertesrequests/addsending/{$param}";
// http://10.32.10.230/rfialertesrequests/addsending/24105300354
//$urlresult = file_get_contents($construct_url);
$urlresult = file_get_contents("http://10.32.10.230/rfialertesrequests/addsending/24105300354");
d($urlresult,"URL Result");

function d($v,$t){
	echo '<pre>';
	echo '<h1>' . $t. '</h1>';
	var_dump($v);
	echo '</pre>';
}