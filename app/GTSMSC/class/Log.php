<?php 
    /* * *
	* Log 			Une classe d'enregistrement qui crée des journaux quand une exception est levée.
	* @author		Jude Parfait NGOM NZE
	* @version      0.0
	*/
	class Log {
			
		    # @string, Nom du répertoire des Logs
		    	private $path = '/logs/';
			
		    # @void, Constructeur par défaut, Définit le fuseau horaire et le chemin des fichiers journaux.
			public function __construct() {
				date_default_timezone_set('Europe/Amsterdam');	
				$this->path  = dirname(__FILE__)  . $this->path;	
			}
			
		   /**
		    *   @void 
		    *	Crée le journal (log)
		    *
		    *   @param string $message le message qui est écrit dans le journal.
		    *	@description:
		    *	 1. Vérifie si le répertoire existe, sinon, créer un et appelle à nouveau cette méthode.
	        *	 2. Vérifie si le journal (log) existe déjà.
		    *	 3. Si non, nouveau journal est créé. Le journal (log) est écrit dans le dossier des journaux.
		    *	 4. Le nom du journal est la date du jour (Année - Mois - Jour).
		    *	 5. Si le journal existe, la méthode d'édition est appelée.
		    *	 6. La méthode d'édition modifie le journal actuel.
		    */	
			public function write($message) {
				$date = new DateTime();
				$log = $this->path . $date->format('Y-m-d').".txt";

				if(is_dir($this->path)) {
					if(!file_exists($log)) {
						$fh  = fopen($log, 'a+') or die("Fatal Error !");
						$logcontent = "Time : " . $date->format('H:i:s')."\r\n" . $message ."\r\n";
						fwrite($fh, $logcontent);
						fclose($fh);
					}
					else {
						$this->edit($log,$date, $message);
					}
				}
				else {
					  if(mkdir($this->path,0777) === true) 
					  {
 						 $this->write($message);  
					  }	
				}
			 }
			
			/** 
			 *  @void
			 *  Est appelée si journal existe.
			 *  Modifie le journal actuel et ajouter le message dans le journal.
			 *
			 * @param string $log
			 * @param DateTimeObject $date
			 * @param string $message
			 */
			    private function edit($log,$date,$message) {
				$logcontent = "Time : " . $date->format('H:i:s')."\r\n" . $message ."\r\n\r\n";
				$logcontent = $logcontent . file_get_contents($log);
				file_put_contents($log, $logcontent);
			    }
		}
?>
