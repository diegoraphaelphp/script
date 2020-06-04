<?php
    class NegUsuario{
        private $objRepoUsuario;
        
        public function NegUsuario(){
            $this->objRepoUsuario = new RepoUsuario();
        }
        
        /*--------------------------
        * Prop�sito do M�todo: Factory do objeto Usuario
        *
        * Utiliza��o dos par�metros:
        $arrStrDados => linha do array associativo
        return = Retorna o objeto Usuario
        * Autor / Data: Andr� Leit�o 12/01/2012
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
        * Prop�sito do M�todo: Filtra os usu�rios
        *
        * Utiliza��o dos par�metros:
        $arrStrDados => array contendo os fitros desejados
        return = Retorna um array de objetos Usuario
        * Autor / Data: Andr� Leit�o 12/01/2012
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
