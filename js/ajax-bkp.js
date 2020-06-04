function trim(campo){
  var retValue = campo;
  var ch 	   = retValue.substring(0, 1);
   while (ch == " ") {
      retValue = retValue.substring(1, retValue.length);
      ch = retValue.substring(0, 1);
   }
   ch = retValue.substring(retValue.length-1, retValue.length);
   while (ch == " ") {
      retValue = retValue.substring(0, retValue.length-1);
      ch = retValue.substring(retValue.length-1, retValue.length);
   }
   while (retValue.indexOf("  ") != -1) { 
      retValue = retValue.substring(0, retValue.indexOf("  ")) + retValue.substring(retValue.indexOf("  ")+1, retValue.length);
   }
   return retValue;
}

function DiegoAjax(url) {
	var ajax = false;	
	if (window.XMLHttpRequest) { // Mozilla, Safari,...	
	    ajax = new XMLHttpRequest();
		if (ajax.overrideMimeType) {
	    	if (url.indexOf(".xml") != -1) {
		        ajax.overrideMimeType('text/xml');
			} else {
		        ajax.overrideMimeType('text/html');
			}
		}
	}else if (window.ActiveXObject) { // IE
	    try {	    	
	        ajax = new ActiveXObject("Msxml2.XMLHTTP");	        
	    } catch (e) {	    	
	        try {	        	
	            ajax = new ActiveXObject("Microsoft.XMLHTTP");	            
	        } catch (e) {}
	    }
	}
	if (!ajax) {
	    alert('Seu browser não suporta Ajax.');
	}
	return ajax;
}

function alertResponse(obj) {
	var ajax = obj;
	if (ajax.readyState == 4) {
		// tudo bem, a respsota foi recebida
		if (ajax.status == 200) {
			// perfect!
			alert(ajax.responseText);
		} else {
			alert('Aconteceu um problema na requesição.');
		}
	} else {
		// ainda não fez a leitura	
	}
	
}

/*
Até este trecho da biblioteca o conteúdo foi RETIRADO e MODIFICADO do site http://developer.mozilla.org
Artigo "AJAX: Como começar"
Autor: Vários
*/

//Fila de conexões
fila=[];
ifila=0;

//Carrega via XMLHTTP a url recebida e coloca seu valor no objeto com o id recebido
function getResponse(objectReturnId,url,classname,loadphrase) {
	//Carregando...
	var recebe = document.getElementById(objectReturnId);
	recebe.innerHTML = loadphrase;
	
    //Adiciona à fila
    fila[fila.length]=[objectReturnId,url];
    //Se não há conexões pendentes, executa
    if((ifila+1)==fila.length)getResponseRun();	
}

function getResponseRun() {
	var ajax = DiegoAjax(fila[ifila][1]);
    //Abre a conexão
    ajax.open("GET",fila[ifila][1],true);
    //Função para tratamento do retorno
    ajax.onreadystatechange=function() {
        if (ajax.readyState==4){
            //Mostra o HTML recebido
			texto = ajax.responseText;
			texto = texto.replace(/\+/g," ");
			texto = unescape(texto);
			recebe= document.getElementById(fila[ifila][0]);
			recebe.innerHTML=texto;
			//Roda o próximo
            ifila++;
            if(ifila<fila.length)setTimeout("getResponseRun()",20)			
        }
    }
    //Executa
    ajax.send(null);	
}

function ajaxPost(url,form) {
	DiegoAjax(url);
	ajax.onreadystatechange = alertResponse;
	// Fazendo a requesição
	ajax.open('POST', url, true);
	// Configura a aplicação para o charset=iso-8859-1 e para identificar o envio via POST
	ajax.setRequestHeader('Content-Type',"application/x-www-form-urlencoded; charset=iso-8859-1");
	// Resolução do problema de Cache
	ajax.setRequestHeader("Cache-Control","no-store, no-cache, must-revalidate");
	ajax.setRequestHeader("Cache-Control","post-check=0, pre-check=0");
	ajax.setRequestHeader("Pragma","no-cache");
	// Enviando
	ajax.send(getDadosForm(form));
}

function getDadosForm(form) {
	var frm = document.getElementById(form);
	var dados = "";
	for (var i = 0; i < frm.elements.length; i++) {
		dados += "&" + frm.elements[i].id + "=" + frm.elements[i].value;
	}
	return dados;
}



/*---------------------------------------------------------------------------------------------*/

/*
Problema com os acentos na RECUPERAÇÃO da página do servidor via AJAX?
Para resolver esse problema basta indicar o charset correto
no início do seu "script server side", com apenas 1 (uma) linha de código passadas abaixo. 

Em ASP:	<% Response.Charset="ISO-8859-1" %>
Em PHP: <?php header("Content-Type: text/html; charset=ISO-8859-1",true) ?>
Em JSP: <%@ page contentType="text/html; charset=ISO-8859-1" %>
Em HTML: <META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

NOTA: Alguns BD's, guardam seus dados apenas em UTF-8, e aí ocorre a perda de caracteres

Saída caso o erro ainda persista:

Na linguagem de programação PHP pelo menos:
rawurlencode - http://br2.php.net/rawurlencode (para codificar)
rawurldecode - http://br2.php.net/rawurldecode (para decodificar) 

Exemplo:
if (isset($_GET["texto"])) { 
    $texto = $_GET["texto"]; 
    echo "<b>Texto codificado: </b>" . rawurlencode($texto) . "<br>"; 
    echo "<b>Texto decodificado: </b>" . rawurldecode($texto) . "<br><br>"; 
}
*/

// Funções Java Script para codificar e decodificar dados enviados

/* 
Exemplo:
... 
    xmlhttp.open("GET", "teste.php?texto=" + url_encode("teste... éé"), true); 
... 
	if (xmlhttp.status == 200) { 
	   document.getElementById("texto").innerHTML = url_decode(xmlhttp.responseText); 
	}
...
*/

// url_encode version 1.0 
function url_encode(str) { 
    var hex_chars = "0123456789ABCDEF"; 
    var noEncode = /^([a-zA-Z0-9\_\-\.])$/; 
    var n, strCode, hex1, hex2, strEncode = ""; 

    for(n = 0; n < str.length; n++) { 
        if (noEncode.test(str.charAt(n))) { 
            strEncode += str.charAt(n); 
        } else { 
            strCode = str.charCodeAt(n); 
            hex1 = hex_chars.charAt(Math.floor(strCode / 16)); 
            hex2 = hex_chars.charAt(strCode % 16); 
            strEncode += "%" + (hex1 + hex2); 
        } 
    } 
    return strEncode; 
} 

// url_decode version 1.0 
function url_decode(str) { 
    var n, strCode, strDecode = ""; 

    for (n = 0; n < str.length; n++) { 
        if (str.charAt(n) == "%") { 
            strCode = str.charAt(n + 1) + str.charAt(n + 2); 
            strDecode += String.fromCharCode(parseInt(strCode, 16)); 
            n += 2; 
        } else { 
            strDecode += str.charAt(n); 
        } 
    }
    return strDecode; 
}

function pega_valor(valor, tipo){
	
	var topo = "<span style='font-family:Verdana, Arial, Helvetica, sans-serif;font-size:11px;font-weight:bold;color:#000066;font-style:normal;text-decoration:none;'><img src='../img/ajax-loader.gif' title='Carregando...' />&nbsp;Carregando...</span>";
		
	if (tipo == "incluirItensCompra"){
		
	}else if (tipo == "pega_area_pat"){
		
	    getResponse("pegaAreaPat","../ajax/ajax_area_pat.php?id="+valor+"&acao="+document.getElementById("areaos").value+"&area="+document.getElementById("idarea").value,"", topo);
		
	}else if (tipo == "pega_area"){
		
	    getResponse("pegaArea","ajax/ajax_area.php?id="+valor,"",topo);
		
    }else if (tipo == "pega_area2"){
		
  	   getResponse("pegaArea2","../ajax/ajax_area2.php?id="+valor,"", topo);	
	   
    }else if (tipo == "validausuario"){
		
       getResponse("pega_validausuario","../ajax/ajax_validausuario.php?id="+valor+"&id2="+document.getElementById("id").value,"", topo);	  
	   
    }else if (tipo == "validaconvenio"){
		
       getResponse("pega_validaconvenio","../ajax/ajax_validaconvenio.php?id="+valor,"", topo);	    
	   
	}else if (tipo == "exibirbtnconv"){
		
       getResponse("pega_exibebtnconv","../ajax/ajax_exibebtnconv.php?id="+valor+"&cod="+document.getElementById("id2").value,"", topo);
	   
    }else if (tipo == "exibirbtnusu"){
		
       getResponse("pega_exibebtnusu","../ajax/ajax_exibebtnusu.php?id="+valor+"&id2="+document.getElementById("id").value,"", topo);
	   
    }else if (tipo == "addAtividades"){ 
	
       getResponse("incluirAtv","../lib/Fachada.php?acao="+document.getElementById('acao').value+"&id="+document.getElementById("id").value+"&nome="+document.getElementById('nome').value+"&status="+document.getElementById('status').value+"&und="+document.getElementById('und').value+"&tipo="+document.getElementById('tipo').value,"", topo);
	   
    }else if (tipo == "excAtividades"){
		
    	getResponse("excluirAtv", document.getElementById('acaoexc').value+"&id="+valor,"", topo);
		
	}else if (tipo == "excEmpenhados"){
		
    	getResponse("excluirEmp","../lib/Fachada.php?acao="+document.getElementById('acaoexc').value+"&id="+valor,"", topo);
		
		getsfiltro1('lista_prod_empenhados.php', '', 'codtp', 'DESC');
		getsfiltro1('lista_prod_empenhados.php', '', 'codtp', 'DESC');		
		
	}else if (tipo == "cancelarEmpenhados"){
		
    	getResponse("cancelarEmp","../lib/Fachada.php?acao="+document.getElementById('acaocancel').value+"&id="+valor,"", topo);
		
		getsfiltro1('lista_prod_empenhados.php', '', 'codtp', 'DESC');
		getsfiltro1('lista_prod_empenhados.php', '', 'codtp', 'DESC');		
		
    }else if (tipo == "excAtividades2"){
		
    	getResponse("excluirAtv","../lib/Fachada.php?acao="+document.getElementById('excluirATV').value+"&id="+valor,"", topo);
		
    }else if (tipo == "pegaAtividades"){
		
    	getResponse("pega_atividades","../ajax/ajax_exibe_atividades.php?id="+valor,"", topo);
		
    }else if (tipo == "excItemPlanoAnual"){

    	getResponse("excluirPA","../lib/Fachada.php?acao="+document.getElementById('acaoexc2').value+"&id="+valor,"", topo);
		
		document.getElementById('resultado').innerHTML = "";
		
		getsfiltroPA('../consultas/lista_elaboracao.php', '', 'codtp', 'DESC');
		getsfiltroPA('../consultas/lista_elaboracao.php', '', 'codtp', 'DESC');		

    }else if (tipo == "excItemPlanoAnual2"){
		
    	getResponse("excluirPA","../lib/Fachada.php?acao="+document.getElementById('acaoexc').value+"&id="+valor,"", topo);
        
  //      document.getElementById("resultado").innerHTML = "";
        //alert("Registro excluído com sucesso.");
        
        //getsfiltroPA('../consultas/lista_elaboracao.php', '', 'codtp', 'DESC');
        //getsfiltroPA('../consultas/lista_elaboracao.php', '', 'codtp', 'DESC');
		
	}else if (tipo == "pega_familia"){
		
		  getResponse("pegaFamilia","../ajax/ajax_form_familia.php?id="+valor+"&muni="+document.getElementById("muni").value,"", topo);   	
		  
    }else if (tipo == "pega_linha"){
		
    	getResponse("pegaLinha","../ajax/ajax_linha.php?id="+valor+"&linha="+document.getElementById("lin").value,"", topo);
		
    }else if (tipo == "pega_ident"){
		
    	getResponse("pegaIdent","../ajax/ajax_ident.php?id="+valor,"", topo);
		
    }else if (tipo == "pega_valida_ident"){
		
    	getResponse("pegaValidaIdent","../ajax/ajax_valida_ident.php?id="+valor,"", topo);
		
    }else if (tipo == "pega_tipogrupo"){		
	
    	getResponse("exibeTipoGrupo","../ajax/ajax_tipogrupo.php?id="+valor,"", topo);
		
    }else if (tipo == "pega_solicitante"){
		
    	getResponse("pegaSolicitante","../ajax/ajax_solicitante.php?id="+valor+"&usu="+document.getElementById("idusu").value,"", topo);
		
    }else if (tipo == "pega_solicitante_muda"){
		
    	getResponse("pegaSolicitanteMuda","../ajax/ajax_solicitante_muda.php?id="+valor,"", topo);			
		
	}else if (tipo == "pega_convenio"){
		
    	getResponse("pegaConvenio","../ajax/ajax_convenio.php?id="+valor,"", topo);
		
	}else if (tipo == "pega_compra_obs"){
		
		if (trim(document.getElementById("unidade").value) == ""){
			
			alert("Unidade precisa ser informado.");
			document.getElementById("unidade").focus();
		
		}else if (trim(document.getElementById("proce").value) == ""){
			
			alert("Processo precisa ser informado.");
			document.getElementById("proce").focus();			
			
		}else if (trim(document.getElementById("obs").value) == ""){
			
			alert("Observação precisa ser informado.");
            document.getElementById("obs").focus();

		}else{

			getResponse("pegaCompraObs","../ajax/ajax_compra_obs.php?id="+document.getElementById("idsol").value+"&obs="+document.getElementById("obs").value+"&usu="+document.getElementById("usu").value+"&proce="+document.getElementById("proce").value+"&unidade="+document.getElementById("unidade").value+"&usuario="+document.getElementById("usuario").value,"", topo);
            document.getElementById("btn").click();
//			getsfiltroCOMPRAS('lista_compras.php', '', 'codtp', '');
		}

	}else if (tipo == "pega_id_clifor"){

		getResponse("pegaValidaCLIFOR","../ajax/ajax_valida_btnclifor.php?id="+document.getElementById("id_nome").value,"", topo);
		
	}else if (tipo == "pega_id_clifor2"){

		getResponse("pegaValidaCLIFOR","../ajax/ajax_valida_btnclifor2.php?id="+document.getElementById("id_nome").value,"", topo);
		
	}else if (tipo == "pega_id_item"){

		getResponse("pegaValidaITEM","../ajax/ajax_valida_btnitem.php?id="+document.getElementById("id_nome").value,"", topo);

	}else if (tipo == "pega_local_rd"){
		
		getResponse("pegaLocalRD","../ajax/ajax_local_rd.php?id="+valor+"&idlocal="+document.getElementById("idlocal").value,"", topo);	
		
	}else if (tipo == "pega_municipio_rd"){
		getResponse("pegaMunicipioRD","../ajax/ajax_municipio_rd.php?id="+valor+"&idlocal="+document.getElementById("idlocal").value,"", topo);	
		
	}else if (tipo == "pega_prog_proj"){
		
		getResponse("pegaProgProj","../ajax/ajax_programa_projeto.php?id="+valor+"&idproj="+document.getElementById("idproj").value,"", topo);	

	}else if (tipo == "pega_local_ge"){

		getResponse("pegaLocalGE","../ajax/ajax_local_ge.php?id="+valor,"","<span><img src='../img/ajax-loader.gif' title='Carregando...' /></span>");	
		
	}else if (tipo == "pega_ativ"){
		
		getResponse("pegaAtiv","../ajax/ajax_ativ.php?id="+valor,"","<span><img src='../img/ajax-loader.gif' title='Carregando...' /></span>");	
		
	}
        else if (tipo == "pega_prog_proj2"){
		
		getResponse("pegaProgProj","../ajax/ajax_programa_projeto2.php?id="+valor,"", topo);	

	}
        else if (tipo == "pega_proj_acoes"){
		
		getResponse("pegaProjAcoes","../ajax/ajax_projeto_acoes.php?id="+valor,"", topo);

	}        
}

function pega_valor2(valor, tipo, param){
	var topo = "<span style='font-family:Verdana, Arial, Helvetica, sans-serif;font-size:11px;font-weight:bold;color:#000066;font-style:normal;text-decoration:none;'><img src='../img/ajax-loader.gif' title='Carregando...' />&nbsp;Carregando...</span>";
	
	if (tipo == "pega_proj"){
		
	  getResponse("pegaProjeto","../ajax/ajax_pega_atv.php?id="+valor+"&ativ="+param,"", topo);
	  
    }else if (tipo == "pega_proj2"){
		
	  getResponse("pegaProjeto","../ajax/ajax_pega_atv2.php?id="+valor,"", topo);
	  
	}else if (tipo == "pega_familia"){
		
	  getResponse("pegaFamilia","../ajax/ajax_form_familia.php?id="+valor+"&muni="+document.getElementById("muni").value,"", topo);
	  
	}else if (tipo == "pega_unid"){
		
		getResponse("pegaUnid","../ajax/ajax_exec_unid.php?id="+valor,"", topo); 
		
	}else if (tipo == "pega_campounid"){
		for (var i = 0; i <document.getElementById("qtd").value; i++) {			
		
			getResponse("pegaCampoUnid"+i,"../ajax/ajax_exec_unid2.php?id="+valor,"", topo);
			
		}
	}else if (tipo == "excluirDetalheCompra"){
		getResponse("excDetCompra","../ajax/ajax_detalhe_compra.php?id="+valor,"", topo); 
	}else if (tipo == "excluirDetalhePedido"){
		
		getResponse("excDetPedido","../ajax/ajax_detalhe_pedido.php?id="+valor,"", topo);
		
		document.getElementById("valorProduto").innerHTML  	  = "";
		document.getElementById("pegaCalculaTotal").innerHTML = "";
		document.getElementById("pegaUnidProduto").innerHTML  = "";
		document.getElementById("pegaQTDRestante").innerHTML  = "";
		document.getElementById("pegaTributacao").innerHTML   = "";
//		document.getElementById("estoque").value			  = "";
		
		getsfiltroPEDIDOS('../consultas/lista_itens_pedidos.php', document.getElementById("id2").value, 'codtp', 'DESC');
		getsfiltroPEDIDOS('../consultas/lista_itens_pedidos.php', document.getElementById("id2").value, 'codtp', 'DESC');
		
		pega_valor2(document.getElementById("idcli").value, 'pega_saldo');

	}else if (tipo == "pega_inclusao_detalhe"){
		
		getResponse("incluirDetPedido","../ajax/ajax_incluir_detalhe_pedido.php?id="+document.getElementById("id").value+"&idprd="+document.getElementById("id_nome").value+"&valor="+document.getElementById("preco").value+"&qtd="+document.getElementById("qtd").value+"&mod="+document.getElementById("mod").value,"", topo);
	
		getsfiltroPEDIDOS('../consultas/lista_itens_pedidos.php', document.getElementById("id2").value, 'codtp', 'DESC');
		getsfiltroPEDIDOS('../consultas/lista_itens_pedidos.php', document.getElementById("id2").value, 'codtp', 'DESC');
		
		document.getElementById("nome_autocomplete").value 	  = ""; 
		document.getElementById("uf1").value 		       	  = "";
		document.getElementById("id_nome").value 		   	  = "";
		document.getElementById("qtd").value     	       	  = "";
		document.getElementById("valorProduto").innerHTML  	  = "";
		document.getElementById("pegaCalculaTotal").innerHTML = "";
		document.getElementById("pegaUnidProduto").innerHTML  = "";
		document.getElementById("pegaQTDRestante").innerHTML  = "";
		document.getElementById("pegaTributacao").innerHTML   = "";
//		document.getElementById("estoque").value			  = "";
		
		pega_valor2(document.getElementById("idcli").value, 'pega_saldo');
		
	}else if (tipo == "pega_calcula_total"){
		
		getResponse("pegaCalculaTotal","../ajax/ajax_calcula_total.php?qtd="+valor+"&valor="+document.getElementById("preco").value+"&mod="+document.getElementById("mod").value,"", topo);	
		
	}else if (tipo == "pega_calc_semana"){
		
//		getResponse("pegaCalculaTotalSemanal","../ajax/ajax_calculo_semana.php?id="+valor+"&mod="+document.getElementById("mod").value,"", topo);	
		
	}else if (tipo == "pega_saldo"){
		getResponse("pegaCalculaSaldo","../ajax/ajax_saldo.php?id="+valor+"&qtd="+document.getElementById("qtd").value+"&mod="+document.getElementById("mod").value+"&valor="+document.getElementById("valorProduto").innerHTML,"", topo);
		
	}else if (tipo == "pega_calcula_restante"){

		getResponse("pegaQTDRestante","../ajax/ajax_calc_restante.php?id="+document.getElementById("idcli").value+"&qtd="+document.getElementById("qtd").value+"&mod="+document.getElementById("mod").value+"&idprd="+document.getElementById("id_nome").value+"&tipo="+document.getElementById("tipo").value,"", topo);

	}else if (tipo == "pega_unidades_prd"){
		
		getResponse("pegaUnidProduto","../ajax/ajax_unidade_prd.php?id="+valor,"", topo);
		
	}else if (tipo == "pega_trib_prd"){
		
		getResponse("pegaTributacao","../ajax/ajax_tributacao.php?id="+valor,"", topo);
		
	}else if (tipo == "pega_desab_btn"){

		getResponse("pegaValidaITEM","../ajax/ajax_desabilita_btn_total.php?id="+document.getElementById("idcli").value+"&qtd="+document.getElementById("qtd").value+"&tipo="+document.getElementById("tipo").value,"", topo);
		
	}else if (tipo == "pega_desab_btn_calc"){
	   
		getResponse("pegaValidaITEM","../ajax/ajax_desabilita_btn_calc.php?id="+document.getElementById("idcli").value+"&qtd="+document.getElementById("qtd").value+"&idprd="+document.getElementById("id_nome").value+"&tipo="+document.getElementById("tipo").value,"", topo);

	}else if (tipo == "pega_valida_cpf"){
		
		getResponse("validaCPF","../ajax/ajax_validacpf.php?id="+valor,"", topo);

	}else if (tipo == "pega_exibebtn_cpf"){
		
		getResponse("exibirBTNCPF","../ajax/ajax_exibebtncpf.php?id="+valor,"", topo);
		
	}else if (tipo == "pega_update_senha"){
		
		if(confirm("Confirma a alteração da senha do usuário "+param+" para 123456 ?")){
		
			getResponse("pegaUpdateSenha","../ajax/ajax_updatesenha.php?id="+valor,"", topo);
		
		}
		
	}else if (tipo == "pega_detalhe_status"){
		
		getResponse("pegaDetalheStatus","../ajax/ajax_status.php?id="+valor,"", topo);
		
	}else if (tipo == "pega_muda_status"){
		
		getResponse("pegaMudaStatus"+param,"../lib/Fachada.php?acao="+document.getElementById("acaostatus").value+"&id="+valor+"&parm="+param,"", topo);
		
	}else if (tipo == "cancelar_pedido"){
		
		if (confirm("Confirma o Cancelamento desta Nota ?")){

			getResponse("pegaCancelaPedido","../ajax/ajax_cancela_pedido.php?id="+valor,"", topo);
			document.getElementById("resultado").value = ""; 		
			getsfiltroPEDIDOS2('lista_pedidos.php', '', 'codtp', 'DESC');
			getsfiltroPEDIDOS2('lista_pedidos.php', '', 'codtp', 'DESC');			
		}
		
	}else if (tipo == "pega_valor_produto"){
		
		getResponse("pegaValorProduto"+param,"../ajax/ajax_valor_produto.php?id="+valor+"&param="+param,"", topo);
		
	}else if (tipo == "pega_calc_valor_produto"){
		
		form   = document.forms["form1"];
		qtd    = form.length;
		vazios = Array();
		
		var teliga = "qtd"+param;
		
		var calc  = 0;
		if (valor == "") valor = 0;
		
		getResponse("pegaCalcValorProduto"+param,"../ajax/ajax_calc_valor_produto.php?valor="+document.getElementById("pegaValorProduto"+param).innerHTML.replace(",",".")+"&qtd="+document.getElementById(teliga).value,"", topo);

	}else if (tipo == "pega_qtd_itens"){

		getResponse("pegaItensProdEmp","../ajax/ajax_qtd_itens.php?id="+valor,"", topo);

	}else if (tipo == "exibir_cfop"){

       getResponse("exibirCFOP","../ajax/ajax_cfop.php?id="+valor,"", topo);

	}else if (tipo == "pega_valida_cpfcnpj"){	   
	   getResponse("exibirBTNCLI","../ajax/ajax_btncli.php?id="+valor+"&tipo="+param+"&idc="+document.getElementById("id").value,"", topo);
       
	}else if (tipo == "pega_valida_cpfcnpj2"){
	   if (param == "F"){
	       getResponse("exibirBTNCLI2","../ajax/ajax_btncli2.php?id="+valor+"&tipo="+param+"&idc="+document.getElementById("id").value,"", topo);    
	   }else{
	       getResponse("exibirBTNCLI3","../ajax/ajax_btncli2.php?id="+valor+"&tipo="+param+"&idc="+document.getElementById("id").value,"", topo);
	   }

	}else if (tipo == "pega_consolidado"){	   
	   
	   getResponse("exibirConsolidado","../ajax/ajax_consolidado.php?id="+valor+"&id2="+param,"", topo);
              
    }else if (tipo == "pega_municipios"){
        
        if (document.getElementById("conso1").checked) var flag = "RD"; else var flag = "GR";
        getResponse("exibirMunicipios","../ajax/ajax_municipios.php?id="+valor+"&flag="+flag+"&idconso="+param,"", topo);
        
    }else if (tipo == "pega_processos"){

        getResponse("pegaProcessos","../ajax/ajax_processos.php?id="+valor+"&flag="+flag+"&idconso="+param,"", topo);
        
    }else if (tipo == "pega_verifica_valor_produto"){

        getResponse("valorProduto","../ajax/ajax_verifica_valor_produto.php?valor="+document.getElementById("prec").value+"&qtd="+document.getElementById("qtd").value,"", topo);
                
    }else if (tipo == "pega_cidades_uf"){
        
        getResponse("pegaCidadeUF","../ajax/ajax_cidade_uf.php?id="+valor,"", topo);
        
    }else if (tipo == "pega_dados_cliente"){
        
        getResponse("dadosCLI","../ajax/ajax_dados_cli.php?id="+valor,"", topo);
        
    }else if (tipo == "pega_clientes_empresa"){

        getResponse("pegaClientesEMP","../ajax/ajax_clientes_empresa.php?empresa="+valor,"", topo);

    }else if (tipo == "pega_subgrupos_grp"){

        getResponse("pegaSubGrupoGRP","../ajax/ajax_subgrupos_grp.php?id="+valor+"&idsub="+document.getElementById("idsub").value+"&empresa="+document.getElementById("empresa").value,"", topo);
        
    }else if (tipo == "pega_fabri_emp"){

        getResponse("pegaFabriEmp","../ajax/ajax_fabri_emp.php?empresa="+valor+"&idfabri="+document.getElementById("idfabri").value,"", topo);
        
    }else if (tipo == "pega_linhas_sgrp"){

        getResponse("pegaLinhasSGRP","../ajax/ajax_linhas_sgrp.php?id="+valor+"&idlinha="+document.getElementById("idlinha").value,"", topo);
        
    }else if (tipo == "pega_grupos_prod"){
        
        getResponse("pegaGruposPrd","../ajax/ajax_grupos_prd.php?empresa="+valor,"", topo);        

	}else if (tipo == "pega_valida_codprd"){
	   
       getResponse("exibirBTNPRD","../ajax/ajax_btnprd.php?id="+valor+"&tipo="+param+"&idprd="+document.getElementById("id").value,"", topo);
       
	}else if (tipo == "pega_valida_codprd2"){
	   
       getResponse("exibirBTNPRD2","../ajax/ajax_btnprd2.php?id="+valor+"&tipo="+param+"&idprd="+document.getElementById("id").value,"", topo);
 
	}else if (tipo == "pega_vendedor_emp"){
       getResponse("pegaVendedorEMP","../ajax/ajax_vendedor_emp.php?id="+valor+"&id2="+document.getElementById("idvend").value+"&empresa="+document.getElementById("empresa").value,"", topo); 
       
	}else if (tipo == "pega_qtd_agr"){
	   
       getResponse("pegaQTDAgr","../ajax/ajax_pega_qtd_agr.php?id="+valor,"", topo);
       
    }else if (tipo == "pega_exibir_qtd"){

        if (valor != ""){
            
            document.getElementById("colunaTOT").style.visibility = "visible";
            document.getElementById("colunaTOT").style.position   = "inherit";
            
        }else{

            document.getElementById("colunaTOT").style.visibility = "hidden";
            document.getElementById("colunaTOT").style.position   = "absolute";
            
        }    

	}else if (tipo == "pega_inclusao_plano_anual"){
        
        getResponse("pegaIncluirPlanoAnual","../lib/Fachada.php?acao="+document.getElementById("acaoform").value+"&idmuni="+document.getElementById('idmuni').value+"&idusu="+document.getElementById('idusu').value+"&idatv="+document.getElementById('idatv').value+"&anoplano="+document.getElementById('anoplano').value+"&idproj="+document.getElementById('idproj').value+"&qtd1="+document.getElementById('qtd1').value+"&qtd2="+document.getElementById('qtd2').value+"&idplanoanual="+document.getElementById('idplanoanual').value, "", topo);
        getsfiltroPA('lista_elaboracao.php', '', 'codtp', 'DESC');
        getsfiltroPA('lista_elaboracao.php', '', 'codtp', 'DESC');

	}else if (tipo == "pega_atividades_proj"){
	   
	   getResponse("pegaAtividadePROJ","../ajax/ajax_atividades_projeto.php?id="+valor,"", topo);

	}else if (tipo == "pega_usuario_municipio"){
	   
	   getResponse("pegaUsuariosMUN","../ajax/ajax_usuarios_municipio.php?id="+valor,"", topo);

	}else if (tipo == "pega_qtd_municipio"){
	   
	   getResponse("pegaQtdMUN","../ajax/ajax_qtd_municipio.php?id="+valor+"&id2="+param,"", topo);       
 
	}
}