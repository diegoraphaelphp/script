<?php
    class Endereco{
        private $strLogradouro;
        private $strComplemento;
        private $strBairro;
        private $strCidade;
        private $strUf;
        private $strCep;
        private $strNumero;
        
        public function Endereco(){}
        
        public function SetLogradouro($strLogradouro){
            $this->strLogradouro = $strLogradouro;
        }
        
        public function GetLogradouro(){
            return $this->strLogradouro;
        }
        
        public function SetComplemento($strComplemento){
            $this->strComplemento = $strComplemento;
        }
        
        public function GetComplemento(){
            return $this->strComplemento;
        }
        
        public function SetBairro($strBairro){
            $this->strBairro = $strBairro;
        }
        
        public function GetBairro(){
            return $this->strBairro;
        }
        
        public function SetCidade($strCidade){
            $this->strCidade = $strCidade;
        }
        
        public function GetCidade(){
            return $this->strCidade;
        }
        
        public function SetUf($strUf){
            $this->strUf = $strUf;
        }
        
        public function GetUf(){
            return $this->strUf;
        }
        
        public function SetCep($strCep){
            $this->strCep = $strCep;
        }
        
        public function GetCep(){
            return $this->strCep;
        }
        
        public function SetNumero($strNumero){
            $this->strNumero = $strNumero;
        }
        
        public function GetNumero(){
            return $this->strNumero;
        }
    }
?>