<?php
	namespace AQSP;
	require_once '../config.php';
	class Database {
		private static $instance;
		private $conn;

		/**
		* Costruttore vuoto. Instanzia la connessione con il Database
		*/
		public function __construct() {
			$this->mysqli = new \mysqli($DB_host, $DB_user, $DB_password, $DB_name);
			$this->mysqli->set_charset('utf8mb4');
		}

		/**
		* @return Database se la connessione è già stata istanziata ritorna l'oggetto già creato, altrimenti ne ritorna una nuova
		*/
		public static function init() {
			if(is_null(self::$instance)){
				self::$instance = new Database();
			}
			return self::$instance;
		}

		/**
		* @param  string $query la query in linguaggio SQL di tipo SELECT
		* @return mixed se l'operazione è andata a buon fine ritorna un array bidimensionale (array) altrimenti false (boolean)
		*/
		public function querySelect($query) {
			$ris = $this->mysqli->query($query);
			if($ris){
				return $ris->fetch_all(MYSQLI_ASSOC);
			}
			return false;
		}

		/**
		* @param  string $query la query in linguaggio SQL di tipo DML
		* @return mixed se l'operazione è andata a buon fine ritorna il numero di righe affette (integer) altrimenti ritorna l'errore (string)
	 	*/
		public function queryDML($query) {
			try{
				$this->mysqli->query($query);
				return $this->mysqli->affected_rows;
			}catch(Exception $e){
				return $this->error();
			}
		}

		/**
		* @param  string $query la query in linguaggio SQL
		* @return mixed se non ci sono errori il numero di righe ritornate dalla query (integer), altrimenti l'errore (string)
		*/
		public function numRows($query) {
			try{
				$ris = $this->mysqli->query($query);
				return $ris->num_rows;
			}catch(Exception $e){
				return $this->error();
			}
		}

		/**
		* @return array una lista di errori, ogni elemento è un array associativo contenente errno, error, e sqlstate.
		*/
		public function error(){
			return $this->mysqli->error_list;
		}

		/**
		* @return mysqli l'istanza di mysqli
		*/
		public function getObj(){
			return $this->mysqli;
		}

		/**
		* @return insert_id
		*/
		public function getInsertId(){
			return $this->mysqli->insert_id;
		}

		/**
		* @param  string $raw la stringa sulla quale eseguire l'escape
		* @return string la stringa ora protetta da SQL injection
		*/
		public function escape($raw) {
			return $this->mysqli->real_escape_string($raw);
		}
	}
?>
