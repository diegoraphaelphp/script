<?php
    class NegUnidade{
        private $objRepoUnidade;
        
        public function __construct() {
            $this->objRepoUnidade = new RepoUnidade();
        }
        
        public function Consultar($arrStrFiltros){
            $arrObjUnidades = null;
            $arrStrDados  = $this->objRepoUnidade->Consultar($arrStrFiltros);
                        
            if(count($arrStrDados) > 0){
                for($intI=0; $intI<count($arrStrDados); $intI++){
                    $arrObjUnidades[$intI] = $this->MontarUnidade($arrStrDados[$intI]);
                }
            }
            
            return $arrObjUnidades;
        }
        
        private function MontarUnidade($arrStrDados){
            $objUnidade = new Unidade();
            
            $objUnidade->SetId($arrStrDados["UNI_ID"]);
            $objUnidade->SetDescricao(utf8_encode($arrStrDados["UNI_Descricao"]));
                  
            
            return $objUnidade;
        }
        
        public function Salvar($arrStrDados){
            $objUnidade = new Unidade();
            $objUnidade->SetId($arrStrDados["id"]);
            $objUnidade->SetDescricao($arrStrDados["descricao"]); 
            if($objUnidade->GetId() == ""){
                
                return $this->objRepoUnidade->Salvar($objUnidade);
            }else{                
                return $this->objRepoUnidade->Alterar($objUnidade);
            }
        }
    }
?>
