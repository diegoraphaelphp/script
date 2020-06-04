<?php
    $_SESSION["RAIZ"] = $_SERVER["DOCUMENT_ROOT"]."/sisplan";
    $_SESSION["HTTP"] = "http://localhost/sisplan";
    
    // configurações do jQuery
    define("JQUERY_JS", "js/jquery-1.7.1.min.js");
    define("JQUERY_UI_JS", "js/jquery-ui-1.8.17.custom.min.js");
    define("JQUERY_UI_CSS", "css/jquery-ui/smoothness/jquery-ui-1.8.17.custom.css");
    
    // configurações do sistema
    define("SISTEMA_TITULO", "SISPLAN - Sistema de Planejamento Anual");
    define("SISTEMA_NOME", "SISPLAN");    
    define("SISTEMA_CSS", "css/sistema.css");
    define("SISTEMA_FAVICON", "http://localhost/sisplan/favicon.ico");
?>