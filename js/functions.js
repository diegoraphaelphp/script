function marcarObrigatorios(obrig){
	for (i = 0; i < obrig.length ; i++){
		document.getElementById(obrig[i]).style.backgroundColor = "#FFCC00";
		document.getElementById(obrig[i]).style.color 			= "#000000";
	}
	setTimeout("desmarcarObrigatorios()",100000);
	
	
}

function desmarcarObrigatorios(){
	var	form;
	
	form = document.forms[0];
	for (i = 0; i < form.length ; i++){
		if(form[i].type != "button"){
			form[i].style.backgroundColor = "#FFFFFF";
			form[i].style.color = "#000000";
		}
	}
}

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

function valida_form_ajax2(){
	var form, qtd, vazios, i;
	//setar vars
	form   = document.forms['form1'];	//pegar o nome do form a verificar
	qtd    = form.length;						//quantidade de elementos do form
	vazios = Array();						//array de id's de elementos vazios
	
	//verificar textfields vazio
	for (i = 0 ; i < qtd ; i++){
		
		if (form[i].type == 'text'){
			if ((form[i].value == "") && (form[i].alt == "#")){
				vazios.push(form[i].id);
			}
		}
		
		if (form[i].type == 'password'){
			if ((form[i].value == "") && (form[i].alt == "#")){
				vazios.push(form[i].id);
			}
		}
		
		//verificar selects vazio
		if ( (form[i].type == "select-one") && (form[i].value == "") ){
			vazios.push(form[i].id);
		}
		
		//verificar textarea vazio
		if ((form[i].type == "textarea") && (form[i].value == "")){
			vazios.push(form[i].id);
		}
	}
	
	//notificar ou enviar
	if (vazios.length == 0){_a
		//incluindo atividades...	
//		alert(document.getElementById("acaoajax").value+' twsetsd gs');
		pega_valor('', document.getElementById("acaoajax").value);		
		
		if (document.getElementById("acaoajax").value == "incluirItensCompra"){
		
		    getResponse("incluirItens","../lib/Fachada.php?acao="+document.getElementById("acao").value+"&id="+document.getElementById("id").value+"&desc="+document.getElementById("desc").value+"&valor="+document.getElementById("valor").value+"&qtd="+document.getElementById("qtd").value+"&und="+document.getElementById("und").value+"&mod="+document.getElementById("mod").value, "", "<span><img src='../img/ajax-loader.gif' title='Carregando...' /></span>");
			
			document.getElementById("desc").value  = ""; 
			document.getElementById("valor").value = "";
			document.getElementById("qtd").value   = ""; 
			document.getElementById("und").value   = "";

			getsfiltroCOMPRAS("../consultas/lista_itens_compras.php", document.getElementById("id").value, "codtp", "DESC");
			getsfiltroCOMPRAS("../consultas/lista_itens_compras.php", document.getElementById("id").value, "codtp", "DESC");
			
			document.getElementById("desc").focus();

		}else if (document.getElementById("acaoajax").value == "incluirItensPedido"){
			
			if (trim(document.getElementById("qtd").value) > 0){
			
				getResponse("incluirdetalhePedido","../lib/Fachada.php?acao="+document.getElementById("acao").value+"&id="+document.getElementById("id").value+"&idprd="+document.getElementById("id_nome").value+"&valor="+document.getElementById("valor").value+"&qtd="+document.getElementById("qtd").value+"&mod="+document.getElementById("mod").value+"&identification="+document.getElementById("idusu").value, "", "<span><img src='../img/ajax-loader.gif' title='Carregando...' /></span>");
				
				document.getElementById("nome_autocomplete").value  = ""; 
				document.getElementById("uf1").value  			    = ""; 
				document.getElementById("id_nome").value  		    = ""; 			
				document.getElementById("valor").value 		        = "";
				document.getElementById("qtd").value                = ""; 
				document.getElementById("exibirTOTAL").innerHTML    = "";
	
				getsfiltroPEDIDOS("../consultas/lista_itens_pedidos.php", document.getElementById("id").value, "codtp", "DESC");
	
				document.getElementById("nome_autocomplete").focus();
				
			}else{
				
				alert("Quantidade do item precisa ser maior que zero.");
				document.getElementById("qtd").focus();
				
			}
		}
		
	}else{
		marcarObrigatorios(vazios);
		document.getElementById("validaForm").style.visibility = "visible";		
	}	
}

function valida_form(){
	var form, qtd, vazios, i;
	//setar vars
	form   = document.forms['form1'];	//pegar o nome do form a verificar
	qtd    = form.length;						//quantidade de elementos do form
	vazios = Array();						//array de id's de elementos vazios
	
	//verificar textfields vazio
	for (i = 0 ; i < qtd ; i++){
		
		if (form[i].type == 'text'){
			if ((form[i].value == "") && (form[i].alt == "#")){
				vazios.push(form[i].id);
			}
		}
		
		if (form[i].type == 'password'){
			if ((form[i].value == "") && (form[i].alt == "#")){
				vazios.push(form[i].id);
			}
		}
		
		//verificar selects vazio
		if ( (form[i].type == "select-one") && (form[i].value == "") ){
			vazios.push(form[i].id);
		}
		
		//verificar textarea vazio
		if ((form[i].type == "textarea") && (form[i].value == "")){
			vazios.push(form[i].id);
		}
	}
	
	//notificar ou enviar
	if (vazios.length == 0){
		form.submit();
	}else{
		marcarObrigatorios(vazios);
		document.getElementById("validaForm").style.visibility = "visible";
//		alert("Alguns campos obrigatorios estao vazios.");
		
	}	
}

function valida_form_ajax(){
	var form, qtd, vazios, i;
	//setar vars
	form   = document.forms['form1'];	//pegar o nome do form a verificar
	qtd    = form.length;						//quantidade de elementos do form
	vazios = Array();						//array de id's de elementos vazios
	
	//verificar textfields vazio
	for (i = 0 ; i < qtd ; i++){
		
		if (form[i].type == 'text'){
			if ((form[i].value == "") && (form[i].alt == "#")){
				vazios.push(form[i].id);
			}
		}
		
		if (form[i].type == 'password'){
			if ((form[i].value == "") && (form[i].alt == "#")){
				vazios.push(form[i].id);
			}
		}
		
		//verificar selects vazio
		if ( (form[i].type == "select-one") && (form[i].value == "") ){
			vazios.push(form[i].id);
		}
		
		//verificar textarea vazio
		if ((form[i].type == "textarea") && (form[i].value == "")){
			vazios.push(form[i].id);
		}
	}
	
	//notificar ou enviar
	if (vazios.length == 0){
		//incluindo atividades...	
		pega_valor('', 'addAtividades');
		
	}else{
		marcarObrigatorios(vazios);
		document.getElementById("validaForm").style.visibility = "visible";		
	}	
}

function excluirAjax(cod, acao, consulta){
	if(confirm("Confirma a Exclusï¿½o do registro?")){
		pega_valor2(cod, acao);
		cods = cod.split("@");
		getsfiltroCOMPRAS(consulta, cods[0], 'codtp', '');		
//		getsfiltroCOMPRAS(consulta, cods[0], 'codtp', '');		
	}
}

function excluir_detalhe_pedido(cod, acao, consulta){
	if(confirm("Confirma a Exclusï¿½o do registro?")){
		
		pega_valor2(cod, acao);
		cods = cod.split("@");
		getsfiltroPEDIDOS(consulta, cods[0], 'codtp', '');
		getsfiltroPEDIDOS(consulta, cods[0], 'codtp', '');		

	}
}

function excluir_pedido(cod, acao, consulta){
	if(confirm("Confirma a Exclusï¿½o do registro?")){
		pega_valor2(cod, acao);
		getsfiltroPEDIDOS(consulta, cods[0], 'codtp', '');
	}
}

function excluir_ajax_empenhados(cod){
	if(confirm("Confirma a Exclusï¿½o do Empenho ?")){
		pega_valor(cod, 'excEmpenhados');
		
		//executando o load do form novamente...
		document.getElementById("filtrar").focus(); 
		getsfiltro1("../consultas/lista_prod_empenhados.php", "", "codtp", "DESC");
	}
}

function cancelar_ajax_empenhados(cod){
	if(confirm("Confirma o Cancelamento do Empenho ?")){
		pega_valor(cod, 'cancelarEmpenhados');
		
		//executando o load do form novamente...
		document.getElementById("filtrar").focus(); 
		getsfiltro1("../consultas/lista_prod_empenhados.php", "", "codtp", "DESC");
	}
}

function excluir_ajax(cod){
	if(confirm("Confirma a Exclusï¿½o do registro?")){
		pega_valor(cod, 'excAtividades');
	}
}

function excluir_ajax2(cod){
	if(confirm("Confirma a Exclusï¿½o do registro?")){
		pega_valor(cod, 'excAtividades2');
		
		//executando o load do form novamente...
		document.getElementById("nome").value   = "";
		document.getElementById("status").value = "A";
		getsfiltro1("../consultas/lista_projetos.php", document.getElementById("id").value, "codtp", "DESC");
	}
}

//excluir itens do plano anual...
function excluir_ajax_pa(cod){
	if(confirm("Confirma a Exclusï¿½o do registro?")){
			
		pega_valor(cod, 'excItemPlanoAnual');
		document.getElementById('resultado').innerHTML = "";
		//getsfiltroPA('../consultas/lista_elaboracao.php', '', 'codtp', 'DESC');
	}
}

function excluir_ajax_pa2(cod){
	if(confirm("Confirma a Exclusï¿½o do registro?")){
		pega_valor(cod, 'excItemPlanoAnual2');

        document.getElementById('resultado').innerHTML = "";
        alert("Registro excluï¿½do com sucesso.");
	}
}

//sem text-area... 
function valida_form2(){

	var form, qtd, vazios, i;
	//setar vars
	form   = document.forms['form1'];	//pegar o nome do form a verificar
	qtd    = form.length;						//quantidade de elementos do form
	vazios = Array();						//array de id's de elementos vazios
	
	//verificar textfields vazio
	for (i = 0 ; i < qtd ; i++){
		
		if (form[i].type == 'text'){
			if ((form[i].value == "") && (form[i].alt == "#")){
				vazios.push(form[i].id);
			}
		}
		
		if (form[i].type == 'password'){
			if ((form[i].value == "") && (form[i].alt == "#")){
				vazios.push(form[i].id);
			}
		}
		
		//verificar selects vazio
		if ( (form[i].type == "select-one") && (form[i].value == "") ){
			vazios.push(form[i].id);
		}
		
		//verificar textarea vazio
		if ((form[i].type == "textarea") && (form[i].value == "")){
			vazios.push(form[i].id);
		}

	}
	
	//notificar ou enviar
	if (vazios.length == 0){
		form.submit();
	}else{ 
		marcarObrigatorios(vazios);
		alert("Alguns campos obrigatorios estao vazios.");
	}	
	
}

function valida_form3(){

	var form, qtd, vazios, i;
	//setar vars
	form   = document.forms['form1'];	//pegar o nome do form a verificar
	qtd    = form.length;						//quantidade de elementos do form
	vazios = Array();

	//verificar textfields vazio
	for (i = 0 ; i < qtd ; i++){
		
		if (form[i].type == 'text'){
			if ((form[i].value == "") && (form[i].alt == "#")){
				vazios.push(form[i].id);
			}
		}
		
		if (form[i].type == 'password'){
			if ((form[i].value == "") && (form[i].alt == "#")){
				vazios.push(form[i].id);
			}
		}
		
		//verificar selects vazio
		if ( (form[i].type == "select-one") && (form[i].value == "") ){
			vazios.push(form[i].id);
		}
	}
	
	//notificar ou enviar
	if (vazios.length == 0){
		form.submit();
	}else{
		marcarObrigatorios(vazios);
		alert("Alguns campos obrigatorios estao vazios.");
	}	
	
}

function valida_form4(){

	var form, qtd, vazios, i;
	//setar vars
	form   = document.forms['form1'];	//pegar o nome do form a verificar
	qtd    = form.length;						//quantidade de elementos do form
	vazios = Array();						//array de id's de elementos vazios
	
	//verificar textfields vazio
	for (i = 0 ; i < qtd ; i++){
		
		if (form[i].type == 'text'){
			if ((form[i].value == "") && (form[i].alt == "#")){
				vazios.push(form[i].id);
			}
		}
		
		if (form[i].type == 'password'){
			if ((form[i].value == "") && (form[i].alt == "#")){
				vazios.push(form[i].id);
			}
		}
		
		//verificar selects vazio
		if ( (form[i].type == "select-one") && (form[i].value == "") ){
			vazios.push(form[i].id);
		}
	}
	
	//notificar ou enviar
	if (vazios.length == 0){
		form.submit();
	}else{
		marcarObrigatorios(vazios);
		alert("Alguns campos obrigatorios estao vazios.");
	}	
	
}

function valida_form5(){

	var form, qtd, vazios, i;
	//setar vars
	form   = document.forms['form5'];	//pegar o nome do form a verificar
	qtd    = form.length;						//quantidade de elementos do form
	vazios = Array();						//array de id's de elementos vazios
	
	//verificar textfields vazio
	for (i = 0 ; i < qtd ; i++){
		
		if (form[i].type == 'text'){
			if ((form[i].value == "") && (form[i].alt == "#")){
				vazios.push(form[i].id);
			}
		}
		
		if (form[i].type == 'password'){
			if ((form[i].value == "") && (form[i].alt == "#")){
				vazios.push(form[i].id);
			}
		}
		
		//verificar selects vazio
		if ( (form[i].type == "select-one") && (form[i].value == "") ){
			vazios.push(form[i].id);
		}
	}
	
	//notificar ou enviar
	if (vazios.length == 0){
		form.submit();
	}else{
		marcarObrigatorios(vazios);
		alert("Alguns campos obrigatorios estao vazios.");
	}	
	
}

function mudarAction(form,action) {
	document.getElementById(form).action = action;
}

function mudarAction3(form,action) {
	document.getElementById(form).action = action;
}

function clica_enter(funcao){
	if (event.keyCode == 13){
	  if (funcao != "")
		funcao();
	}
}

function gerar_xls(){	
	document.getElementById("form1").target = "_blank";
	document.getElementById("form1").action = document.getElementById("acaoxls").value;
	document.getElementById("form1").submit();
}

function gerar_xls2(){	
	document.getElementById("form1").target = "_blank";
	document.getElementById("form1").action = document.getElementById("acaoxls2").value;
	document.getElementById("form1").submit();
}



function Voltar(){
  document.location.href="../menu.php";
}

function redir(url){
  document.location.href = url;
}

function minuscula(campo){
	var val = campo.value;
		campo.value = val.toLowerCase();
}

function maiuscula(campo){
	var val = campo.value;
		campo.value = val.toUpperCase();
}

function isCPF(input, msg){
	var result = true;
	var r = new RegExp('[./-]', 'g');
	var cpf = input.value.replace(r, '');
	
	result = isNotRepeated(cpf.substr(0,10));
	
	var i;
	var c = cpf.substr(0,9);
	var dv = cpf.substr(9,2);
	var d1 = 0;
	for (i = 0; i < 9; i++) {
		d1 += c.charAt(i)*(10-i);
	}
	if (d1 == 0) result =  false;
    d1 = 11 - (d1 % 11);
    
    if (d1 > 9) d1 = 0;         
	if (dv.charAt(0) != d1) result = false;
	d1 *= 2;
	for (i = 0; i < 9; i++) {
		d1 += c.charAt(i)*(11-i);
	}
	d1 = 11 - (d1 % 11);
	if (d1 > 9) d1 = 0;
	if (dv.charAt(1) != d1) result =  false;
	
	isValid(result, input.id, msg);
	return result;
}

function isNotRepeated(campo){
	var resultado = false;
	var indice	  = 0;

	for(indice=0; indice < campo.length; indice++){
		if( campo.charAt(0) != campo.charAt(indice)){
			resultado = true;
		}
	}
	
	return resultado;
}

function isValid(result, input, msg) {
	
   if (result == false && msg != "" && alerted == false) {
      alert(msg);
	  alerted = true;
      document.getElementById(input).focus();
   }else{
   	return true;
   }
}

	function auto_data( campo ) {
	    texto = campo.value;
    	if( texto.length == 2 ) {
	        texto += '/';
    	    campo.value = texto;
	    } else if( texto.length == 5 ) {
	        texto += '/';
	        campo.value = texto;
    	}
	}
	
	function auto_data2(campo) {
	    texto = campo.value;
    	if( texto.length == 2 ) {
	        texto += '/';
    	    campo.value = texto;
    	}
	}

	function valida_data(campo) {
		data = campo.value;
		resultado = true;
	
		if( data != "" ) {
			if( data.charAt(0) != '0' ) {
				dia = data.charAt(0) + data.charAt(1);
			} else {
				dia =data.charAt(1);
			}
			dia = parseInt(dia);
	
			if( data.charAt(3) != "0" ) {
				mes = data.charAt(3) + data.charAt(4);
			} else {
				mes = data.charAt(4);
			}
			mes = parseInt(mes);
	
			if(data.charAt(6) != '0' && data.charAt(7) != '0' && data.charAt(8) != '0' ) {
				ano = data.charAt(7) + data.charAt(8) + data.charAt(9);
			}else if(data.charAt(7) != '0' && data.charAt(8) != '0' ) {
				ano = data.charAt(8) + data.charAt(9);				
			}else if(data.charAt(8) != '0' ) {
//				ano = data.charAt(6)+data.charAt(7)+data.charAt(8)+data.charAt(9);
			}else{	
//				ano = data.charAt(6) + data.charAt(7) + data.charAt(8) + data.charAt(9);
			}
			ano = data.charAt(6) + data.charAt(7) + data.charAt(8) + data.charAt(9);
			ano = parseInt(ano);

			if( campo.value.length != 10 ) {
				alert( "Data invï¿½lida!\nVerifique a quantidade de dï¿½gitos" );
				campo.focus();
				resultado = false;
			} else if( (mes == 4 || mes == 6 || mes == 9 || mes == 11) && dia > 30 ) {
				alert( "Data invï¿½lida!\nEsse mï¿½s nï¿½o permite dia 31" );
				campo.focus();
				resultado = false;
			} else if( mes == 2 && dia > 29 ) {
				alert( "Data invï¿½lida!\nFevereiro nï¿½o permite dia com esse valor" );
				campo.focus();
				resultado = false;
			} else if( campo.value.charAt( 2 ) != '/' || campo.value.charAt( 5 ) != '/' ) {
				alert( "Data invï¿½lida!\nVerifique o formato da data" );
				campo.focus();
				resultado = false;
			} else if( dia < 1 || dia > 31 ) {
				alert( "Data InvÃ¡lida!\nVerifique o valor do dia" );
				campo.focus();
				resultado = false;
			} else if( mes < 1 || mes > 12 ) {
				alert( "Data invï¿½lida!\nVerifique o valor do mÃªs" );
				campo.focus();
				resultado = false;
			} else if( ano < 1 ) {
				alert( "Data invï¿½lida!\nVerifique o valor do ano");
				campo.focus();
				resultado = false;
			}

			if (resultado == false){
			  campo.value = "";	  
			}
			return resultado;
		}
	}


	function auto_cep( campo ) {
		texto = campo.value;
		if( parseInt(texto.length) == 5 ) {
			texto += "-";
			campo.value = texto;
		}
	}
	
	function validar_cep( campo ) {
		if( campo.value != '' ) {
			if( campo.value.length != 9 ) {
				alert( "Valor do CEP invalido");
				campo.value = "";
				campo.focus();				
//				return false;

			} else if( campo.value.charAt(0) == '5' ) {
				return true;
			} else {
				//alert( "Valor de CEP invï¿½ido nï¿½ sei" );
				//campo.focus();
				//return false;
			}
		}
	}	
	
	/* MASCARA PARA TELEFONE*/
	
function TelefoneFormat(Campo, e) {
	var key = '';
	var len = 0;
	var strCheck = '0123456789';
	var aux = '';
	var whichCode = (window.Event) ? e.which : e.keyCode;
	
	if (whichCode == 13 || whichCode == 8 || whichCode == 0)
	{
		return true;  // Enter backspace ou FN qualquer um que nao seja alfa numerico
	}
	key = String.fromCharCode(whichCode);
	if (strCheck.indexOf(key) == -1){
		return false;  //NaO E VALIDO
	}
	
	aux =  Telefone_Remove_Format(Campo.value);
	
	len = aux.length;
	if(len>=10)
	{
		return false;	//impede de digitar um telefone maior que 10
	}
	aux += key;
	
	Campo.value = Telefone_Mont_Format(aux);
	return false;
}

function Telefone_Mont_Format(Telefone){
	var aux = len = '';
	
	len = Telefone.length;
	if(len<=9)
	{
		tmp = 5;
	}
	else
	{
		tmp = 6;
	}
	
	aux = '';
	for(i = 0; i < len; i++)
	{
		if(i==0)
		{
			aux = '(';
		}
		aux += Telefone.charAt(i);
		if(i+1==2)
		{
			aux += ')';
		}
		
		if(i+1==tmp)
		{
			aux += '-';
		}
	}
	return aux ;
}

function Telefone_Remove_Format(Telefone){
	var strCheck = '0123456789';
	var len = i = aux = '';
	len = Telefone.length;
	for(i = 0; i < len; i++)
	{
		if (strCheck.indexOf(Telefone.charAt(i))!=-1)
		{
			aux += Telefone.charAt(i);
		}
	}
	return aux;
}
/**/

	function auto_cnpj(campo) {
	  texto = campo.value;
	  if(parseInt(texto.length) == 2) {
	    texto += ".";
	    campo.value = texto;
		  
      }else if(parseInt(texto.length) == 6) {
	    texto += ".";
	    campo.value = texto;			
		  
      }else if(parseInt(texto.length) == 10) {
	    texto += "/";
	    campo.value = texto;
	  } else if ( parseInt(texto.length) == 15) {
	    texto += "-";
	    campo.value = texto;
	  }
	}
	
	function verifica_cnpj2(campo) {
	  var texto = campo.value;
	  if (texto != "") {
		if (parseInt(texto.length) < 18) {
		  alert("ATENcaO: CNPJ invalido");
	      campo.focus();
		  return false;
	  	}
      }	  	
	}			
	
	
	var ncnpj = new Array;

	function valida_cnpj(Form,nForm){
		if (form1.cnpj.value != '') {
			var Campos = eval('document.' + nForm + '.vcnpj.value');
			var Contador = 0;
			var x = 0;
			var i = Campos.indexOf( "," );
			if (i==-1){
				ncnpj[Contador] = Campos.slice(0,Campos.length);
			} else {
				ncnpj[Contador] = Campos.slice(0,i);
				Campos = Campos.slice(i+1,Campos.length);
				//Rotina que recebe os demais campos
				for (;x<Campos.length;x++){
					if (Campos.slice(x,x+1) == ","){
						Contador = Contador + 1;
						ncnpj[Contador] = Campos.slice(0,x);
						Campos = Campos.slice(x+1,Campos.length);
						x = 0;
					}
				}
				ncnpj[ncnpj.length] = Campos;
			}
			x = 0;
			for (;x<ncnpj.length;x++){
				var Obj = eval ("document." + nForm + "." + ncnpj[x])
				if(!verifica_cnpj(Obj)){
				Obj.focus();
				ncnpj = new Array;
				return false;
			}
		}
	}
	return true;
	}

	function verifica_cnpj(S){
		Testa_Tamanho_do_Numero = true;
		Digitos_Verificadores_cnpj = 2;
		Digitos_cnpj = 18; //xx.xxx.xxx/xxxx-xx tem 14 numeros
		/*
		 * Alem de testar os digitos verificadores as funcoes seguintes
		 * tambem devem testar o tamanho dos numeros fornecidos (no caso
		 * desta constante ser True). Se for colocada como False sera'
		 * somente verificada a igualdade dos digitos verificadores.
		*/
		
		// S - ï¿½o OBJETO Text e nï¿½ o valor!!!
		//Verifica se o string esta' ok (CPF ou cnpj)
		
		var Original = Limpa_cnpj(S);
		var Gerado = "";
		var Tamanho = Digitos_cnpj;  //tamanho esperado para o cnpj
		
		teste = (( !Testa_Tamanho_do_Numero) || (Testa_Tamanho_do_Numero && Original.length == Tamanho));
		//alert("Resposta da condiï¿½o: "+teste);
		if (teste){
			//Gerado = Original;
			//retira digitos verificadores
			Gerado = Original.substring( 0, Original.length - Digitos_Verificadores_cnpj )
			Gerado = Completa_cnpj( Gerado ); //Gera numero completo
			
			cnpj_valido = (Gerado == Original) //compara com original
			//alert("Valor de cnpj_valido: "+cnpj_valido)

			if (!cnpj_valido) {
				alert("O CNPJ (cnpj) invalido, favor corrigi-lo!");
				S.select();
				S.focus();
				return false
			}else{
				return true
			}
		} else {
			alert("A quantidade de numeros do cnpj invalido, favor corrigir.");
			S.select();
			S.focus();
			return false    //Nao tem o tamanho certo
		}
	}

	function Limpa_cnpj( S_aux2 ) {
		//Retira tudo o que nao for numero,
		// mas nï¿½ tira os nmeros do cnpj
		// S_aux2 - ï¿½o objeto Text e nï¿½ o valor. Prestar atenï¿½o!!!
		var SAux = '';
		S = S_aux2.value;
		//alert("cnpj: " + S)
		var pos = 0
		for( ; pos < S.length; pos++ ) {
			if( S.charAt(pos) >= '0' && S.charAt(pos) <= '9' ) {
				SAux = SAux + S.charAt(pos);
			}
			return SAux
		}
	}
	//Completa o numero colocando digitos verificadores
	function Completa_cnpj( S ) {
		//   var SAux = Limpa_String(S);
		var SAux = S;
		var Quantos = Digitos_Verificadores_cnpj;
		var c = 1
		for( ; c <= Quantos; c++ ){
			SAux = SAux + Digito_Verificador_cnpj( SAux );
			return SAux
		}
	}
	//Calcula um digito verificador em funcao do numero
	function Digito_Verificador_cnpj( S ) {
		//   S = Limpa_String(S);
		var soma = 0
		var comprimento = S.length
		var i = 1
		for( ; i <= comprimento; i++ ) {
			// fator = 2,3,4,5,6,7,8,9, 2, 3, 4, 5...
			var fator = 2+( (i-1) % 8 );
			soma = soma + parseInt( S.charAt(comprimento-i) ) * fator
		}
		return ((10*soma) % 11) % 10
	}

	function CNPJ(quadro) {
	
		texto = quadro.value;
		if( parseInt(texto.length) == 8 ) {
			texto += "/";
			quadro.value = texto;
		} else if ( parseInt(texto.length) ==13 ) {
			texto += "-";
			quadro.value = texto;
		}
	}
	
	function auto_cep(quadro) {
		texto = quadro.value;
		if( parseInt(texto.length) == 5 ) {
			texto += "-";
			quadro.value = texto;
		}
	}	

 	function Reload(campo1,campo2){
		var	val = campo1.value;
			if (val == "outro" ) {
				campo2.disabled = false;
				campo2.focus();
			} else {
				campo2.value = '';
				campo2.disabled = true;
			}
	}
	

	function auto_tab(campo1,campo2,qtd) {
		var val = campo1.value;
			if (val.length == qtd) {
				campo2.focus();
			}	
	}
	
	function conf_senha(campo1,campo2) {
		var val1 = campo1.value;
		var val2 = campo2.value;
		if (val1 != val2) {
		  alert('ATENï¿½ï¿½O: As senhas nï¿½o conferem.');
		  campo2.value='';
		  campo1.value='';
		  campo1.focus();
		}else{
		  if (val1 == "" || val2 == ""){
		    alert("Senhas precisa ser informadas.");
		    campo2.value='';
		    campo1.value='';
		    campo1.focus();
		  }
		}
	}
	
	function valida_frm_senha(){
		if (document.getElementById("senha").value != document.getElementById("senha2").value){
			alert("ATENï¿½ï¿½O: As senhas nï¿½o conferem");	
			document.getElementById("senha").value  = "";
			document.getElementById("senha2").value = "";
			document.getElementById("senha").focus();
		}else{
			valida_form();	
		}		
	}
	

	function horario() {
		var data = new Date();
		var hora = data.getHours();	
		var minuto = data.getMinutes();	
		var segundo = data.getSeconds();	
			if (hora < 10) {	
				hora = "0"+hora;
			}
			if (minuto < 10) {
				minuto = "0"+minuto;	
			}
			if (segundo < 10) {
				segundo = "0"+segundo;
		}
			document.all['hora'].innerText =hora+":"+minuto+":"+segundo;
			setTimeout ('horario()',1000);
	}					

	function saudacao() {
		var data = new Date();
		var hora = data.getHours();	
		var dias = data.getDay();
		var cumprimento = '';
		var nomedia = new Array();
		nomedia[0] = "domingo";
		nomedia[1] = "segunda-feira";
		nomedia[2] = "terca-feira";
		nomedia[3] = "quarta-feira";
		nomedia[4] = "quinta-feira";
		nomedia[5] = "sexta-feira";
		nomedia[6] = "sabado";
			if (	hora > 0 &&
				hora < 6 ) {
				cumprimento = 'Bom dia';
			}
			if (	hora >= 6 &&
				hora < 12 ) {
				cumprimento = 'Bom dia';
			}
			if (	hora >= 12 &&
				hora < 18 ) {
				cumprimento = 'Boa tarde';
			}
			if (	hora >= 18 &&
				hora <= 23  ) {
				cumprimento = 'Boa noite';
			}
			//document.write(cumprimento+" "+nomedia[dias]);
			document.write(cumprimento);
	}
	
	function barra(esp_atual,esp_inic) {
		if (esp_atual == 0 ) esp_atual = esp_inic;
		var txt = '';	
		for ( i = 0 ; i <= esp_atual ; i++) {
			txt +=' ';
		}
		txt += 'Bem Vindo ao SEC - Sistema de Controle Escolar';
		window.status = txt;
		setTimeout('barra('+ (esp_atual-1)+','+esp_inic+')',100); 
	}
	
	function Pagina(){
		if (self == parent){
			parent.location = "index.php";
		}
	}
	
	function valida_email(campo){
    	if (campo.value !=''){
        	if ( campo.value.indexOf('@')==-1 ||
            	 campo.value.indexOf('.')==-1 ||
	             campo.value.indexOf(' ')!=-1 ||
    	         campo.value.indexOf('@.')!=-1 ||
        	     campo.value.indexOf('.@')!=-1 ||
            	 campo.value.length<6) {
		            alert("E-mail incorreto.");
					campo.select();
		            campo.focus();
        		    return false;
	        }
    	}
	}
	
	function isInteger(s){
		var i;
		for (i = 0; i < s.length; i++){
			// Check that current character is number.
			var c = s.charAt(i);
			if (((c < "0") || (c > "9"))) return false;
		}
		// All characters are numbers.
		return true;
	}

    function soNums(e,args){         
    // Funcao que permite apenas teclas numï¿½ricas e  
    // todos os caracteres que estiverem na lista 
    // de argumentos. 
    // Deve ser chamada no evento onKeyPress desta forma 
    //  onKeyPress="return (soNums(event,'0'));" 
    // caso queira apenas permitir caracters como por exemplo um campo que sï¿½ aceite valores em Hexadecimal (de 0 a F) usamos 
    //  onKeyPress ="return (soNums(event,'AaBbCcDdEeFf'));" 

/* Esta parte comentada ï¿½ a que testei exaustivamente e garanto que funciona em praticamente todos os browsers 
        var evt='';// devido a um warning gerado pelo Console de Javascript que "enxergava" uma redeclaracao de "evt" decidi declara-la uma vez e alterar ser valor posteriormente  

        if (document.all){evt=event.keyCode;} // caso seja IE 
        else{evt = e.charCode;}    // do contrario deve ser Mozilla 
O cï¿½digo a seguir teste apenas em FireFox e Internet Explorer 6 e funcionou perfeitamente. Caso vc tenha algum problema com esta funcao por favor entre em contato 
*/ 
        var evt= (e.keyCode?e.keyCode:e.charCode); 
        var chr= String.fromCharCode(evt);    // pegando a tecla digitada 
        // Se o cï¿½digo for menor que 20 ï¿½ porque deve ser caracteres de controle 
        // ex.: <ENTER>, <TAB>, <BACKSPACE> portanto devemos permitir 
        // as teclas numï¿½ricas vao de 48 a 57 
        return (evt <20 || (evt >47 && evt<58) || (args.indexOf(chr)>-1 ) ); 
    } 

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' ï¿½requerido.\n'; }
  } if (errors) alert('Ocorreram os Seguintes Erros:\n'+errors);
  document.MM_returnValue = (errors == '');
}

function auto_fone(campo){
  texto = campo.value;
  if( texto.length == 0 ) {
	texto += '(';
    campo.value = texto;  
  } else if( texto.length == 3 ) {
	texto += ') ';
    campo.value = texto;
  } else if( texto.length == 9 ) {
	texto += '-';
    campo.value = texto;
  }
}

function auto_hora(campo) {
  texto = campo.value;    
  if( texto.length == 2 ) {
	if (texto > 23){
	   alert("Hora Invï¿½lida.");
	   campo.value = "";
	   campo.focus();
	}else{
	  texto += ':';
      campo.value = texto;		
	}
  }
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function marcarTodos() {
	if (document.getElementById("ckall").value == '0') {
		document.getElementById("ckall").value = '1';
		for (i = 0; i < document.forms[0].length; i++) {
			if (document.forms[0].elements[i].type == 'checkbox') {
				document.forms[0].elements[i].checked = true;
			}
		}
	} else {
		document.getElementById("ckall").value = '0';
		for (i = 0; i < document.forms[0].length; i++) {
			if (document.forms[0].elements[i].type == 'checkbox') {
				document.forms[0].elements[i].checked = false;
			}
		}		
	}
}

function numeroChecados2() {
	var aux = 0;
	for (i = 0; i < document.forms[0].length; i++) {
		if (document.forms[0].elements[i].type == 'checkbox' && document.forms[0].elements[i].id != 'dbListCheckAll_1') {
			if (document.forms[0].elements[i].checked) {
				aux = document.forms[0].elements[i].value;
			}
		}
	}
	return aux;
}

// verifica o numero de checkboxs checados
function numeroChecados() {
	var aux = 0;
	for (i = 0; i < document.forms[0].length; i++) {
		if (document.forms[0].elements[i].type == 'checkbox' && document.forms[0].elements[i].id != 'dbListCheckAll_1') {
			if (document.forms[0].elements[i].checked) {
				aux += 1;
			}
		}
	}
	return aux;
}

function checkAll() {
	if (document.getElementById("ckall").value == '0') {
		document.getElementById("ckall").value = '1';
		var x   = 0;

		for (i = 0; i < document.forms[0].length; i++) {

			if (document.forms[0].elements[i].type == 'checkbox') {
				document.forms[0].elements[i].checked = true;
				
				var cel = "";
				cel     = "cel"+x;
				document.getElementById(cel).style.backgroundColor = "#FFF868";
				x++;
			}
		}
	} else {
		document.getElementById("ckall").value = '0';
		var x = 0;		
		for (i = 0; i < document.forms[0].length; i++) {
			if (document.forms[0].elements[i].type == 'checkbox') {
				document.forms[0].elements[i].checked = false;

				var cel = "";
				cel 	= "cel"+x;
				if(x % 2 == 0){
				  document.getElementById(cel).style.backgroundColor = "#EEE";					
				}else{
				  document.getElementById(cel).style.backgroundColor = "#FFF";
				}
				x++;
			}
		}		
	}
}

function checkGroup(radioPai) {
	var id = "trans_" + radioPai;
	if (document.getElementById(id).checked == false) {
		for (i = 0; i < document.forms[0].length; i++) {
			if (document.forms[0].elements[i].type == 'checkbox' && document.forms[0].elements[i].id.indexOf(id) != -1) {
				document.forms[0].elements[i].checked = true;
			}
		}
	} else {
		for (i = 0; i < document.forms[0].length; i++) {
			if (document.forms[0].elements[i].type == 'checkbox' && document.forms[0].elements[i].id.indexOf(id) != -1) {
				document.forms[0].elements[i].checked = false;
			}
		}		
	}
}

function check(radio,radioPai) {
	var id 		= "trans_" + radioPai + "_" + radio;
	var idPai	= "trans_" + radioPai;
	if (!document.getElementById(id).checked) {
		document.getElementById(idPai).checked = true;
		document.getElementById(id).checked = true;
	} else {
		document.getElementById(id).checked = false;
	}
}

function valida_lista(){
	var acao   = document.getElementById("acao").value;
    document.getElementById("form1").target = "_self";
	if (numeroChecados() != 1){
		alert("Selecione apenas um Checkbox para alterar.");
	}else{
		mudarAction("form1", acao);
		document.form1.submit();
	}
}


function valida_ts(acao){	
  if(confirm("Confirma a Exclusï¿½o deste Sorterio Adicionado ?")){
    redir(acao);
  }
}

function apagar_lista(){
	var acao = document.form1.acaoexc.value;	
	if (numeroChecados() == 0){	
		alert("Selecione um ou mais registros para exclusï¿½o.");
	}else{
	  if(confirm("Confirma a Exclusï¿½o do(s) Registro(s) ?")){
	    document.getElementById("form1").target = "_self";
		mudarAction("form1", acao);		  
		document.form1.submit();
	  }
	}
}

function aprovar_lista(){
	var acao = document.form1.acaoaprovar.value;	
	if (numeroChecados() == 0){	
		alert("Selecione um ou mais registros para aprovaï¿½ï¿½o.");
	}else{
	  if(confirm("Confirma a Aprovaï¿½ï¿½o do(s) Registro(s) ?")){
	    document.getElementById("form1").target = "_self";
		mudarAction("form1", acao);		  
		document.form1.submit();
	  }
	}
}

function reprovar_lista(){
	var acao = document.form1.acaoreprovar.value;	
	if (numeroChecados() == 0){	
		alert("Selecione um ou mais registros para reprovaï¿½ï¿½o.");
	}else{
	  if(confirm("Confirma a Reprovaï¿½ï¿½o do(s) Registro(s) ?")){
	    document.getElementById("form1").target = "_self";
		mudarAction("form1", acao);		  
		document.form1.submit();
	  }
	}
}



function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' ï¿½requerido.\n'; }
  } if (errors) alert('Ocorreram os Seguintes Erros:\n'+errors);
  document.MM_returnValue = (errors == '');
}

function valida_pdf(){
  var acao   = document.getElementById("acaopdf").value;
  document.form1.target = "blank";
  mudarAction("form1", acao);
  document.form1.submit();
}

function abrirCal(){
  window.open("../lib/cal.php",null,"height=150,width=280,status=no,location=no,resizable=no");
}

function abrirJanela(id){
  window.open("../lib/semanas.php?id="+id,null,"height=150,width=280,status=no,location=no,resizable=no");
}


function fechaCal(data){
	window.opener.document.getElementById('data').value = data;
	window.close();
}

function proximo(e, campo){
	if (e.keyCode == 13){
		campo.focus();
	}	
}

function proximo2(e){
	if (e.keyCode == 13){ 	
		document.getElementById("btn3").onclick();
	}
}

function marcar(x, cor_anterior){	
	var chk  = "cod"+x;
	var cel  = "cel"+x;  
	var cel2 = document.getElementById(cel);
	
	if (document.getElementById(chk).checked == true){
		document.getElementById(chk).checked = false;  	
		cel2.style.backgroundColor = cor_anterior;
	}else{
		document.getElementById(chk).checked = true;  	  
		cel2.style.backgroundColor = "#FFF868";	
	}
}

function mouseOut(cor, linha, x){
	var chk = "cod"+x;

	if (document.getElementById(chk).checked == true){
	  linha.style.background = "#FFF868";
	}else{
	  linha.style.background = cor;
	}
}

function mouseOver(cor, linha){
	linha.style.background = cor;
}

function validaCheck(chk){
	if (chk.checked){
		document.getElementById("sede").value = "S";
		document.getElementById("exibeBloco").style.visibility = "visible";
	}else{
		document.getElementById("sede").value = "N";
		document.getElementById("exibeBloco").style.visibility = "hidden";
	}
}

function submitenter(myfield,e){
    var keycode;
    if (window.event){
        keycode = window.event.keyCode;
    }else if (e) {
        keycode = e.which;
    }else{
        return true;
    }

    if (keycode == 13){
        //myfield.form.submit();
        document.getElementById("btn3").onclick();
        return false;
    }else{
        return true;
    }
}

function hab_desa(div1, div2){
	if (document.getElementById(div1).style.visibility == "visible"){
		document.getElementById(div1).style.visibility = "hidden";
		document.getElementById(div2).style.visibility = "visible";
	}else{	
		document.getElementById(div2).style.visibility = "hidden";
		document.getElementById(div1).style.visibility = "visible"
	}
}

function visualiza_tiporel(valor){
	if (valor == ""){		
		document.getElementById("tipoRel").style.visibility = "visible"
	}else{
		document.getElementById("tipoRel").style.visibility = "hidden";
	}
}


function focos(campo){
	document.getElementById(campo).style.background = "#BFEFFF";
}

function naofocos(campo){
	document.getElementById(campo).style.background = "#FFFFFF";
}

function uncheckAll(){

	document.getElementById("ckall").value = '0';
	var x   = 0;		
	var cel = "";	
	for (i = 0; i < document.forms[0].length; i++) {
		if (document.forms[0].elements[i].type == 'checkbox') {
			document.forms[0].elements[i].checked = false;
		/*
			cel = "";			
			cel = "cel"+x;
			if(x % 2 == 0){
			  document.getElementById(cel).style.backgroundColor = "#EEE";					
			}else{
			  document.getElementById(cel).style.backgroundColor = "#FFF";
			}
			*/
			x++;
		}
	}
}

function validaForm(){
           d = document.cadastro;
           //validar nome
           if (d.nome.value == ""){
                     alert("O campo " + d.nome.name + " deve ser preenchido!");
                     d.nome.focus();
                     return false;
           }

}
function calcula_pedido(){
	if (document.getElementById("valor").value == "") document.getElementById("valor").value = 0;
	if (document.getElementById("qtd").value == "") document.getElementById("qtd").value = 0;	
	
	var valor = 0;
	alert(document.getElementById("valor").value * document.getElementById("qtd").value);
	valor = moeda(document.getElementById("valor").value * document.getElementById("qtd").value);
	alert(valor);
	//	document.getElementById("exibirTOTAL").innerHTML = 
}

function moeda(z){
	v = z.value;
	v=v.replace(/\D/g,"") // permite digitar apenas numero
	v=v.replace(/(\d{1})(\d{14})$/,"$1.$2") // coloca ponto antes dos ultimos digitos
	v=v.replace(/(\d{1})(\d{11})$/,"$1.$2") // coloca ponto antes dos ultimos 11 digitos
	v=v.replace(/(\d{1})(\d{8})$/,"$1.$2") // coloca ponto antes dos ultimos 8 digitos
	v=v.replace(/(\d{1})(\d{5})$/,"$1.$2") // coloca ponto antes dos ultimos 5 digitos
	v=v.replace(/(\d{1})(\d{1,2})$/,"$1,$2") // coloca virgula antes dos ultimos 2 digitos
	z.value = v;
} 

function valida_filtro_sigater(){
	var form, vazios;
	//setar vars
	form   = document.forms['form1'];	//pegar o nome do form a verificar
	vazios = Array();
	
	//	se um dos campos referentes ï¿½ data estiverem setados, todos os outros campos referentes
	//	a data deverï¿½o estar setados tambï¿½m.
	if (((form.mesini.value != '') || (form.anoini.value != '') || (form.mesfim.value != '') || (form.anofim.value != ''))
			&& ((form.mesini.value == '') || (form.anoini.value == '') || (form.mesfim.value == '') || (form.anofim.value == '')))	{

		if(form.mesini.value == '')	vazios.push(form.mesini.id);
		if(form.anoini.value == '')	vazios.push(form.anoini.id);
		if(form.mesfim.value == '')	vazios.push(form.mesfim.id);
		if(form.anofim.value == '')	vazios.push(form.anofim.id);

		alert('Selecione todos os campos referentes a data');

		marcarObrigatorios(vazios);		
		
		form.cpf.value = "1";
		getsfiltroSigater('lista_sigater.php', '', 'codtp', '');
		form.cpf.value = "";
		return null;
	}

	getsfiltroSigater('lista_sigater.php', '', 'codtp', '');
	
}

function visualizar_registro_completo_sigater(id){
	document.getElementById("interno").style.display="none"; 
	getsfiltroSigater2('lista_sigater.php', id);
}

function valida_filtro_rde()	{
	
	var form, vazios;
	//setar vars
	form   = document.forms['form1'];	//pegar o nome do form a verificar
	vazios = Array();
	
	if(typeof(form.selgere) != 'undefined'){	selgere = form.selgere.value;	}
	if(typeof(form.muni) != 'undefined'){	muni = form.muni.value;	}
		
	proj = form.proj.value;			
	ativ = form.ativ.value;
	
	//	se um dos campos referentes ï¿½ data estiverem setados, todos os outros campos referentes
	//	a data deverï¿½o estar setados tambï¿½m.
	if ((form.mes.value != '') && (form.ano.value == ''))	{
		vazios.push(form.ano.id);
		alert('Selecione o ano');
		marcarObrigatorios(vazios);
		return null;
	}

	//getsfiltroSigater('lista_sigater.php', '', 'codtp', '');
	getsfiltroRDE('lista_rde.php');
	
}


function finalizar(){
	if(confirm("Confirma a Finalizaï¿½ï¿½o do Pedido ?")){
		document.form1.submit();	
	}
}


function PrintElementID(pg) {
        var oPrint, oJan;
        oPrint  = window.document.getElementById("resultado").innerHTML;
        oJan    = window.open(pg);
        oJan.document.write(oPrint);
        oJan.history.go();
        oJan.window.print();
}

function mascaraMoeda(objTextBox, SeparadorMilesimo, SeparadorDecimal, e){
    var sep = 0;
    var key = '';
    var i = j = 0;
    var len = len2 = 0;
    var strCheck = '0123456789';
    var aux = aux2 = '';
    var whichCode = (window.Event) ? e.which : e.keyCode;
    if (whichCode == 13) return true;
    key = String.fromCharCode(whichCode); // Valor para o código da Chave
    if (strCheck.indexOf(key) == -1) return false; // Chave inválida
    len = objTextBox.value.length;
    for(i = 0; i < len; i++)
    if ((objTextBox.value.charAt(i) != '0') && (objTextBox.value.charAt(i) != SeparadorDecimal)) break;
    aux = '';
    for(; i < len; i++)
    if (strCheck.indexOf(objTextBox.value.charAt(i))!=-1) aux += objTextBox.value.charAt(i);
    aux += key;
    len = aux.length;
    if (len == 0) objTextBox.value = '';
    if (len == 1) objTextBox.value = '0'+ SeparadorDecimal + '0' + aux;
    if (len == 2) objTextBox.value = '0'+ SeparadorDecimal + aux;
    if (len > 2) {
    aux2 = '';
    for (j = 0, i = len - 3; i >= 0; i--) {
    if (j == 3) {
    aux2 += SeparadorMilesimo;
    j = 0;
    }
    aux2 += aux.charAt(i);
    j++;
    }
    objTextBox.value = '';
    len2 = aux2.length;
    for (i = len2 - 1; i >= 0; i--)
    objTextBox.value += aux2.charAt(i);
    objTextBox.value += SeparadorDecimal + aux.substr(len - 2, len);
    }
    return false;
}

function incluirBTN(url){
    document.location.href = url+"&empresa="+document.getElementById("empresa").value;
}

function limparDIV(){
    document.getElementById("resultado").innerHTML = "";
}

function limparPRD(){

    document.getElementById("grupo").value               = "";
    document.getElementById("subgrupo").value            = "";
    document.getElementById("linha").value               = "";
    document.getElementById("fabri").value               = "";
}

function valida_filtro_ace()	{
	
	var form, vazios;
	//setar vars
	form   = document.forms['form1'];	//pegar o nome do form a verificar
	vazios = Array();
	
	if(typeof(form.selgere) != 'undefined'){	selgere = form.selgere.value;	}
	if(typeof(form.muni) != 'undefined'){	muni = form.muni.value;	}
		
	//proj = form.proj.value;			
	//ativ = form.ativ.value;
	
	//	se um dos campos referentes ï¿½ data estiverem setados, todos os outros campos referentes
	//	a data deverï¿½o estar setados tambï¿½m.
	if ((form.mes.value != '') && (form.ano.value == ''))	{
		vazios.push(form.ano.id);
		alert('Selecione o ano');
		marcarObrigatorios(vazios);
		return null;
	}
 
	//getsfiltroSigater('lista_sigater.php', '', 'codtp', '');
	getsfiltroACE('lista_ace.php');	
}

function valida_qtd_exec(e, valor, param){
	if (e.keyCode == 13){
	   pega_valor2(valor, 'pega_atualiza_qtd_exec', param);
	}
}

function valida_qtd_exec2(e, valor, param){
	if (e.keyCode == 13){
	   pega_valor2(valor, 'pega_atualiza_qtd_exec2', param);
	}
}


function limpaDTACOM(){
    if (document.getElementById("dtini").value == ""){        
        document.getElementById("dtfim").value = "";
    } 
}

/*
function calcula_qtds(){

    //alert(document.getElementById('dtini').value+' ==== '+document.getElementById('dtfim').value);
    if (document.getElementById('tipo').value != "" && document.getElementById('mun').value == ""){
        alert(document.getElementById('tipo').value);
        pega_valor2(document.getElementById('tipo').value, 'pega_municipios', document.getElementById('tipo').value);
        
    }else if (document.getElementById('mun').value != ""){

        pega_valor2(document.getElementById('mun').value, 'pega_exibir_qtd', document.getElementById('tipo').value);

    }else{

        if (document.getElementById("conso1").checked){
            pega_valor2('RD', 'pega_consolidado', document.getElementById("ids").value);
        }else{
            pega_valor2('GR', 'pega_consolidado', document.getElementById("idreg").value);
        }
    }
}
*/

function carregaSelect(id, nome){
    var selPAA = document.getElementById('selPAA');

    if (document.getElementById("ckbMunicipio_"+id).checked == true){

        //ADICIONANDO...
        var opt   = document.createElement('option');
        opt.text  = nome;
        opt.value = id;

		selPAA.options.add(opt);

    }else{
        //REMOVENDO...
		for (i=selPAA.length-1;i>=0; i--){
			if (selPAA.options[i].text == nome){
				selPAA.options[i] = null;	
			}
		}
    }
}


function validachecks(){
    
    var unid = new Array();
    var t    = 0;
    
    for (i=0;i<document.forms[0].length; i++) {
    	if (document.forms[0].elements[i].type == 'checkbox' && document.forms[0].elements[i].checked == true) {
    	   unid[t] = document.forms[0].elements[i].value;
           t++;
    	}
    }

}