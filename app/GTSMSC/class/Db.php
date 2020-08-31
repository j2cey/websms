<?php
/**
 *  DB - Une simple classe de base de données  
 *
 * @author		Jude Parfait NGOM NZE
 * @version		0.0
 * @lastmodif	2015 01 10
 */
require("Log.php");
class DB
{
	# @object, L'objet PDO
	private $pdo;

	# @object, Requête d'objet PDO
	private $sQuery;

	# @array,  Les réglages de base de données
	private $settings;

	# @bool ,  Connecté à la base de données
	private $bConnected = false;

	# @object, Objet pour exceptions de logging (journalisation)	
	private $log;

	# @array, Les paramètres de la requête SQL
	private $parameters;
	
	# Paramètres de connexion à la base de données
	private $dbhost;
	private $dbuser;
	private $dbpass;
	private $dbname;
       
	   /**
	*   Constructeur par défaut 
	*
	*	1. Instancier la classe Log (Journal).
	*	2. Se connecter à la base de données.
	*	3. Crée le tableau de paramètres.
	*/
		public function __construct($_dbhost,$_dbuser,$_dbpass,$_dbname)
		{
			$this->SetDbhost($_dbhost);
			$this->SetDbuser($_dbuser);
			$this->SetDbpass($_dbpass);
			$this->SetDbname($_dbname);
				
			$this->log = new Log();	
			$this->Connect();
			$this->parameters = array();
		}
	
	/* Mutateurs des variables d'accès à la base de données */
		public function SetDbhost($_dbhost){
			$this->dbhost = $_dbhost;
		}
		public function SetDbuser($_dbuser){
			$this->dbuser = $_dbuser;
		}
		public function SetDbpass($_dbpass){
			$this->dbpass = $_dbpass;
		}
		public function SetDbname($_dbname){
			$this->dbname = $_dbname;
		}
	/* Fin Mutateurs */
	
	
	/* Accesseurs des variables d'accès à la base de données */
		public function GetDbhost(){
			return $this->dbhost;
		}
		public function GetDbuser(){
			return $this->dbuser;
		}
		public function GetDbpass(){
			return $this->dbpass;
		}
		public function GetDbname(){
			return $this->dbname;
		}
	/* Fin Accesseurs */
		
       /**
	*	Cette méthode permet la connexion à la base de données.
	*	
	*	1. Lit les paramètres d'un fichier ini de base de données.
	*	2. Met le contenu ini dans le tableau des paramètres.
	*	3. Tente de se connecter à la base de données.
	*	4. Si la connexion a échoué, une exception s'affiche et un fichier journal est créé.
	*/
		private function Connect()
		{
			//$this->settings = parse_ini_file("settings.ini.php");
			$dsn = 'mysql:dbname='.$this->dbname.';host='.$this->dbhost.'';
			try 
			{
				# Lire les paramètres du fichier INI, mettre UTF8
				$this->pdo = new PDO($dsn, $this->dbuser, $this->dbpass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
				
				# Nous pouvons maintenant enregistrer des exceptions en cas d'erreur fatale.
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				# Désactiver l'émulation des instructions préparées, utiliser de VRAIES instructions préparées à la place.
				$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				
				# La Connexion a réussi, mettre le booléen à true.
				$this->bConnected = true;
			}
			catch (PDOException $e) 
			{
				# Écrire dans le journal (Log)
				echo $this->ExceptionLog($e->getMessage());
				die();
			}
		}
		
	/*
	 *   On peut utiliser cette petite méthode pour fermer la connexion PDO
	 *
	 */
	 	public function CloseConnection()
	 	{
	 		# Mettre l'objet PDO à null pour fermer la connexion
	 		# http://www.php.net/manual/en/pdo.connections.php
	 		$this->pdo = null;
	 	}
		
		
       /**
	*	Toute méthode qui doit exécuter une requête SQL utilise cette méthode.
	*	
	*	1. Si pas connecté, se connecter à la base de données.
	*	2. Préparer la requête.
	*	3. Paramétrer la requête.
	*	4. Exécuter la requête.
	*	5. En cas d'exception: Ecrire l'Exception dans le journal + requête SQL.
	*	6. Réinitialiser les paramètres.
	*/	
		private function Init($query,$parameters = "")
		{
		# Se connecter à la base de données
		if(!$this->bConnected) { $this->Connect(); }
		try {
				# Préparer la requête
				$this->sQuery = $this->pdo->prepare($query);
				
				# Ajouter des paramètres au tableau de paramètres
				$this->bindMore($parameters);

				# Lier les paramètres
				if(!empty($this->parameters)) {
					foreach($this->parameters as $param)
					{
						$parameters = explode("\x7F",$param);
						$this->sQuery->bindParam($parameters[0],$parameters[1]);
					}		
				}

				# Exécuter SQL
				$this->succes 	= $this->sQuery->execute();		
			}
			catch(PDOException $e)
			{
					# Écrire dans le journal et afficher l'Exception
					echo $this->ExceptionLog($e->getMessage(), $query );
					die();
					# Notification par SMS
					// A FAIRE
			}

			# Réinitialiser les paramètres.
			$this->parameters = array();
		}
		
       /**
	*	@void 
	*
	*	Ajouter un paramètre au tableau de paramètres
	*	@param string $para  
	*	@param string $value 
	*/	
		public function bind($para, $value)
		{	
			$this->parameters[sizeof($this->parameters)] = ":" . $para . "\x7F" . utf8_encode($value);
		}
       /**
	*	@void
	*	
	*	Ajouter plusieurs paramètres au tableau de paramètres
	*	@param array $parray
	*/	
		public function bindMore($parray)
		{
			if(empty($this->parameters) && is_array($parray)) {
				$columns = array_keys($parray);
				foreach($columns as $i => &$column)	{
					$this->bind($column, $parray[$column]);
				}
			}
		}
       /**
	*   Si la requête SQL contient une instruction SELECT ou SHOW elle retourne un tableau contenant tous les jeux de résultats
	*	Si l'instruction SQL est un DELETE, INSERT, ou UPDATE elle retourne le nombre de lignes affectées
	*
	*   @param  string $query
	*	@param  array  $params
	*	@param  int    $fetchmode
	*	@return mixed
	*/			
		public function query($query,$params = null, $fetchmode = PDO::FETCH_ASSOC)
		{
			$query = trim($query);

			$this->Init($query,$params);

			$rawStatement = explode(" ", $query);
			
			# Quels instruction SQL est utilisée ?
			$statement = strtolower($rawStatement[0]);
			
			if ($statement === 'select' || $statement === 'show') {
				return $this->sQuery->fetchAll($fetchmode);
			}
			elseif ( $statement === 'insert' ||  $statement === 'update' || $statement === 'delete' ) {
				return $this->sQuery->rowCount();	
			}	
			else {
				return NULL;
			}
		}
		
      /**
       *  Retourne le dernier ID inséré.
       *  @return string
       */	
		public function lastInsertId() {
			return $this->pdo->lastInsertId();
		}	
		
       /**
	*	Retourne un tableau qui représente une COLONNE du jeu de résultats
	*
	*	@param  string $query
	*	@param  array  $params
	*	@return array
	*/	
		public function column($query,$params = null)
		{
			$this->Init($query,$params);
			$Columns = $this->sQuery->fetchAll(PDO::FETCH_NUM);		
			
			$column = null;

			foreach($Columns as $cells) {
				$column[] = $cells[0];
			}

			return $column;
			
		}
			
       /**
	*	Retourne un tableau qui représente une LIGNE du jeu de résultats
	*
	*	@param  string $query
	*	@param  array  $params
	*   	@param  int    $fetchmode
	*	@return array
	*/	
		public function row($query,$params = null,$fetchmode = PDO::FETCH_ASSOC)
		{				
			$this->Init($query,$params);
			return $this->sQuery->fetch($fetchmode);			
		}
       /**
	*	Retourne la valeur d'un seul CHAMP/COLONNE
	*
	*	@param  string $query
	*	@param  array  $params
	*	@return string
	*/	
		public function single($query,$params = null)
		{
			$this->Init($query,$params);
			return $this->sQuery->fetchColumn();
		}
		
       /**	
	* Écrit le journal et renvoie l'exception
	*
	* @param  string $message
	* @param  string $sql
	* @return string
	*/
		private function ExceptionLog($message , $sql = "")
		{
			$exception  = 'Unhandled Exception. <br />';
			$exception .= $message;
			$exception .= "<br /> You can find the error back in the log.";

			if(!empty($sql)) {
				# Ajouter la requête SQL brute dans le journal
				$message .= "\r\nRaw SQL : "  . $sql;
			}
			# Écrire dans le journal
			$this->log->write($message);

			return $exception;
		}			
}
?>
