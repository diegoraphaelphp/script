<?php
    class NegMenuNivel2{
        private $objRepoMenuNivel2;
        
        public function __construct() {
            $this->objRepoMenuNivel2 = new RepoMenuNivel2();
        }
        
        public function Consultar($arrStrFiltros){
           
            $arrObjMenusNiveis2 = null;
            $arrStrDados  = $this->objRepoMenuNivel2->Consultar($arrStrFiltros);
                        
            if(count($arrStrDados) > 0){
                
                for($intI=0; $intI<count($arrStrDados); $intI++){
                    $arrObjMenusNiveis2[$intI] = $this->MontarMenuNivel2($arrStrDados[$intI]);
                }
            }
         
            return $arrObjMenusNiveis2;
        }
        
        private function MontarMenuNivel2($arrStrDados){
            $objMenuNivel2 = new MenuNivel2();
     
            $objMenuNivel2->SetId($arrStrDados["MEN2_ID"]);
            
            $objMenuNivel1 = new MenuNivel1();
            $objMenuNivel1->SetId($arrStrDados["MEN_ID"]);
            $objMenuNivel1->SetNome($arrStrDados["MEN_Descricao"]);
            
            $objMenuNivel2->SetMenuNivel1($objMenuNivel1);
            
            $objMenuNivel2->SetCaminho($arrStrDados["MEN2_Caminho"]);
 
            $objMenuNivel2->SetNome($arrStrDados["MEN2_Descricao"]);
        
            $objMenuNivel2->SetStatus($arrStrDados["MEN2_Status"]);
        
         
            return $objMenuNivel2;
           
        }
        
        public function Salvar($arrStrDados){
          
            $objMenuNivel2 = new MenuNivel2();
            $objMenuNivel2->SetId($arrStrDados["id"]);
            
            $objMenuNivel2->SetMenuNivel1($arrStrDados["menu1"]);
            $objMenuNivel2->SetCaminho($arrStrDados["caminho"]);  
            $objMenuNivel2->SetNome($arrStrDados["nome"]);             
            $objMenuNivel2->SetStatus($arrStrDados["status"]);
            
            if($objMenuNivel2->GetId() == ""){
             
                
                return $this->objRepoMenuNivel2->Salvar($objMenuNivel2);
            }else{   
              
                return $this->objRepoMenuNivel2->Alterar($objMenuNivel2);
            }
        }
    }
?>
