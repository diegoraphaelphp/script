<?php
	/**************
	Estas exceções irão retornar mensagens direcionadas para o administrador do sistema. todas as 
	exceções de administrador se estendem dela. Estas servem para enviar mensagens sigilosas, como
	informações de banco de dados, sql, etc.
	
	**************/
	class tecnicoException extends Exception{
		//insira abaixo os possíveis locais do mesmo arquivo de log
		public $logFile = array("log.htm","../log.htm");
		public $msgErro = "Uma falha no sistema foi detectada e capturada. Contacte o suporte para corrigir o mesmo.";
		
		public function __toString(){
			return "";
		}
		
		
		public function __construct(){
			$traceMsg = $this->fullInfoHTML(parent::getTrace());
			$this->gravarLog($traceMsg);
			
			  $msg_log = "
				<table width=\"400px\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" align='center'>
					<tr>
						<td align=\"center\" style='background-color:#FF4040;color:#FFFFFF;font-family:Verdana, Arial, sans-serif;font-size:11px;font-weight:bold; '>ATEN&Ccedil;&Atilde;O!</td>
					</tr>
					<tr>
						<td align=\"center\" style='background-color:#FFE0E0; border-color:#406080;border-style:solid;border-width:1px;color:#000000;font-family:Tahoma, Arial, sans-serif;font-size:11px;'>".utf8_decode("Ocorreu um erro no Banco de Dados.<br>Uma notifica&ccedil;&atilde;o foi enviada para o Administrador do Sistema.")."</td>
					</tr>
				</table>";
				
				

				$headers = "MIME-Version: 1.1\r\n";
				$headers.= "Content-type: text/html; charset=utf-8\n";
				$headers.= "From: PAM <diego.raphael@ipa.br>\r\n"; // remetente
				$headers.= "Return-Path: diego.raphael@ipa.br\r\n"; // return-path
		
				@mail('diego.raphael@ipa.br', 'PAM : Erro Banco de Dados', $traceMsg, $headers);
				
				exit($msg_log);
		}
		
		public function fullInfo($trace){
			$result = 
				"
				Data: " . date("d/m/Y") . " às " . date("H:i:s") . "
				Mensagem do SGBD: " . @mysql_error() . "
				
				";
				foreach ($trace as $tr){
				foreach ($tr as $key=>$t){
				if ($key != "args"){
				$result .= "$key = $t
				";
				}else{
				$result .= "$key(";
				$cont = count($t);
				$x = 1;
				foreach($t as $arg){
				$result .=  "$arg";
				if($x < $cont){
				$result .= ", ";
				}
				$x++;
				}
				$result .= ")
				";
				}
				}
				$result .= "---------------------------------------------------
				";
				}
				$result .= "
				====================================================
				
				";
			return $result;
		}
		
		
		public function fullInfoHtml($trace){
			$result = 
"
<pre style='font-family:\"Courier New\"; font-size:12px'>
<strong>Data:</strong> <span style='color:#00F'>" . date("d/m/Y") . " às " . date("H:i:s") . "</span>
<strong>Mensagem do SGBD:</strong> <span style='color:#F00'>" . @mysql_error() . "</span>
</pre>
<pre style='font-family:\"Courier New\"; font-size:12px'>
";

foreach ($trace as $tr){
foreach ($tr as $key=>$t){
if ($key != "args"){
$result .= "<strong>$key</strong> = $t
";
}else{
$result .= "<strong>$key</strong>(";
$cont = count($t);
$x = 1;
foreach($t as $arg){
$result .= "<span style='color:#F60'>";

//ver se é um objeto
if (is_object($arg)){
	$vars = get_object_vars($arg);
	$var = "";
	foreach ($vars as $i => $v){
$var .= " $i = $v,
";
	}
$result .= substr($var,-1);
}else{
$result .= $arg;
}


$result .= "</span>";
if($x < $cont){
$result .= ", ";
}
$x++;
}
$result .= ")
";
}
}
$result .= "<hr color='#DDDDDD' size='2' />";
}
$result .= "
<input type='button' value='Limpar Log' onclick='window.location = \"clrLog.php?go=true\"' />
<hr color='#FF0000' size='2' />
</pre>
";

			return $result;
		}
		
		
		public function gravarLog($msg){
			$gravou = false;
			for($x = 0 ; $x < count($this->logFile) ; $x++){
				// Tendo certeza que o arquivo existe e que há permissão de escrita primeiro.
				eval('$filename = $this->logFile[' . $x . '];');
				if (is_writable($filename)) {
					// abrindo log em modo de append (acréscimo).
					// O ponteiro do arquivo estará no final dele
					// Log será escrito com fwrite().
					if (!$handle = fopen($filename, 'a')) {
						 throw new UsuarioException("Falha ao abrir o arquivo de Log");
					}
				
					// Escrevendo os erros para o arquivo aberto.
					if (!fwrite($handle, $msg)) {
						throw new UsuarioException("Falha ao escrever Log.");
					}
					
					//fechando arquivo
					fclose($handle);
					$gravou = true;
					return;
				}
			}
			
			if(!$gravou){
				throw new UsuarioException("ERRO ao escrever no log. Arquivo não encontrado ou não é permitido alterá-lo.");
			}
		}
	}
?>