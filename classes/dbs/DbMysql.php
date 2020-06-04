<?php
    class DbMysql{
        private $hdlCon   = null;
        private $hdlDb    = null;
        private $strHost  = 'localhost'; 
        private $strUser  = 'root'; 
        private $strPass  = 'raphaelrd';
        private $strBanco = 'pam';
        
        public function DbMySql(){
            try {
                $this->hdlCon = mysql_connect($this->strHost, $this->strUser, $this->strPass);
                
                if($this->hdlCon){
                    $this->hdlDb = mysql_select_db($this->strBanco, $this->hdlCon);
                    
                    if(!$this->hdlDb){
                        throw new Exception(mysql_error());
                    }else{
                        return true;
                    }
                }else{
                    throw new Exception(mysql_error());
                }
            } catch (Exception $objEx) {                    
                return false;
            }
        }

        public function Select($strSQL){
            try {
                $hdlResult = mysql_query($strSQL);
                
                if($hdlResult){
                    $intNumeroLinhas = mysql_num_rows($hdlResult);
                    $arrStrLinhas    = null;
                    
                    if($intNumeroLinhas > 0){
                        $intI = 0;                        
                        
                        while($arrStrRes = mysql_fetch_assoc($hdlResult)){
                            $arrStrLinhas[$intI] = $arrStrRes;
                            $intI++;
                        }
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
                if(!mysql_query($strSQL)){
                    throw new Exception(mysql_error());
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