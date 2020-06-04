<?php
    class Usuario{
        private $intId;
        private $strNome;
        private $strLogin;
        private $strStatus;
        
        public function __construct() {}
        
        public function SetId($intId){
            $this->intId = $intId;
        }
        
        public function GetId(){
            return $this->intId;
        }
        
        public function SetNome($strNome){
            $this->strNome = $strNome;
        }
        
        public function GetNome(){
            return $this->strNome;
        }
        
        public function SetLogin($strLogin){
            $this->strLogin = $strLogin;
        }
        
        public function GetLogin(){
            return $this->strLogin;
        }
        
        public function SetStatus($strStatus){
            $this->strStatus = $strStatus;
        }
        
        public function GetStatus(){
            return $this->strStatus;
        }
    }
?>
