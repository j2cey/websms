<?php
/**
 * Custom Logger
 * @author judparfait@gmail.com
 */
class CustomLog
{
	
	/**
	*   @void 
	*	�crit le fichier
	*
	*   @param string $message le message � �crire.
	*/	
	public static function write($filepath, $message) {
		if(!file_exists($filepath)) {
			$fh  = fopen($filepath, 'a+') or die("Fatal Error !");
			$logcontent = $message;
			fwrite($fh, $logcontent);
			fclose($fh);
		}else {
			$logcontent = file_get_contents($filepath);
			$logcontent = $logcontent ."\r\n" . $message;
			file_put_contents($filepath, $logcontent);
		}
	}
	
	// pass in the number of seconds elapsed to get hours:minutes:seconds returned
	public static function secondsToTime($s)
	{
	    $h = floor($s / 3600);
	    $s -= $h * 3600;
	    $m = floor($s / 60);
	    $s -= $m * 60;
	    return $h.':'.sprintf('%02d', $m).':'.sprintf('%02d', $s);
	}
	
	public static function logStart($filepath, $msg){
		$start = new DateTime();
		if ($msg == "") {
			$line = $start->format('Y-m-d H:i:s');
		} else {
			$line = $msg . " - " . $start->format('Y-m-d H:i:s');
		}
		self::write($filepath, $line);
		/*
		$starttime = self::secondsToTime(microtime(true));
		if ($msg == "") {
			$line = $starttime;
		} else {
			$line = $msg . " - " . $starttime;
		}
		self::write($filepath, $line);
		*/
	}
	
	public static function logEnd($filepath, $starttime, $msg){
		
		//$first  = new DateTime( '11:35:20' );
		$second = new DateTime();

		$diff = $starttime->diff( $second );

		$diff_str = $diff->format( '%H:%I:%S' ); // -> 00:25:25
		if ($msg == "") {
			$line = $second->format('Y-m-d H:i:s') . ". Duree: ". $diff_str;
		} else {
			$line = $msg . " - " . $second->format('Y-m-d H:i:s') . ". Duree: ". $diff_str;
		}
		self::write($filepath, $line);
		
		/*
		$endtime = microtime(true);
		$endtime_str = self::secondsToTime($endtime);
		$timediff = $endtime - $starttime;
		$timediff_str = self::secondsToTime($timediff);
		if ($msg == "") {
			$line = $endtime_str . ". Duree: ". $timediff_str;
		} else {
			$line = $msg . " - " . $endtime_str . ". Duree: ". $timediff_str;
		}
		self::write($filepath, $line);
		*/
	}
}