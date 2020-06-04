<?php
    class RepoMenuNivel1{
        private $objDbMysql;
        
        public function __construct() {
            $this->objDbMysql = new DbMysql();
        }
        
        public function Consultar($arrStrFiltros){
            $strSQL   = "SELECT cmmn.MEN_ID, cmmn.MOD_ID, cmmn.MEN_Descricao, cmmn.MEN_Caminho, cmm.MOD_Descricao,";
            $strSQL  .= " cmmn.MEN_Status FROM CAD_MEN_MENUS_NIVEL1 as cmmn ";
            $strSQL  .= " INNER JOIN CAD_MOD_MODULOS AS cmm ON cmm.MOD_ID = cmmn.MOD_ID ";
            $strSQL  .= " WHERE cmmn.MEN_ID IS NOT NULL "; 
            
            if(!empty ($arrStrFiltros["codigo"])){
                $strSQL .= "AND cmmn.MEN_ID = ".$arrStrFiltros["codigo"]." ";
            }

            if(!empty ($arrStrFiltros["nome_modulo"])){
                $strSQL .= "AND cmm.MOD_Descricao LIKE '%".$arrStrFiltros["nome_modulo"]."%' ";
            }
            
            if(!empty ($arrStrFiltros["nome"])){
                $strSQL .= "AND cmmn.MEN_Descricao LIKE '%".$arrStrFiltros["nome"]."%' ";
            }
            
            if(!empty ($arrStrFiltros["status"])){
                $strSQL .= "AND cmmn.MEN_Status = '".$arrStrFiltros["status"]."' ";
            }
            
            $strSQL .= " ORDER BY cmmn.MEN_Descricao ASC";
        
            return $this->objDbMysql->Select($strSQL);
        }
        
        public function Salvar($objMenuNivel1){
            
            $strSQL  = "INSERT INTO CAD_MEN_MENUS_NIVEL1(";
            $strSQL .= " MOD_ID, MEN_Descricao, MEN_Caminho, MEN_Status) VALUES( ";
            $strSQL .= $objMenuNivel1->GetModulo().", ";
            $strSQL .= " '".$objMenuNivel1->GetNome()."', '".$objMenuNivel1->GetCaminho()."', '".$objMenuNivel1->GetStatus()."' )";
          
            return $this->objDbMysql->Insert($strSQL);
        }
        
        public function Alterar($objMenuNivel1){
            $strSQL  = "UPDATE CAD_MEN_MENUS_NIVEL1 SET ";
            $strSQL .= "MOD_ID=".$objMenuNivel1->GetModulo().", MEN_Descricao='".$objMenuNivel1->GetNome()."', ";
            $strSQL .= "MEN_Caminho='".$objMenuNivel1->GetCaminho()."', MEN_Status='".$objMenuNivel1->GetStatus()."' ";
            $strSQL .= "WHERE MEN_ID=".$objMenuNivel1->GetId();
              
            return $this->objDbMysql->Update($strSQL);
        }
    }
?>