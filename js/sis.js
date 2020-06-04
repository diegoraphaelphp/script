function marcarObrigatorios(obrig){
	for (i = 0; i < obrig.length ; i++){
		document.getElementById(obrig[i]).style.backgroundColor = "#FFCC00";
		document.getElementById(obrig[i]).style.color 			= "#000000";
	}
	setTimeout("desmarcarObrigatorios()",6000);
}

function desmarcarObrigatorios(){
	/*
		faz o inverso da funcao acima (marcarObrigatorios())
	*/
	var	form;
	
	form = document.forms[0];
	for (i = 0; i < form.length ; i++){
		if(form[i].type != "button"){
			form[i].style.backgroundColor = "#FFFFFF";
			form[i].style.color = "#000000";
		}
	}
}

//fazer buscas usando Ajax
var pedido;
var resposta = false;

function axConsulta(fld,url,param,retro,elemento){
	try {
		pedido = new XMLHttpRequest();	//FF, IE7
		//pedido.overrideMimeType('text/html');
		
	} catch (e) {
		try {
			pedido = new ActiveXObject("Msxml2.XMLHTTP");	//IE 5.x, IE 6.x
			
		} catch (e) {
			pedido = new ActiveXObject("Microsoft.XMLHTTP");	//IE 5.x, IE 6.x
		
		}
	}
	
	if (!pedido){
		alert("Para usar este recurso você precisa atualizar seu browser.");
	}
	
	url = retro + "sys/" + fld + "/" + url + ".php";
	
	//parametros
	var vars = "param=" + param;
	

    // depurando
    //alert(vars);
     
	pedido.onreadystatechange = function(){
		//verificar a resposta
		//alert("ready: " + pedido.readyState + "\nStatus: " + pedido.status);
		if (pedido.readyState == 1){
			//document.getElementById(elemento).innerHTML = "Buscando. Aguarde...";
		}else{
			if (pedido.readyState == 4){
				if (pedido.status == 200) {
					//o que fazer com a resposta do php
					resposta = pedido.responseText;
					//alert(resposta);
					if ((elemento != false) || (elemento != "")){
						document.getElementById(elemento).innerHTML = resposta;
					}
					
				} else {
					if (pedido.status == 404){
						//arquivo de consulta nao encontrado
						resposta = false;
						
					}else{
						//falha no pedido. ver o pedido.status
						alert('Erro na solicitacao.');
					}
				}
			}
		}
		
	}
	
	
	//alert(url);
	pedido.open('POST', url, true);
	
	//enviar via POST
	pedido.setRequestHeader('Content-Type',"application/x-www-form-urlencoded; charset=iso-8859-1");
	
	//cancelando o cache do browser
	pedido.setRequestHeader("Cache-Control","no-store, no-cache, must-revalidate");
	pedido.setRequestHeader("Cache-Control","post-check=0, pre-check=0");
	pedido.setRequestHeader("Pragma", "no-cache");
	
	pedido.send(vars);
}


function pause(millisecondi){
    var now = new Date();
    var exitTime = now.getTime() + millisecondi;

    while(true){
        now = new Date();
        if(now.getTime() > exitTime) return;
    }
}

function ge(id){
	return document.getElementById(id);
}