<?php
	class usuarioException extends Exception{
		/*
		Estas exceções irão retornar mensagens direcionadas para o usuario. todas as exceções de usuarios
		se estendem dela. As exceções terão que ser controladas pela gui.
		*/
		
		public function __toString(){
			return parent::getMessage();
		}
		
		public function alertMsg($msg = null){
			if (($msg == null) || ($msg == "")){
				$msg = parent::getMessage();
			}
			
			$strJs = '
				<script language="javascript" type="text/javascript">
					alert("'.$msg.'");
				</script>
			';
			
		}
		
		public function alertMsgGoTo($locl = null, $msg = null){
			
			if (($msg == null) || ($msg == "")){
				$msg = parent::getMessage();
			}
			
			if (($locl == null) || ($locl == "")){
				$locl = "false";
			}
			$strJs = '
				<script language="javascript" type="text/javascript">
					locl = "'.$locl.'";
					
					alert("'.$locl.'");
					if (locl == "false"){
						history.back(-1);
					}else{
						window.location = locl;
					}
				</script>
			';
		}
	}
?>