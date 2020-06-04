<?php
	//Limpar o arquivo de Log se o comando for dado pelo botão no log
	$filename = "log.htm";
	if ((isset($_GET["go"])) && (@$_GET["go"] == true)){
		//verificar acesso ao arquivo de Log
		if (is_writable($filename)){
			if ($handle = fopen($filename, 'w')){
				// apagando os dados
				$txt = "
				Log limpo em " . date("d/m/Y") . " &agrave;s " . date("H:i:s") . "h<br>
				________________________________";
				if (fwrite($handle, $txt)) {
					?>
						<script language="javascript" type="text/javascript">
							window.location = "log.htm";
						</script>
					<?
				}else{
					print "Falha ao apagar Log.";
				}
			}else{
				print "Falha ao abrir arqiovo de Log";
			}
		}else{
			print "O arquivo de Log não pôde ser acessado.";
		}
		
	}else{
		print "Você não tem permissão para executar este arquivo.";
	}
?>
