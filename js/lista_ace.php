
 <?php
header("Content-type: text/html; charset=iso-8859-1");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//importando...	
require_once("../lib/verifica.php");
require_once("../lib/util.php");	
require_once("../class/UsuarioException.php");
require_once("../class/TecnicoException.php");
require_once("../class/Conexao.php");	
require_once("../class/Config.php");

//instancias...
$conf  = Config::singleton();	
$banco = Conexao::singleton();


$titulo = "Listagem das Ações do IPA";

 
$mes   = $_GET["mes"];
$ano   = $_GET["ano"];
$status=$_GET['status'];
	$banco = "pam";
    $usuario = "root";
    $senha = "mysql@2oo8";
    $hostname = "localhost";
    $conn = mysql_connect($hostname,$usuario,$senha); mysql_select_db($banco) or die( "Não foi possível conectar ao banco MySQL");
 
 echo $status;
  $sql1="SELECT * FROM tb_municipios group by MUN_IDMunicipio order by MUN_Descricao ";
     $rs1=mysql_query($sql1,$conn);
 
	 
 
     
?>
<table width="676" border="0">
  <tr>
    <td width="286">Municipio</td>
    <td align="center" width="111">Status</td>
    <td align="center" width="74">Semana</td>
    <td align="center" width="74">Ano</td>
  </tr>
  <? while($linha1=mysql_fetch_array($rs1)){  $cor = ($contador % 2 == 1) ? $coratual = "#EBEBEB" : $coratual = "#CCCCCC";  
  
  			$idm=$linha1['MUN_IDMunicipio'];
			
		$sql2="SELECT pe.MUN_IDMunicipio, pe.PLE_Semana, pe.PLE_Ano, m.MUN_Descricao FROM tb_plano_execucao pe 
		LEFT JOIN tb_municipios m ON m.MUN_IDMunicipio = pe.MUN_IDMunicipio
		
		WHERE pe.PLE_Semana = $mes AND pe.PLE_Ano= $ano AND pe.MUN_IDMunicipio<>$idm group by pe.MUN_IDMunicipio ";
     	$rs2=mysql_query($sql2,$conn);
		 while($linha2=mysql_fetch_array($rs2)){
			 $idm1=$linha2['MUN_IDMunicipio'];
  ?>
  
  
  
  <tr bgcolor="<? echo $coratual?>">
    <td><?=$linha1['MUN_Descricao'];?></td>
    
	<? if($idm==$idm1){ ?>
    <td align="center" bgcolor="#00CC00" >Enviado</td>
    <? }else{ ?>
    <td align="center"   bgcolor="#CC0000" >Não Enviado</td>
    <? } ?> 
    <td align="center"><?=$linha2['PLE_Semana'];?></td>
    <td align="center"><?=$linha2['PLE_Ano'];?></td>
   
  </tr>
  <? $contador++; } }?>
</table>
