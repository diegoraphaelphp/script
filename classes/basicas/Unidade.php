<?php
    class Unidade{
        private $intID;
        private $strDescricao;
        private $strSigla;
        private $strCodigo;        
        
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
        
        public function SetSigla($strSigla){
            $this->strSigla = $strSigla;
        }
        
        public function GetSigla(){
            return $this->strSigla;
        }
        
        public function SetCodigo($strCodigo){
            $this->strCodigo = $strCodigo;
        }
        
        public function GetCodigo(){
            return $this->strCodigo;
        }
    }
?>
