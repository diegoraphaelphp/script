<?php
    class NegUsuario{
        private $objRepoUsuario;
        
        public function NegUsuario(){
            $this->objRepoUsuario = new RepoUsuario();
        }
        
        /*--------------------------
        * Propósito do Método: Factory do objeto Usuario
        *
        * Utilização dos parâmetros:
        $arrStrDados => linha do array associativo
        return = Retorna o objeto Usuario
        * Autor / Data: André Leitão 12/01/2012
        --------------------------*/
        private function MontarUsuario($arrStrDados){
            $objUsuario = new Usuario();
            
            if(isset($arrStrDados)){
                $objUsuario->SetId($arrStrDados["USU_IDUsuario"]);
                $objUsuario->SetNome($arrStrDados["USU_Nome"]);
                $objUsuario->SetLogin($arrStrDados["USU_Login"]);
                $objUsuario->SetStatus($arrStrDados["USU_Status"]);
            }
            
            return $objUsuario;
        }
        
        /*--------------------------
        * Propósito do Método: Filtra os usuários
        *
        * Utilização dos parâmetros:
        $arrStrDados => array contendo os fitros desejados
        return = Retorna um array de objetos Usuario
        * Autor / Data: André Leitão 12/01/2012
        --------------------------*/
        public function Consultar($arrStrFiltros){
            $arrObjUsuarios = null;
            $arrStrDados  = $this->objRepoUsuario->Consultar($arrStrFiltros); 
            
            if(count($arrStrDados) > 0){
                for($intI=0; $intI<count($arrStrDados); $intI++){
                    $arrObjUsuarios[$intI] = $this->MontarUsuario($arrStrDados[$intI]);
                }
            }
            
            return $arrObjUsuarios;
        }
    }
?>
