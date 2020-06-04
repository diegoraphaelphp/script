<?php
	//class FalhaConexaoException extends tecnicoException{}

	class ConexaoMySqlI{
	
		private $conexao = NULL;
		private $host    = NULL;
		private $login   = NULL;
		private $pass    = NULL;
		private $bd      = NULL;
		
		private $rep;
		public static $instance;
		
		public static function singleton() {
			if (!isset(self::$instance)) {
				self::$instance = new ConexaoMySqlI();
			}
			return self::$instance;
		}	

		public function __construct(){
			@require("Banco.php");
			$this->host  = trim($PRO_host);
			$this->login = trim($PRO_usuario);
			$this->pass  = trim($PRO_senha);
			$this->bd    = trim($PRO_basededados);

			try{
				$conn = new mysqli($this->host, $this->login, $this->pass, $this->bd);
			}catch(Exception $e){
				throw new FalhaConexaoException("Não foi possível conectar com o banco.<br>host: $host<br>Login: $login <br> Senha: $pass",'101001');
			}

			$this->conexao = $conn;			
		}

		public function getConexao(){
			return $this->conexao;
		}

		public function executarQuery($sql){		
			//$retorno = mysqli_query($this->getConexao(), $sql);		
			
			$retorno = $this->getConexao()->query($sql);					
			if($retorno == false){	
//			     echo $sql;
                 throw new Exception();
			}

			return $retorno;
		}
		
		public function executarMultiQuery($sql){		
			//$retorno = mysqli_query($this->getConexao(), $sql);		
			$retorno = $this->getConexao()->multi_query($sql);		
			
			if($retorno == false){				
				throw new Exception();
			}

			return $retorno;
		}

		public function desativarAutoCommit()	{
			//mysqli_autocommit($this->conexao, false);
			$this->getConexao()->autocommit(false);
		}
		
		public function commit(){
			//mysqli_commit($this->conexao);
			$this->getConexao()->commit();
		}
		
		public function rollback()	{
			//mysqli_rollback($this->conexao);
			$this->getConexao()->rollback();
		}
		
		public function ultimoId($sql){
			$this->getConexao();
			$select = @mysqli_query($this->getConexao(), $sql);
			$id     = @mysql_insert_id();
			if ($select == false){
			  echo "
				<table width=\"30%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" align='center'>
					<tr>
						<td align=\"center\" style='background-color:#FF4040;color:#FFFFFF;font-family:Verdana, Arial, sans-serif;font-size:11px;font-weight:bold; '>ATEN&Ccedil;&Atilde;O!</td>
					</tr>
					<tr>
						<td align=\"center\" style='background-color:#FFE0E0; border-color:#406080;border-style:solid;border-width:1px;color:#000000;font-family:Tahoma, Arial, sans-serif;font-size:11px;'>Ocorreu um erro neste processo, consulte o Administrador do Sistema.</td>
					</tr>
				</table>";
				exit;
			}		
//			$this->Fechar();
			return $id;
		}

		public function listarArray($sql){
			$result       = $this->executarQuery($sql);
			$retornoArray = array();
            
            while($fetch = mysql_fetch_array($result, MYSQL_ASSOC)){
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
			$result = $this->getConexao()->query($sql);		

			if ($this->getConexao()->affected_rows){
				return true;
			}else{
				return false;
			}
		}
		
		
		public function indiceUnicoDuplicado(){
			//$num = $this->getConexao()->errno;
			//$frase = $this->getConexao()->error;
			
			//	1062 é o número de erro do mysql, indicando que existe um valor duplicado
			if ($this->getConexao()->errno == 1062)	{
				return true;
			}
			return false;
		}
		
		
		
		
	}
?>
