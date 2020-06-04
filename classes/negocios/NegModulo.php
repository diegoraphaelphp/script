<?php
    class NegModulo{
        private $objRepoModulo;
        
        public function __construct() {
            $this->objRepoModulo = new RepoModulo();
        }
        
        public function Consultar($arrStrFiltros){
            $arrObjModulos = null;
            $arrStrDados  = $this->objRepoModulo->Consultar($arrStrFiltros);
                        
            if(count($arrStrDados) > 0){
                for($intI=0; $intI<count($arrStrDados); $intI++){
                    $arrObjModulos[$intI] = $this->MontarModulo($arrStrDados[$intI]);
                }
            }
            
            return $arrObjModulos;
        }
        
        private function MontarModulo($arrStrDados){
            $objModulo = new Modulo();
            
            $objModulo->SetId($arrStrDados["MOD_ID"]);
            $objModulo->SetDescricao($arrStrDados["MOD_Descricao"]);
            $objModulo->SetStatus($arrStrDados["MOD_Status"]);
             
            return $objModulo;
        }
        
        public function Salvar($arrStrDados){
          
            $objModulo = new Modulo();
            $objModulo->SetId($arrStrDados["id"]);
            $objModulo->SetDescricao($arrStrDados["descricao"]);
            $objModulo->SetStatus($arrStrDados["status"]);
            
            
            if($objModulo->GetId() == ""){
             
                
                return $this->objRepoModulo->Salvar($objModulo);
            }else{   
              
                return $this->objRepoModulo->Alterar($objModulo);
            }
        }
    }
?>
