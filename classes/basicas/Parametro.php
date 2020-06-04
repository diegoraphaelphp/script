<?php
    class Parametro{
        private $intId;
        private $strParametro;
        private $strValor;
        private $strTipo;
        
        public function __construct() {}
        
        public function SetId($intId){
            $this->intId = $intId;
        }
        
        public function GetId(){
            return $this->intId;
        }
        
        public function SetParametro($strParametro){
            $this->strParametro = $strParametro;
        }
        
        public function GetParametro(){
            return $this->strParametro;
        }
        
        public function SetValor($strValor){
            $this->strValor = $strValor;
        }
        
        public function GetValor(){
            return $this->strValor;
        }
        
        public function SetTipo($strTipo){
            $this->strTipo = $strTipo;
        }
        
        public function GetTipo(){
            return $this->strTipo;
        }
    }
?>
