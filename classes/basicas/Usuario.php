<?php
    class Usuario{
        private $intId;
        private $objGrupoUsuario;
        private $strNome;
        private $strLogin;
        private $strSenha;        
        private $objUnidade;
        private $objMunicipio;
        private $strEmail;
        private $strTelefone;
        private $strDataCadastro;
        private $strStatus;
        
        
        public function __construct() {}
        
        public function SetId($intId){
            $this->intId = $intId;
        }
        
        public function GetId(){
            return $this->intId;
        }

        public function SetGrupoUsuario($objGrupoUsuario){
            $this->objGrupoUsuario = $objGrupoUsuario;
        }
        
        public function GetGrupoUsuario(){
            return $this->objGrupoUsuario;
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
        
        public function SetSenha($strSenha){
            $this->strSenha = $strSenha;
        }
        
        public function GetSenha(){
            return $this->strSenha;
        }
        
        public function SetUnidade($objUnidade){
            $this->objUnidade = $objUnidade;
        }
        
        public function GetUnidade(){
            return $this->objUnidade;
        }
        
        public function SetMunicipio($objMunicipio){
            $this->objMunicipio = $objMunicipio;
        }
        
        public function GetMunicipio(){
            return $this->objMunicipio;
        }

        public function SetEmail($strEmail){
            $this->strEmail = $strEmail;
        }

        public function GetEmail(){
            return $this->strEmail;
        }
        
        public function SetTelefone($strTelefone){
            $this->strTelefone = $strTelefone;
        }
        
        public function GetTelefone(){
            return $this->strTelefone;
        }
        
        public function SetStatus($strStatus){
            $this->strStatus = $strStatus;
        }
        
        public function GetStatus(){
            return $this->strStatus;
        }
        
        public function SetDataCadastro($strDataCadastro){
            $this->strDataCadastro = $strDataCadastro;
        }
        
        public function GetDataCadastro(){
            return $this->strDataCadastro;
        }
        
        

    }
?>
