<?php
    class NegMunicipio{
        private $objRepoMunicipio;
        
        public function __construct() {
            $this->objRepoMunicipio = new RepoMunicipio();
        }
        
        public function Consultar($arrStrFiltros){
            $arrObjMunicipios = null;
            $arrStrDados  = $this->objRepoMunicipio->Consultar($arrStrFiltros);
                        
            if(count($arrStrDados) > 0){
                for($intI=0; $intI<count($arrStrDados); $intI++){
                    $arrObjMunicipios[$intI] = $this->MontarMunicipio($arrStrDados[$intI]);
                }
            }
            
            return $arrObjMunicipios;
        }
        
        private function MontarMunicipio($arrStrDados){
            $objMunicipio = new Municipio();
            
            $objMunicipio->SetId($arrStrDados["MUN_ID"]);
            $objMunicipio->SetDescricao(utf8_encode($arrStrDados["MUN_Descricao"]));
                  
            
            return $objMunicipio;
        }
        
        public function Salvar($arrStrDados){
            $objMunicipio = new Municipio();
            $objMunicipio->SetId($arrStrDados["id"]);
            $objMunicipio->SetDescricao($arrStrDados["descricao"]); 
            if($objMunicipio->GetId() == ""){
                
                return $this->objRepoMunicipio->Salvar($objMunicipio);
            }else{                
                return $this->objRepoMunicipio->Alterar($objMunicipio);
            }
        }
    }
?>
