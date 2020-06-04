<?php
    class NegMenuNivel3{
        private $objRepoMenuNivel3;
        
        public function __construct() {
            $this->objRepoMenuNivel3 = new RepoMenuNivel3();
        }
        
        public function Consultar($arrStrFiltros){
           
            $arrObjMenusNiveis3 = null;
            $arrStrDados  = $this->objRepoMenuNivel3->Consultar($arrStrFiltros);
                        
            if(count($arrStrDados) > 0){
                
                for($intI=0; $intI<count($arrStrDados); $intI++){
                    $arrObjMenusNiveis3[$intI] = $this->MontarMenuNivel3($arrStrDados[$intI]);
                }
            }
         
            return $arrObjMenusNiveis3;
        }
        
        private function MontarMenuNivel3($arrStrDados){
            $objMenuNivel3 = new MenuNivel3();
     
            $objMenuNivel3->SetId($arrStrDados["MEN3_ID"]);
            
            $objMenuNivel2 = new MenuNivel2();
            $objMenuNivel2->SetId($arrStrDados["MEN2_ID"]);
            $objMenuNivel2->SetNome($arrStrDados["MEN2_Descricao"]);
            
            $objMenuNivel3->SetMenuNivel2($objMenuNivel2);
            
            $objMenuNivel3->SetCaminho($arrStrDados["MEN3_Caminho"]);
 
            $objMenuNivel3->SetNome($arrStrDados["MEN3_Descricao"]);
        
            $objMenuNivel3->SetStatus($arrStrDados["MEN3_Status"]);
        
         
            return $objMenuNivel3;
           
        }
        
        public function Salvar($arrStrDados){
          
            $objMenuNivel3 = new MenuNivel3();
            $objMenuNivel3->SetId($arrStrDados["id"]);
            
            $objMenuNivel3->SetMenuNivel2($arrStrDados["menu2"]);
            $objMenuNivel3->SetCaminho($arrStrDados["caminho"]);  
            $objMenuNivel3->SetNome($arrStrDados["nome"]);             
            $objMenuNivel3->SetStatus($arrStrDados["status"]);
            
            if($objMenuNivel3->GetId() == ""){
             
                
                return $this->objRepoMenuNivel3->Salvar($objMenuNivel3);
            }else{   
              
                return $this->objRepoMenuNivel3->Alterar($objMenuNivel3);
            }
        }
    }
?>
