<?php
    class NegUsuario{
        private $objRepoUsuario;
        
        public function __construct(){
            $this->objRepoUsuario = new RepoUsuario();            
        }
        
        public function Consultar($arrStrFiltros){
            $arrObjUsuarios = null;             
            $arrStrDados    = $this->objRepoUsuario->Consultar($arrStrFiltros);
                          
            if(count($arrStrDados) > 0){              
                for($intI=0; $intI<count($arrStrDados); $intI++){
                    $arrObjUsuarios[$intI] = $this->MontarUsuario($arrStrDados[$intI]);
                }
            }
            
            return $arrObjUsuarios;
        }
        
        public function MontarUsuario($arrStrDados){
            $objUsuario = new Usuario();
            $objUsuario->SetId($arrStrDados["USU_ID"]);
            $objUsuario->SetNome($arrStrDados["USU_Nome"]);            
            $objUsuario->SetLogin($arrStrDados["USU_Login"]);
            $objUsuario->SetSenha($arrStrDados["USU_Senha"]);
            $objUsuario->SetTelefone($arrStrDados["USU_Telefone"]);
            $objUsuario->SetEmail($arrStrDados["USU_Email"]);
            // unidade
            $objUnidade = new Unidade();
            $objUnidade->SetId($arrStrDados["UNI_ID"]);
            $objUnidade->SetDescricao($arrStrDados["UNI_Descricao"]);
            $objUsuario->SetUnidade($objUnidade);
            
            // municipio
            $objMunicipio = new Municipio();
            $objMunicipio->SetId($arrStrDados["MUN_ID"]);
            $objMunicipio->SetDescricao($arrStrDados["MUN_Descricao"]);
            $objUsuario->SetMunicipio($objMunicipio);
            
            $objGrupoUsuario = new GrupoUsuario();
            $objGrupoUsuario->SetId($arrStrDados["GRU_ID"]);
            $objGrupoUsuario->SetDescricao($arrStrDados["GRU_Descricao"]);
            $objUsuario->SetGrupoUsuario($objGrupoUsuario);
            
            $objUsuario->SetStatus($arrStrDados["USU_Status"]);
            
             
            
            return $objUsuario;
        }
        
        public function Salvar($arrStrDados){
            $objUsuario = new Usuario();
            
            $objUsuario->SetId($arrStrDados["id"]);
            
            $objUsuario->SetNome($arrStrDados["nome"]); 
            $objUsuario->SetLogin($arrStrDados["login"]); 
            $objUsuario->SetSenha($arrStrDados["senha"]);
            $objUsuario->SetEmail($arrStrDados["email"]);
            $objUsuario->SetTelefone($arrStrDados["telefone"]);
             
            // grupos do Usuario
            $objGrupoUsuario = new GrupoUsuario();
            $objGrupoUsuario->SetId($arrStrDados["grupo"]);
            $objUsuario->SetGrupoUsuario($objGrupoUsuario);
            
            // Municipios
            $objMunicipio = new Municipio();
            $objMunicipio->SetId($arrStrDados["municipio"]);
            $objUsuario->SetMunicipio($objMunicipio);
            
            // Unidades
            $objUnidade = new Unidade();
            $objUnidade->SetId($arrStrDados["unidade"]);
            $objUsuario->SetUnidade($objUnidade);
           
            $objUsuario->SetStatus($arrStrDados["status"]);          
             
            if($arrStrDados["id"] == ""){
                return $this->objRepoUsuario->Salvar($objUsuario);
            }else{
                return $this->objRepoUsuario->Alterar($objUsuario);
            }
        }
        
        public function AlterarSenha($arrStrDados){
            $objUsuario = new Usuario();
            
            $objUsuario->SetId($arrStrDados["id"]);            
            $objUsuario->SetSenha($arrStrDados["senha"]);
             
            return $this->objRepoUsuario->AlterarSenha($objUsuario);
        }
    }
?>