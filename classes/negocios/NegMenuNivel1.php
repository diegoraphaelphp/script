<?php
    class NegMenuNivel1{
        private $objRepoMenuNivel1;
        
        public function __construct() {
            $this->objRepoMenuNivel1 = new RepoMenuNivel1();
        }
        
        public function Consultar($arrStrFiltros){
           
            $arrObjMenusNiveis1 = null;
            $arrStrDados  = $this->objRepoMenuNivel1->Consultar($arrStrFiltros);
                        
            if(count($arrStrDados) > 0){
                
                for($intI=0; $intI<count($arrStrDados); $intI++){
                    $arrObjMenusNiveis1[$intI] = $this->MontarMenuNivel1($arrStrDados[$intI]);
                }
            }
         
            return $arrObjMenusNiveis1;
        }
        
        private function MontarMenuNivel1($arrStrDados){
            $objMenuNivel1 = new MenuNivel1();
     
            $objMenuNivel1->SetId($arrStrDados["MEN_ID"]);
            
            $objMenuNivel1->SetCaminho($arrStrDados["MEN_Caminho"]);
            
            $objModulo = new Modulo();
           
            $objModulo->SetId($arrStrDados["MOD_ID"]);
          
            $objModulo->SetDescricao($arrStrDados["MOD_Descricao"]);
          
            $objMenuNivel1->SetModulo($objModulo);
           
            $objMenuNivel1->SetNome($arrStrDados["MEN_Descricao"]);
        
            $objMenuNivel1->SetStatus($arrStrDados["MEN_Status"]);
        
             
            return $objMenuNivel1;
           
        }
        
        public function Salvar($arrStrDados){
          
            $objMenuNivel1 = new MenuNivel1();
            $objMenuNivel1->SetId($arrStrDados["id"]);
            $objMenuNivel1->SetModulo($arrStrDados["modulo"]);
            $objMenuNivel1->SetCaminho($arrStrDados["caminho"]);  
            $objMenuNivel1->SetNome($arrStrDados["nome"]);             
            $objMenuNivel1->SetStatus($arrStrDados["status"]);
            
            if($objMenuNivel1->GetId() == ""){
             
                
                return $this->objRepoMenuNivel1->Salvar($objMenuNivel1);
            }else{   
              
                return $this->objRepoMenuNivel1->Alterar($objMenuNivel1);
            }
        }
    }
?>
