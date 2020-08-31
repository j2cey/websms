<?php

function getService($config, $vascode, $requestype, $requestcode){
	$vasservice = array();
	//$config = config('vas');

	$vasservice['resp_code'] = 0;

	if (isset($config[$vascode])){
		if (isset($config[$vascode]['services'][$requestype][$requestcode])){

			$scename = $config[$vascode]['services'][$requestype][$requestcode];
			$vasservice['service'] = $config[$vascode][$scename];
			$vasservice['service']['name'] = $scename;
			$vasservice['sms'] = $config[$vascode]['sms'];
			$vasservice['ucip'] = $config['ucip'];
			//
			$vasservice['resp_code'] = 1;
			$vasservice['resp_string'] = true;

		}else{
			$vasservice['resp_code'] = -2;
			$vasservice['resp_string'] = "Mauvaise requete";
		}
	}else{
		$vasservice['resp_code'] = -1;
		$vasservice['resp_string'] = "Mauvais code VAS";
	}

	return $vasservice;
}