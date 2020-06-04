<?php
    function __autoload($strNomeClasse){
        $strStrDir = array(	
            $_SESSION["RAIZ"]."/classes/basicas/",
            $_SESSION["RAIZ"]."/classes/dbs/",
            $_SESSION["RAIZ"]."/classes/fachadas/",
            $_SESSION["RAIZ"]."/classes/helpers/",
            $_SESSION["RAIZ"]."/classes/negocios/",
            $_SESSION["RAIZ"]."/classes/repositorios/"
        );

        for($intI=0; $intI<count($strStrDir);$intI++){
            if(file_exists($strStrDir[$intI].$strNomeClasse.".php")){
                require_once $strStrDir[$intI].$strNomeClasse.".php";
            }
        }
    }    
?>