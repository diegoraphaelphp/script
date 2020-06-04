<?php
    class NegGrupoUsuario{
        private $objRepoGrupoUsuario;
        
        public function __construct() {
            $this->objRepoGrupoUsuario = new RepoGrupoUsuario();
        }
        
        public function Consultar($arrStrFiltros){
            $arrObjGruposUsuarios = null;
            $arrStrDados  = $this->objRepoGrupoUsuario->Consultar($arrStrFiltros);
                        
            if(count($arrStrDados) > 0){
                for($intI=0; $intI<count($arrStrDados); $intI++){
                    $arrObjGruposUsuarios[$intI] = $this->MontarGrupoUsuario($arrStrDados[$intI]);
                }
            }
            
            return $arrObjGruposUsuarios;
        }
        
        private function MontarGrupoUsuario($arrStrDados){
            $objGrupoUsuario = new GrupoUsuario();
            
            $objGrupoUsuario->SetId($arrStrDados["GRU_ID"]);
            $objGrupoUsuario->SetDescricao($arrStrDados["GRU_Descricao"]);
            $objGrupoUsuario->SetStatus($arrStrDados["GRU_Status"]);
            $objGrupoUsuario->SetVisualizar($arrStrDados["GRU_PermissaoVisualizar"]);
            $objGrupoUsuario->SetIncluir($arrStrDados["GRU_PermissaoIncluir"]);
            $objGrupoUsuario->SetAlterar($arrStrDados["GRU_PermissaoAlterar"]);
            $objGrupoUsuario->SetRemover($arrStrDados["GRU_PermissaoRemover"]);
            
            return $objGrupoUsuario;
        }
        
        public function Salvar($arrStrDados){
            $objGrupoUsuario = new GrupoUsuario();
            $objGrupoUsuario->SetId($arrStrDados["id"]);
            $objGrupoUsuario->SetDescricao($arrStrDados["descricao"]);
            $objGrupoUsuario->SetStatus($arrStrDados["status"]);
            $objGrupoUsuario->SetVisualizar($arrStrDados["visualizar"]);
            $objGrupoUsuario->SetIncluir($arrStrDados["incluir"]);
            $objGrupoUsuario->SetAlterar($arrStrDados["alterar"]);
            $objGrupoUsuario->SetRemover($arrStrDados["remover"]);
            
            if($objGrupoUsuario->GetId() == ""){
             
                
                return $this->objRepoGrupoUsuario->Salvar($objGrupoUsuario);
            }else{   
              
                return $this->objRepoGrupoUsuario->Alterar($objGrupoUsuario);
            }
        }
    }
?>
