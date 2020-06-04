<?php
    if (!empty($_FILES)) {
    
        $tempFile   = $_FILES['Filedata']['tmp_name'];
        $targetPath = "../tmp/sigater/"; //$_SERVER['DOCUMENT_ROOT'] . $_GET['folder'] . '/';
        $targetFile =  str_replace('//','/',$targetPath) .$_FILES['Filedata']['name'];
        move_uploaded_file($tempFile,$targetFile);
    }
?>