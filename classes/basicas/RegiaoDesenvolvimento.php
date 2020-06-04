<?php
    class RegiaoDesenvolvimento{
        private $intId;
        private $strDescricao;
        private $strStatus;
        
        public function SetId($intId){
            $this->intId = $intId;
        }
        
        public function GetId(){
            return $this->intId;
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
