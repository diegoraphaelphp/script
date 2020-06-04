<?php
    class DbFirebird{
        private $hdlCon   = null;              
        private $strUser  = 'SYSDBA'; 
        private $strPass  = 'masterkey';
        private $strBanco = 'localhost:C:\\Firebird\\NFE.FDB';
        
        public function DbFirebird(){
            try {
                $this->hdlCon = ibase_pconnect($this->strBanco, $this->strUser, $this->strPass);
                
                if($this->hdlCon){
                    return true;
                }else{
                    throw new Exception(mysql_error());
                }
            } catch (Exception $objEx) {                    
                return false;
            }
        }

        public function Select($strSQL){
            try {
                $hdlResult = ibase_query($this->hdlCon, $strSQL);
                
                if($hdlResult){                    
                    $arrStrLinhas = null;                    
                    $intI = 0;                        
                        
                    while($arrStrRes = ibase_fetch_assoc($hdlResult)){
                        $arrStrLinhas[$intI] = $arrStrRes;
                        $intI++;
                    }
                    
                    return $arrStrLinhas;
                }else{
                    throw new Exception(mysql_error());
                }
            } catch (Exception $objEx) {                    
                return null;
            }
        }

        private function Executar($strSQL){
            try {
                if(!ibase_query($this->hdlCon, $strSQL)){
                    return false;
                }else{
                    return true;
                }
            } catch (Exception $objEx) {            
                return false;
            }
        }

        public function Insert($strSQL){
            return $this->Executar($strSQL);
        }

        public function Update($strSQL){
            return $this->Executar($strSQL);
        }

        public function Delete($strSQL){
            return $this->Executar($strSQL);
        }
    }
?>