<?php
    class MenuNivel3{
        private $intID;
        private $objMenuNivel2;
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
        
        public function SetMenuNivel2($objMenuNivel2){
            $this->objMenuNivel2 = $objMenuNivel2;
        }
        
        public function GetMenuNivel2(){
            return $this->objMenuNivel2;
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
