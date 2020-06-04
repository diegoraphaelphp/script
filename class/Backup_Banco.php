<?php
class mysql_backup {
 
 var $connection;
 var $db;
 var $data;
 
 //Connects mySQL
 function connect($host,$user,$pass,$db) {
	$this->connection = mysql_connect($host,$user,$pass) or die(mysql_error());
	$this->db = $db;
	mysql_select_db($db) or die(mysql_error());
 }

 //Adds data
 function add($data) {
	$this->data .= $data;
 }

 //Prepares the data to put in the file
 function make_spell($content) {
	$content = mysql_escape_string($content);
	if (!$content) return "NULL";
	elseif (is_numeric($content)) return $content;
	else return "'".$content."'";
 }

 //Read and saves into memory the structure and data of the tables
 function structure() {

	$this->add("-- MySQL Dump\n");
	$this->add("-- Criado por Diego Raphael\n");
	$this->add("-- <diegoraphael.php@gmail.com>\n");

	$tables = mysql_list_tables($this->db,$this->connection);
	$number_tables = mysql_num_rows($tables)-1;

	for ($i=0;$i<=$number_tables;$i++) {
		//Table to be created
		$t_name = mysql_tablename($tables,$i);
		$this->add("\n\n");
		$this->add("--\n");
		$this->add("-- Structure for table '$t_name'\n");
		$this->add("--\n");
		$this->add("\n");

		//Destroy table if exists
		$this->add("DROP TABLE IF EXISTS `$t_name`;\n");

		//Adds the data
		$query = mysql_query("SHOW CREATE TABLE `$t_name`") or die(mysql_error());
		$fetch = mysql_fetch_array($query);
		$t_structure = $fetch[1].";";
		$this->add($t_structure);

		//Adiciona os dados da tabela
		$this->add("\n");
		$this->add("--\n");
		$this->add("-- Data for table '$t_name'\n");
		$this->add("--\n");
		$this->add("\n");

		$query = mysql_query("SELECT * FROM `$t_name`") or die(mysql_error());
		if (mysql_num_rows($query)== 0) {
			$this->add("-- Table without data\n");
		}
		else {
			$this->add("LOCK TABLES `$t_name` WRITE;\n");
			$this->add("INSERT INTO `$t_name` values \n");

			while ($row=mysql_fetch_row($query)) {
				$row = array_map(array($this,'make_spell'),$row);
				$data = implode(",",$row);
				$data_array[] = "(".$data.")";
			}
			
			$data = implode(" ,\n",$data_array);
			$data .= ";\n";
			$this->add($data);
			$this->add("UNLOCK TABLES;\n");
			unset($data_array);
		}
	}
 }

 //Shows the content in the browser
 function export_browser() {
 	$html = $this->data;
 	$html = htmlentities($html);
	$html = str_replace("\n","<br>",$html);
	echo $html;
 }

 //Saves the file in the server
 function export_server($file_name,$compress=1) {
 	if ($compress==0) {
		if (file_exists($file_name)) unlink($file_name);
		$openarq = fopen($file_name,"w+");
		$salva = fwrite($openarq, $this->data);
		fclose($openarq);
		chmod($file_name,0666);
	}
	else {
		if (file_exists($file_name.".	")) unlink($file_name.".zip");
		$openarq = gzopen($file_name.".zip", "wb9");
		gzwrite($openarq,$this->data);
		gzclose($openarq);
		chmod($file_name.".zip",0666);
	}
 }

 //Desconnects mySQL
 function disconnect() {
	mysql_close($this->connection);
 }
 
}
?>