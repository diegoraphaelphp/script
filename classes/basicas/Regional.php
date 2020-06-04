<?php
    class Regional{
        private $intID;
        private $strDescricao;
        private $strStatus;
        
        public function __construct() {}
        
        public function SetId($intID){
            $this->intID = $intID;
        }
        
        public function GetId(){
            return $this->intID;
        }
        
        public function SetDescricao($strDescricao){
            $this->strDescricao = $strDescricao;
        }
        
        public function GetDescricao(){
            return $this->strDescricao;
        }
        
        public function SetStatus($strStatus){
            $this->strStatus = $strStatus;
        }
        
        public function GetStatus(){
            return $this->strStatus;
        }
    }
?>