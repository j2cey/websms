<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);


function enregistrerRequete($settings,&$request){
	
	// Trace
	$trace_name = "enregistrement requete";
	$trace_result = 0;
	
	// Request string
	$insert_new_request = "INSERT INTO rfialertesrequests(applicant, type, code, request, service, cost, trace, status, result, start_at, end_at, created_at, updated_at) VALUES (:applicant, :type, :code, :request, :service, :cost, :trace, :status, :result, :start_at, :end_at, :created_at, :updated_at)";
	
	$db = new DB($settings['DB_RFIALERTES_HOST'],$settings['DB_RFIALERTES_USERNAME'],$settings['DB_RFIALERTES_PASSWORD'],$settings['DB_RFIALERTES_DATABASE']);
	
	$request['trace'] = addTrace($trace_name,1,$request['trace']);
	$request['created_at'] = date('Y-m-d H:i:s', time());
	
	$db_rslt = $db->query($insert_new_request,$request);
	$request_id = $db->lastInsertId();
	$request['id'] = $request_id;
	
	// Close connection
	$db->CloseConnection();
	
	return $db_rslt;
}

function mettreAjourRequete($settings,&$request){
	
	// Request string
	$update_request = "UPDATE rfialertesrequests SET applicant = :applicant, type = :type, code = :code, request = :request, service = :service, cost = :cost, trace = :trace, status = :status, result = :result, start_at = :start_at, end_at = :end_at, created_at = :created_at, updated_at = :updated_at WHERE id = :id";
	
	$db = new DB($settings['DB_RFIALERTES_HOST'],$settings['DB_RFIALERTES_USERNAME'],$settings['DB_RFIALERTES_PASSWORD'],$settings['DB_RFIALERTES_DATABASE']);
	
	$request['updated_at'] = date('Y-m-d H:i:s', time());
	
	$db_rslt = $db->query($update_request,$request);
	
	// Close connection
	$db->CloseConnection();
	
	return $db_rslt;
}


function insertLives($settings,&$request,$vasservice){
	
	// Return
	$live_rslt = array(0=>0,1=>"",2=>"");
	
	// Trace
	$trace_name = "mise en live souscription";
	$trace_result = 0;
	
	// Request string
	$insert_live = "INSERT INTO rfialerteslives(rfialertesrequest_id,applicant,code,start_live,end_live,created_at,updated_at) VALUES (:rfialertesrequest_id,:applicant,:code,:start_live,:end_live,:created_at,:updated_at);";
	$select_max_endlive = "SELECT MAX(end_live) FROM rfialerteslives WHERE applicant = :applicant;";
	
	
	$db = new DB($settings['DB_RFIALERTES_HOST'],$settings['DB_RFIALERTES_USERNAME'],$settings['DB_RFIALERTES_PASSWORD'],$settings['DB_RFIALERTES_DATABASE']);
	
	$request_live = array(
		'rfialertesrequest_id'	=> $request['id'],
		'applicant'				=> $request['applicant'],
		'code'					=> $vasservice['service']['name'],
		'start_live'			=> null,
		'start_live'			=> null,
		'created_at'			=> null,
		'updated_at'			=> null
	);
				
	$request_live['start_live'] = $db->single($select_max_endlive,array('applicant'=>$request['applicant']));
	
	if ( (is_null($request_live['start_live'])) || ($request_live['start_live'] === "") || ($request_live['start_live'] === false) ){
		$request_live['start_live'] = date('Y-m-d H:i:s', time());
		$ts_start = time();
	}else{
		$ts_start = strtotime($request_live['start_live']);
	}
	
	$ts_exp = $ts_start + 86400*($vasservice['service']['duration']);
	$request_live['end_live'] = date('Y-m-d H:i:s', $ts_exp);
	$request_live['created_at'] = date('Y-m-d H:i:s', time());
	$request_live['updated_at'] = date('Y-m-d H:i:s', time());
	
	$end_live = date('d-m-Y \a H:i:s', $ts_exp);
	
	$live_rslt[1] = $request_live['start_live'];
	$live_rslt[2] = $end_live;
	
	$db_rslt = $db->query($insert_live,$request_live);
	$trace_result = $db_rslt;
	$request['trace'] = addTrace($trace_name,$trace_result,$request['trace']);
	
	if($db_rslt == 1){
		$request['result'] = $request['result'].", Succes mise en live";
	}else{
		$request['result'] = $request['result'].", Echec mise en live";
	}
	
	// Close connection
	$db->CloseConnection();
	
	$maj_rslt = mettreAjourRequete($settings,$request);
	
	return $live_rslt;
}

function deleteLives($settings,&$request){
	
	// Trace
	$trace_name = "suppression souscriptions lives";
	$trace_result = 0;
	
	// Request string
	$suppr_lives_request = "DELETE FROM rfialerteslives WHERE applicant = :applicant";
	
	$db = new DB($settings['DB_RFIALERTES_HOST'],$settings['DB_RFIALERTES_USERNAME'],$settings['DB_RFIALERTES_PASSWORD'],$settings['DB_RFIALERTES_DATABASE']);
	
	$db_rslt = $db->query($suppr_lives_request,array('applicant'=>$request['applicant']));
	$trace_result = $db_rslt;
	$request['trace'] = addTrace($trace_name,$trace_result,$request['trace']);
	
	// Close connection
	$db->CloseConnection();
	
	$maj_rslt = mettreAjourRequete($settings,$request);
	
	return $db_rslt;
}

function addTrace($name, $result, $trace){
	
	if (empty($trace)){
		$trace_tmp = array();
	}else{
		$trace_tmp = json_decode($trace);
	}
	$trace_tmp[] = array('date'=>date('Y-m-d H:i:s', time()),'name'=>$name,'result'=>$result);
	
	return json_encode($trace_tmp);
}