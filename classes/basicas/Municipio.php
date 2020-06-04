<?php
    class Municipio{
        private $intId;
        private $strDescricao;
        private $strCodigo;
        private $objRegiaoDesenvolvimento;
        private $objRegional;
        
        public function __construct() {}
        
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
        
        public function SetCodigo($strCodigo){
            $this->strCodigo = $strCodigo;
        }
        
        public function GetCodigo(){
            return $this->strCodigo;
        }
        
        public function SetRegiaoDesenvolvimento($objRegiaoDesenvolvimento){
            $this->objRegiaoDesenvolvimento = $objRegiaoDesenvolvimento;
        }
        
        public function GetRegiaoDesenvolvimento(){
            return $this->objRegiaoDesenvolvimento;
        }
        
        public function SetRegional($objRegional){
            $this->objRegional = $objRegional;
        }
        
        public function GetRegional(){
            return $this->objRegional;
        }
    }
?>