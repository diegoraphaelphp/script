<?php
    class MenuNivel2{
        private $intID;
        private $objMenuNivel1;
        private $strNome;
        private $strCaminho;
        private $strStatus;
           
        
        public function __construct() {}
        
        public function SetId($intID){
            $this->intID = $intID;
        }
        
        public function GetId(){
            return $this->intID;
        }
        
        public function SetMenuNivel1($objMenuNivel1){
            $this->objMenuNivel1 = $objMenuNivel1;
        }
        
        public function GetMenuNivel1(){
            return $this->objMenuNivel1;
        }
        
        
        public function SetNome($strNome){
            $this->strNome = $strNome;
        }
        
        public function GetNome(){
            return $this->strNome;
        }

        public function SetCaminho($strCaminho){
            $this->strCaminho = $strCaminho;
        }
        
        public function GetCaminho(){
            return $this->strCaminho;
        }
        
        public function SetStatus($strStatus){
            $this->strStatus = $strStatus;
        }
        
        public function GetStatus(){
            return $this->strStatus;
        }
 
    }
?>
