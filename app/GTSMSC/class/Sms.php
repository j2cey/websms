<?php

include_once('SMPP.php');
include_once('SMPP2.php');

class Sms
{
    private $smpp_host;
    private $smpp_port;
    private $smsc_systemid;
    private $smsc_password;
    private $smsc_systemtype;


    public function __construct($settings)
    {
		//$settings = parse_ini_file("config/settings.ini.php");
		
        $this->smpp_host = $settings['SMPP_HOST'];
        $this->smpp_port = $settings['SMPP_PORT'];
        $this->smsc_systemid = $settings['SMSC_SYSTEMID'];
        $this->smsc_password = $settings['SMSC_PASSWORD'];
        $this->smsc_systemtype = $settings['SMSC_SYSTEMTYPE'];
    }

    public function send($message,$from,$to,$replace = null,$smstype = 1){

		if (is_null($replace)){
            $sms = $message;
        }else{
            $sms = strtr( $message, $replace );
        }

        if($smstype == 1){
            $this->send_message($sms,$from,$to);
            return 1;
        }elseif($smstype == 2){
            $this->send_message_flash($sms,$from,$to);
            return 1;
        }else{
            return -1;
        }

    }

    private function send_message($message,$from,$to) {
        try{
            $tx=new SMPP($this->smpp_host,$this->smpp_port);
            $tx->debug=true;
            $tx->system_type="SMPP";
            $tx->addr_npi=1;
            print "open status: ".$tx->getState()."\n";
            $bind_rslt = $tx->bindTransmitter($this->smsc_systemid,$this->smsc_password);
            // Je modifie la valeur par defaut 1 de la variable sms_source_addr_npi par 0
            $tx->sms_source_addr_npi=0;
            //$tx->sms_source_addr_ton=1;
            $tx->sms_dest_addr_ton=1;
            $tx->sms_dest_addr_npi=1;
            $send_rslt = $tx->sendSMS($from,$to,$message);

            $this->d_sms([$tx,$bind_rslt,$send_rslt],"TX, BIND RSLT, SEND RSLT");

            $tx->close();
            unset($tx);

        }catch (Exception $e){
            $err_msg =  "AN ERROR OCCURED: " . $e->getMessage()." <br>At code: ".$e->getCode()." <br>At file: ".$e->getFile()." <br>At line: ".$e->getLine()." <br>At traceString: ".$e->getTraceAsString();
        }
    }

    private function d_sms($v,$t){
        echo '<pre>';
        echo '<h1>' . $t. '</h1>';
        var_dump($v);
        echo '</pre>';
    }

    private function send_message_flash($message,$from,$to){
        try{
            $smpp = new SMPP2();
            $smpp->SetSender($from);
            $smpp->Start($this->smpp_host, $this->smpp_port, $this->smsc_systemid, $this->smsc_password, $this->smsc_systemtype);

            // Envoi de message flash
            $smpp->Send("$to", "$message");

        }catch (Exception $e){
            $err_msg =  "AN ERROR OCCURED: " . $e->getMessage()." <br>At code: ".$e->getCode()." <br>At file: ".$e->getFile()." <br>At line: ".$e->getLine()." <br>At traceString: ".$e->getTraceAsString();
        }
    }
}