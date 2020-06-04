<?php    
    if(isset($_SESSION["USUARIO_ID"]) && isset($_SESSION["ACESSOPERMITIDO"])){
        if($_SESSION["ACESSOPERMITIDO"] != "TRUE"){
            header("Location: ".$_SESSION["HTTP"]);
        }
    }else{
        header("Location: ".$_SESSION["HTTP"]);     
    }
?>
