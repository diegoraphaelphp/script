<?php
	#class FalhaConexaoException extends tecnicoException{}

	class Conexao{
	
		private $conexao = NULL;
		private $host    = NULL;
		private $login   = NULL;
		private $pass    = NULL;
		private $bd      = NULL;
		
		private $rep;
    	public static $instance;
		
		public static function singleton() {
			if (!isset(self::$instance)) {
				self::$instance = new Conexao();
			}
			return self::$instance;
		}	

		public function __construct(){
			@require("Banco.php");
			$this->host  = trim($PRO_host);
			$this->login = trim($PRO_usuario);
			$this->pass  = trim($PRO_senha);
			$this->bd    = trim($PRO_basededados);
			
			@$conn = mysql_pconnect($this->host, $this->login, $this->pass);
			if (!$conn){			
				throw new FalhaConexaoException("Não foi possível conectar com o banco.<br>host: $host<br>Login: $login <br> Senha: $pass",'101001');
			}
			$this->conexao = $conn;			
			@$conecta = @mysql_select_db($this->bd, $this->conexao);
			if (!$conecta){
				exit("treta!");
				throw new FalhaConexaoException("Não foi possível selecionar o banco de dados.<br>Database: $bd",'101002');
			}
		}

		public function getConexao(){
			return $this->conexao;
		}

		public function executarQuery($sql){
			$retorno = @mysql_query($sql, $this->getConexao());
                        
			if($retorno == false){
				throw new tecnicoException();
			}
			
			return $retorno;
		}
        
		public function execQRY($sql){
			return @mysql_query($sql, $this->getConexao());			
		}
		
		
		public function ultimoId($sql){
			$this->getConexao();
			$select = @mysql_query($sql);
			$id     = @mysql_insert_id();
			if ($select == false){
				throw new tecnicoException();
			}		
//			$this->Fechar();
			return $id;
		}		


		public function listarArray($sql){

			$result = $this->executarQuery($sql);
			$retornoArray = array();
			while($fetch = mysql_fetch_array($result,MYSQL_ASSOC)){
				array_push($retornoArray,$fetch);
			}
			return $retornoArray;
			
		}
		
		public function totalLinhas($select){
			return @mysql_num_rows($select);
		}
		
		public function criaArray($query){
			$array = mysql_fetch_array($query);
			return $array;
		}
		
		public function getDadosNum($sql){
			$result = $this->executarQuery($sql);
			$fetch = mysql_fetch_array($result,MYSQL_NUM);
			return $fetch;
		}
		
		
		public function __destruct(){
			unset($this->conexao);
		}
		
		public function existe($sql){
			$result = $this->executarQuery($sql);
			$num    = mysql_num_rows($result);
			if ($num > 0){
				return true;
			}else{
				return false;
			}
		}
	}
?>
