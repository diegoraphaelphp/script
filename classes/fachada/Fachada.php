<?php
    class FachadaSIS{
        public function FachadaSIS(){}
        
        public function ConsultarUsuarios($arrStrFiltros){
            $objNegUsuario = new NegUsuario();
            return $objNegUsuario->Consultar($arrStrFiltros);
        }
    }
?>
