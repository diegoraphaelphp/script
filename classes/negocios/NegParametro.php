<?php
    class NegParametro{
        private $objRepoParametro;
        
        public function NegParametro(){
            $this->objRepoParametro = new RepoParametro();            
        }
        
        public function Consultar($arrStrFiltros){
            $arrObjParametros = null;
            $arrStrDados      = $this->objRepoParametro->Consultar($arrStrFiltros); 
            
            if(count($arrStrDados) > 0){
                for($intI=0; $intI<count($arrStrDados); $intI++){
                    $arrObjParametros[$intI] = $this->MontarParametro($arrStrDados[$intI]);
                }
            }
            
            return $arrObjParametros;
        }
        
        public function MontarParametro($arrStrDados){
            $objParametro = new Parametro();
            $objParametro->SetId($arrStrDados["PAR_ID"]);
            $objParametro->SetParametro($arrStrDados["PAR_Parametro"]);            
            $objParametro->SetValor($arrStrDados["PAR_Valor"]);
            $objParametro->SetTipo($arrStrDados["PAR_Tipo"]);
            
            return $objParametro;
        }
    }
?>