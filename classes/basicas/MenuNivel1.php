<?php
    class MenuNivel1{
        private $intID;
        private $objModulo;
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
        
        public function SetModulo($objModulo){
            $this->objModulo = $objModulo;
        }
        
        public function GetModulo(){
            return $this->objModulo;
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
