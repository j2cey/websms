<?php

/*
		REQUETES
*/

function sendbypostUcip($settings,$xmlRqst)
{
	//$settings = parse_ini_file("config/settings.ini.php");
	
	$ucip_host = $settings['UCIP_HOST'];
    $ucip_port = $settings['UCIP_PORT'];
    $ucip_user = $settings['UCIP_USER'];
    $ucip_password = $settings['UCIP_PASSWORD'];
    $ucip_lien = $settings['UCIP_LIEN'];
	
	$taille = strlen($xmlRqst);

	$requete1 =  "POST $ucip_lien HTTP/1.1\n";
	$requete1 .= "Content-type: text/xml\n";
	$requete1 .= "Host: $ucip_host:$ucip_port\n";
	$requete1 .= "User-Agent: /2.0/1.0/\n";
	$requete1 .= "Authorization: Basic ".base64_encode($ucip_user.':'.$ucip_password)."\n";
	$requete1 .= "Content-length: $taille\n";
	$requete1 .= "Connection : keep-alive\n\n";
	$requete1 .= $xmlRqst;

	// echo " La requete envoyee est :  $requete1 " ;
	
	//dd_ucip($requete1,"RQST ready:");
	//echo "<pre>RQST ready:</pre>";
	//echo "<pre>". htmlentities($requete1) . "</pre>";

	if ($fp_msi = fsockopen($ucip_host, $ucip_port, $errno, $errstr,180)){
		fputs($fp_msi, $requete1);
		$ret='' ;
		while ($buff=fread($fp_msi,1000))
		{
			$ret .=$buff ;
		}
	}

	else
	{
		echo 'erreur connexion au serveur\n';
	}
	//  echo $ret;

	fclose ($fp_msi);

	//dd_ucip($ret,"RQST response:");
	//echo "<pre>RQST response:</pre>";
	//echo "<pre>". htmlentities($ret) . "</pre>";

	return  $ret;
}

function getAccountInformations($settings,$mobile){
	$originNodeType = 'EXT';
	$serveur_hote = 'moovfoot';
	$trans_ID = '2000';
	$numberNAI = '2';
	$date_transaction = date("Ymd")."T".date("H:i:s")."+0000";

	$requete ='';
	$requete .='<?xml version="1.0" encoding="utf-8"?>';
	$requete .='<methodCall><methodName>BalanceEnquiryTRequest</methodName>';
	$requete .='<params><param><value><struct>';
	$requete .='<member><name>originNodeType</name><value><string>'.$originNodeType.'</string></value></member>';
	$requete .='<member><name>originHostName</name><value><string>'.$serveur_hote.'</string></value></member>';
	$requete .='<member><name>originTransactionID</name><value><string>'.$trans_ID.'</string></value></member>';
	$requete .='<member><name>originTimeStamp</name><value><dateTime.iso8601>'.$date_transaction.'</dateTime.iso8601></value></member>';
	$requete .='<member><name>subscriberNumberNAI</name><value><int>'.$numberNAI.'</int></value></member>';
	$requete .='<member><name>subscriberNumber</name><value><string>'.$mobile.'</string></value></member>';
	$requete .='<member><name>chargingRequestInformation</name></member>';
	$requete .='</struct></value></param></params>';
	$requete .='</methodCall>';
	
	$result_xml = sendbypostUcip($settings,$requete);
	
	return parseUcipAccountInfosDetail($result_xml);
}

function adjustMainAccount($settings,$mobile,$amount,$transaction_id){
	
	$amount_neg = (-1) * ( (int)$amount );
	
	$originNodeType = 'EXT';
	$serveur_hote = 'applivas';
	// $trans_ID = '2000';
	$numberNAI = '2';
	$date_transaction = date("Ymd")."T".date("H:i:s")."+0000";

	$requete ='';
	$requete .='<?xml version="1.0" encoding="utf-8"?>';
	$requete .='<methodCall><methodName>AdjustmentTRequest</methodName>';
	$requete .='<params><param><value><struct>';
	$requete .='<member><name>originNodeType</name><value><string>'.$originNodeType.'</string></value></member>';
	$requete .='<member><name>originHostName</name><value><string>'.$serveur_hote.'</string></value></member>';
	$requete .='<member><name>originTransactionID</name><value><string>'.$transaction_id.'</string></value></member>';
	$requete .='<member><name>originTimeStamp</name><value><dateTime.iso8601>'.$date_transaction.'</dateTime.iso8601></value></member>';
	$requete .='<member><name>subscriberNumberNAI</name><value><int>'.$numberNAI.'</int></value></member>';
	$requete .='<member><name>subscriberNumber</name><value><string>'.$mobile.'</string></value></member>';
	$requete .='<member><name>transactionCurrency</name><value><string>CFA</string></value></member>';
	$requete .='<member><name>adjustmentAmount</name><value><string>'.$amount_neg.'</string></value></member>';
	$requete .='</struct></value></param></params>';
	$requete .='</methodCall>';
	
	$result_xml = sendbypostUcip($settings,$requete);
	
	return parseUcipAdjustAccount($result_xml);
}



/*
		REPONSES
*/

function isValidResultsUcip($ucip_resp_code){
	
	return ($ucip_resp_code == 0
		AND (is_numeric($ucip_resp_code)
			&& (intval(0 + $ucip_resp_code) == $ucip_resp_code)
		)
	);
}

function assignAccountInfos($sendRslt){

	$ucip_resp_code = 'N';
	$main_account = -1;
	$service_class = array();
	$dedicated_accounts = array();

	$account_infos_json = "";
	
	$accountinfosresult_array = array();

	//$sendRslt = $this->accountinfosresult_array;

	if(isset($sendRslt["params"]["param"]["value"]["struct"]["member"][5]["value"]["i4"])){
		$ucip_resp_code = (int)$sendRslt["params"]["param"]["value"]["struct"]["member"][5]["value"]["i4"];

		if (isValidResultsUcip($ucip_resp_code)){

			// Main account
			$main_account = (isset($sendRslt["params"]["param"]["value"]["struct"]["member"][0]["value"]["string"])
				? (int)$sendRslt["params"]["param"]["value"]["struct"]["member"][0]["value"]["string"]
				: -2
			);

			// DDA
			if (isset($sendRslt["params"]["param"]["value"]["struct"]["member"][4]["value"]["array"]["data"]["value"])){
				foreach($sendRslt["params"]["param"]["value"]["struct"]["member"][4]["value"]["array"]["data"]["value"] as $dda){
					$dedicated_accounts[] = array(
						'dedicatedAccountID'=>(int)$dda["struct"]["member"][0]["value"]["i4"],
						'dedicatedAccountValue1'=>(int)$dda["struct"]["member"][1]["value"]["string"]);
				}
			}

			// Service Class
			$service_class["desc"] = "";
			$service_class["value"] = (isset($sendRslt["params"]["param"]["value"]["struct"]["member"][6]["value"]["i4"])
				? (int)$sendRslt["params"]["param"]["value"]["struct"]["member"][6]["value"]["i4"]
				: -2
			);

		}else{
		}
	}else{
		$ucip_resp_code = -2;
	}

	$accountinfosresult_array["responseCode"] = $ucip_resp_code;
	$accountinfosresult_array["MainAccount"] = $main_account;
	$accountinfosresult_array["DedicatedAccounts"] = $dedicated_accounts;
	$accountinfosresult_array["ServiceClass"] = $service_class;

	$account_infos_json = json_encode($accountinfosresult_array);
	
	$accountinfosresult_array["JSON"] = $account_infos_json;
	
	return $accountinfosresult_array;
}

function assignAdjustAccountResult($sendRslt){

	$ucip_resp_code = 'N';
	//$sendRslt = $this->adjustaccount_rslt_array;

	if(isset($sendRslt["params"]["param"]["value"]["struct"]["member"]["value"]["i4"])){

		$ucip_resp_code = (int)$sendRslt["params"]["param"]["value"]["struct"]["member"]["value"]["i4"];

	}else{
		$ucip_resp_code = -2;
	}
	
	return $ucip_resp_code;
}


function parseUcipAccountInfosDetail($result_xml)
{
	$ucip_resp_code = 'N';
	$ucip_fault_code = 'N';
	$ucip_fault_string = 'N';
	
	$ucip_response = "";
	
	$rep_pos = strpos($result_xml,"responseCode");

	if ($rep_pos !== false)
	{
		// $tmp_str_rep = substr($result_xml,$rep_pos+31,50);
		$tmp_str_rep = substr($result_xml,($rep_pos + 31),strlen($result_xml) - ($rep_pos + 31));
		$fin_rep_pos = strpos($tmp_str_rep,"</i4>");
		$ucip_resp_code = substr($tmp_str_rep, 0, $fin_rep_pos);

		//$resultat = array(0=>$ucip_resp_code, 1=>$ucip_fault_code, 2=>$ucip_fault_string, 3=>$ucip_response);
	}

	if ($ucip_resp_code == "0")
	{
		$innerResult_xml = returnMethodResponseStringXML($result_xml);

		$tempArray = XMLstr_to_array($innerResult_xml);
		$tempArray = manageEmptyArray($tempArray);
		$ucip_response = $tempArray;

		//$resultat = array(0=>$ucip_resp_code, 1=>$ucip_fault_code, 2=>$ucip_fault_string, 3=>$ucip_response);
	}
	else
	{
		$rep_pos = strpos($result_xml,"faultCode");
		// $tmp_str_rep = substr($result_xml,$rep_pos+28,50);
		$tmp_str_rep = substr($result_xml,($rep_pos + 28),strlen($result_xml) - ($rep_pos + 28));
		$fin_rep_pos = strpos($tmp_str_rep,"</i4>");
		$ucip_fault_code = substr($tmp_str_rep, 0, $fin_rep_pos);

		$rep_pos = strpos($result_xml,"faultString");
		// $tmp_str_rep = substr($result_xml,$rep_pos+34,200);
		$tmp_str_rep = substr($result_xml,($rep_pos + 34),strlen($result_xml) - ($rep_pos + 34));
		$fin_rep_pos = strpos($tmp_str_rep,"</string>");
		$ucip_fault_string = substr($tmp_str_rep, 0, $fin_rep_pos);

		//$resultat = array(0=>$ucip_resp_code, 1=>$ucip_fault_code, 2=>$ucip_fault_string, 3=>$ucip_response);
	}
	
	$resultat = array(0=>$ucip_resp_code, 1=>$ucip_fault_code, 2=>$ucip_fault_string, 3=>$ucip_response);
	//$resultat = array(0=>$this->ucip_resp_code, 1=>$fault_code, 2=>$fault_string, 3=>$service_classe_id, 4=>$account_value);

	return $resultat ;

}

function parseUcipAdjustAccount($result_xml){
	
	$ucip_resp_code = 'N';
	$ucip_fault_code = 'N';
	$ucip_fault_string = 'N';
	
	$ucip_response = "";
		
	$rep_pos = strpos($result_xml,"responseCode");
	
	if ($rep_pos !== false){
		
		$innerResult_xml = returnMethodResponseStringXML($result_xml);

		$tempArray = XMLstr_to_array($innerResult_xml);
		$tempArray = manageEmptyArray($tempArray);

		$ucip_response = $tempArray;
		
	}else{
		
		$rep_pos = strpos($result_xml,"faultCode");
		// $tmp_str_rep = substr($result_xml,$rep_pos+28,50);
		$tmp_str_rep = substr($result_xml,($rep_pos + 28),strlen($result_xml) - ($rep_pos + 28));
		$fin_rep_pos = strpos($tmp_str_rep,"</i4>");
		$ucip_fault_code = substr($tmp_str_rep, 0, $fin_rep_pos);

		$rep_pos = strpos($result_xml,"faultString");
		// $tmp_str_rep = substr($result_xml,$rep_pos+34,200);
		$tmp_str_rep = substr($result_xml,($rep_pos + 34),strlen($result_xml) - ($rep_pos + 34));
		$fin_rep_pos = strpos($tmp_str_rep,"</string>");
		$ucip_fault_string = substr($tmp_str_rep, 0, $fin_rep_pos);

		$resultat = array(0=>$ucip_resp_code, 1=>$ucip_fault_code, 2=>$ucip_fault_string, 3=>$ucip_response);
	}
	
	$resultat = array(0=>$ucip_resp_code, 1=>$ucip_fault_code, 2=>$ucip_fault_string, 3=>$ucip_response);
	//$resultat = array(0=>$resp_code, 1=>$fault_code, 2=>$fault_string);
	
	return $resultat ;
}

function manageEmptyArray($arrayToManage){
	$finalArray;

	foreach($arrayToManage as $cle => $elem){
		if (is_array($elem)){
			if(count($elem) == 0){  // empty array
				$finalArray[$cle] = "";
			}
			else{
				$finalArray[$cle] = manageEmptyArray($elem);
			}
		}
		else{
			$finalArray[$cle] = str_replace(';', '',$elem);
		}
	}
	return $finalArray;
}

function XMLstr_to_array($xmlstr){
	$doc = new \DOMDocument();
	$doc->loadXML($xmlstr);

	return DOMnode_to_array($doc->documentElement);
}

function DOMnode_to_array($node){
	$output = array();
	switch ($node->nodeType){
		case XML_CDATA_SECTION_NODE:
		case XML_TEXT_NODE:
			$output = trim($node->textContent);
			break;
		case XML_ELEMENT_NODE:
			for ($i=0, $m=$node->childNodes->length; $i<$m; $i++){
				$child = $node->childNodes->item($i);
				$v = DOMnode_to_array($child);

				if(isset($child->tagName)){
					$t = $child->tagName;

					if(!isset($output[$t])){
						$output[$t] = array();
					}
					$output[$t][] = $v;
				}
				elseif($v){
					$output = (string) $v;
				}
			}
			if(is_array($output)){
				if($node->attributes->length){
					$a = array();
					foreach($node->attributes as $attrName => $attrNode){
						$a[$attrName] = (string) $attrNode->value;
					}
					$output['@attributes'] = $a;
				}
				foreach ($output as $t => $v) {
					if(is_array($v) && count($v)==1 && $t!='@attributes') {
						$output[$t] = $v[0];
					}
				}
			}
			break;
	}
	return $output;
}

function returnMethodResponseStringXML($result_xml){
	//<methodResponse>
	$pos = strpos($result_xml, '<methodResponse>');

	if ($pos === false) {
		return "";
	} else {
		return substr($result_xml, $pos);
	}
}

function dd_ucip($v,$t){
	echo '<pre>';
	echo '<h1>' . $t. '</h1>';
	var_dump($v);
	echo '</pre>';
}