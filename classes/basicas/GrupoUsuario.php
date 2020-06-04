<?php
    class GrupoUsuario{
        private $intId;
        private $strDescricao;
        private $strStatus;
        private $strVisualizar;
        private $strIncluir;
        private $strAlterar;
        private $strRemover;
        
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
        
        public function SetStatus($strStatus){
            $this->strStatus = $strStatus;
        }
        
        public function GetStatus(){
            return $this->strStatus;
        }

        public function SetVisualizar($strVisualizar){
            $this->strVisualizar = $strVisualizar;
        }
        
        public function GetVisualizar(){
            return $this->strVisualizar;
        }
        
        public function SetIncluir($strIncluir){
            $this->strIncluir = $strIncluir;
        }
        
        public function GetIncluir(){
            return $this->strIncluir;
        }

        public function SetAlterar($strAlterar){
            $this->strAlterar = $strAlterar;
        }
        
        public function GetAlterar(){
            return $this->strAlterar;
        }

         public function SetRemover($strRemover){
            $this->strRemover = $strRemover;
        }
        
        public function GetRemover(){
            return $this->strRemover;
        }
       
        
        
        
        
    }
?>