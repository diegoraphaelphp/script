<?php
    // Test CVS
    require_once 'Excel/reader.php';

    // ExcelFile($filename, $encoding);
    $data = new Spreadsheet_Excel_Reader();

    // Set output Encoding.
    $data->setOutputEncoding('CP1251');

    /***
    * if you want you can change 'iconv' to mb_convert_encoding:
    * $data->setUTFEncoder('mb');
    *
    **/

    /***
    * By default rows & cols indeces start with 1
    * For change initial index use:
    * $data->setRowColOffset(0);
    *
    **/



    /***
    *  Some function for formatting output.
    * $data->setDefaultFormat('%.2f');
    * setDefaultFormat - set format for columns with unknown formatting
    *
    * $data->setColumnFormat(4, '%.3f');
    * setColumnFormat - set format for column (apply only to number fields)
    *
    **/

    $data->read('entidades.xls');

    /*
    $data->sheets[0]['numRows'] - count rows
    $data->sheets[0]['numCols'] - count columns
    $data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column

    $data->sheets[0]['cellsInfo'][$i][$j] - extended info about cell

        $data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
            if 'type' == "unknown" - use 'raw' value, because  cell contain value with format '0.00';
        $data->sheets[0]['cellsInfo'][$i][$j]['raw'] = value if cell without format 
        $data->sheets[0]['cellsInfo'][$i][$j]['colspan'] 
        $data->sheets[0]['cellsInfo'][$i][$j]['rowspan'] 
    */
    require_once("../../class/UsuarioException.php");
    require_once("../../class/TecnicoException.php");
    require_once("../../class/Conexao.php");
    require_once("../../lib/util.php");
      
    set_time_limit(0);

    $banco = Conexao::singleton();

    $d = 0;
    for ($j=7;$j<=$data->sheets[0]['numRows'];$j++){        
        $sql = "UPDATE sgc.SGC_ENT_ENTIDADES SET ENT_NumeroVagas = ".$data->sheets[0]['cells'][$j][3]."   WHERE ENT_ID = ".$data->sheets[0]['cells'][$j][1];
        //echo $sql.'<br>';
        $banco->executarQuery($sql);
  }
  exit;
?>