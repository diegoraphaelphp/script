<?php
    class Fachada{
        public function Fachada(){}
        
        public function SalvarGrupoUsuario($arrStrDados){
            $objNegGrupoUsuario = new NegGrupoUsuario();
            return $objNegGrupoUsuario->Salvar($arrStrDados);
        }
        public function ConsultarGrupoUsuario($arrStrFiltros){
            $objNegGrupoUsuario = new NegGrupoUsuario();
            return $objNegGrupoUsuario->Consultar($arrStrFiltros);
        }

        public function SalvarModulo($arrStrDados){
            $objNegModulo = new NegModulo();
            return $objNegModulo->Salvar($arrStrDados);
        }
        public function ConsultarModulo($arrStrFiltros){
            $objNegModulo = new NegModulo();
            return $objNegModulo->Consultar($arrStrFiltros);
        }
        public function ConsultarMunicipio($arrStrFiltros){
            $objNegMunicipio = new NegMunicipio();
            return $objNegMunicipio->Consultar($arrStrFiltros);
        }
        
        public function SalvarUsuario($arrStrDados){
            $objNegUsuario= new NegUsuario();
            return $objNegUsuario->Salvar($arrStrDados);
        }   

        public function ConsultarUnidade($arrStrFiltros){
            $objNegUnidade = new NegUnidade();
            return $objNegUnidade->Consultar($arrStrFiltros);
        }        
        public function ConsultarUsuario($arrStrFiltros){
            $objNegUsuario = new NegUsuario();
            return $objNegUsuario->Consultar($arrStrFiltros);
        }
        
        public function ConsultarParametro($arrStrFiltros){
            $objNegParametro = new NegParametro();
            return $objNegParametro->Consultar($arrStrFiltros);
        }
        
        public function AlterarSenhaUsuario($arrStrDados){
            $objNegUsuario = new NegUsuario();
            return $objNegUsuario->AlterarSenha($arrStrDados);
        }

        public function ConsultarMenuNivel1($arrStrFiltros){
            $objNegMenuNivel1 = new NegMenuNivel1();
            return $objNegMenuNivel1->Consultar($arrStrFiltros);
        }
        public function SalvarMenuNivel1($arrStrDados){
            $objNegMenuNivel1= new NegMenuNivel1();
            return $objNegMenuNivel1->Salvar($arrStrDados);
        }   
        public function ConsultarMenuNivel2($arrStrFiltros){
            $objNegMenuNivel2 = new NegMenuNivel2();
            return $objNegMenuNivel2->Consultar($arrStrFiltros);
        }
        public function SalvarMenuNivel2($arrStrDados){
            $objNegMenuNivel2= new NegMenuNivel2();
            return $objNegMenuNivel2->Salvar($arrStrDados);
        } 
        public function ConsultarMenuNivel3($arrStrFiltros){
            $objNegMenuNivel3 = new NegMenuNivel3();
            return $objNegMenuNivel3->Consultar($arrStrFiltros);
        }
        public function SalvarMenuNivel3($arrStrDados){
            $objNegMenuNivel3= new NegMenuNivel3();
            return $objNegMenuNivel3->Salvar($arrStrDados);
        }         
    }
?>
