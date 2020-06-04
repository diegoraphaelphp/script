var req;

function loadXMLDoc4(url){
    req = null;
    // Procura por um objeto nativo (Mozilla/Safari)
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
        req.onreadystatechange = processReqChange4;
        req.open("GET", url, true);
        req.send(null);
    // Procura por uma versao ActiveX (IE)
    } else if (window.ActiveXObject) {
        req = new ActiveXObject("Microsoft.XMLHTTP");
        if (req) {
            req.onreadystatechange = processReqChange4;
            req.open("GET", url, true);
            req.send();
        }
    }
}

function processReqChange4(){
    // apenas quando o estado for "completado"
    document.getElementById('resultado').innerHTML = "<span style='font-family:Verdana, Arial, Helvetica, sans-serif;font-size:11px;font-weight:bold;color:#000066;font-style:normal;text-decoration:none;'><img src='img/ajax-loader.gif' />&nbsp;Carregando...</span>";
    if (req.readyState == 4) {
        // apenas se o servidor retornar "OK"
        if (req.status == 200) {
            // procura pela div id="resultado" e insere o conteudo
            // retornado nela, como texto HTML
            document.getElementById('resultado').innerHTML = req.responseText;
        } else {
            alert("Houve um problema ao obter os dados:\n" + req.statusText);
        }
    }
}

var req;

function loadXMLDoc2(url){
    req = null;
	url = "../consultas/"+url;
    //alert(url);	
    // Procura por um objeto nativo (Mozilla/Safari)
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
        req.onreadystatechange = processReqChange2;
        req.open("GET", url, true);
        req.send(null);
    // Procura por uma versao ActiveX (IE)
    } else if (window.ActiveXObject) {
        req = new ActiveXObject("Microsoft.XMLHTTP");
        if (req) {
            req.onreadystatechange = processReqChange2;
            req.open("GET", url, true);
            req.send();
        }
    }
}

function processReqChange2(){
    // apenas quando o estado for "completado"
    document.getElementById('resultado').innerHTML = "<span style='font-family:Verdana, Arial, Helvetica, sans-serif;font-size:11px;font-weight:bold;color:#000066;font-style:normal;text-decoration:none;'><img src='../img/ajax-loader.gif' />&nbsp;Carregando...</span>";
    if (req.readyState == 4) {
        if (req.status == 200) {			  			
            document.getElementById('resultado').innerHTML = req.responseText;			
        }else{
            alert("Houve um problema ao obter os dados:\n" + req.statusText);
        }
    }
}

function gambi(){
    var cat  = "";
    for (i=0;i<document.form1.cate.length;i++){
		cat = cat + document.form1.cate[i].value+"@";
	}
    document.getElementById('listamod').value = cat;    
}

function getsfiltroUSU(arq, cod, ordem, tpord){	
	var pag    = document.getElementById("pag").value;
	var nome   = document.getElementById("nome").value;
	var status = document.getElementById("status").value;
	var lista  = document.getElementById("lista").value;	
	var lista2 = document.getElementById("lista2").value;
    var gere   = document.getElementById("gere").value;
    var muni   = document.getElementById("muni").value;
    var cat  = "&categ=";	
	
	for (i=0;i<document.form1.cate.length;i++){
		cat = cat + document.form1.cate[i].value+"@";
	}

	var par  = "&status="+status+"&nome="+nome+"&ordem="+ordem+"&tpordem="+tpord+"&lista="+lista+"&lista2="+lista2+"&gere="+gere+"&muni="+muni+cat;

	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value;;

    loadXMLDoc2(arq+parms);
}

function getsfiltroDANFE(arq, cod, ordem, tpord){	
	var cpf   = document.getElementById("cpf").value;
	var danfe = document.getElementById("danfe").value;
	var tipo  = "J";

	if (document.getElementById("tipo1").checked == true){
		tipo = "F";
	}
	
	var parms = "?cpf="+cpf+"&danfe="+danfe+"&tipo="+tipo;

    loadXMLDoc4(arq+parms);
}

function getsfiltro1(arq, cod, ordem, tpord){	
	var pag    = document.getElementById("pag").value;
	var nome   = document.getElementById("nome").value;
	var status = document.getElementById("status").value;
	var par  = "&status="+status+"&nome="+nome+"&ordem="+ordem+"&tpordem="+tpord;	
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value;

    loadXMLDoc2(arq+parms);
}

function getsfiltroProjetos(arq, cod, ordem, tpord){	
	var pag    = document.getElementById("pag").value;
	var nome   = document.getElementById("nome").value;
	var status = document.getElementById("status").value;
    var btn2   = document.getElementById("btn2").value;
	var par    = "&status="+status+"&nome="+nome+"&ordem="+ordem+"&tpordem="+tpord+"&btn2="+btn2;
	
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value;

    loadXMLDoc2(arq+parms);
}

function getsfiltroFAM(arq, cod, ordem, tpord){	
	var pag    = document.getElementById("pag").value;
	var nome   = document.getElementById("nome").value;
	var status = document.getElementById("status").value;
    var idmuni = document.getElementById("idmuni").value;
    var dtini  = document.getElementById("dtini").value;
    var dtfim  = document.getElementById("dtfim").value;
    
	var par  = "&status="+status+"&nome="+nome+"&ordem="+ordem+"&tpordem="+tpord+"&idmuni="+idmuni+"&dtini="+dtini+"&dtfim="+dtfim;	
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value;

    loadXMLDoc2(arq+parms);
}



function getsfiltroVEI(arq, cod, ordem, tpord){	
	var pag    = document.getElementById("pag").value;
	var nome   = document.getElementById("nome").value;
    var idmuni = document.getElementById("idmuni").value;
    var ano    = document.getElementById("ano").value;
	var status = document.getElementById("status").value;
    var xips   = document.getElementById("xips").value;

	var par  = "&status="+status+"&nome="+nome+"&ordem="+ordem+"&tpordem="+tpord+"&idmuni="+idmuni+"&ano="+ano+"&xips="+xips;	
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value;

    loadXMLDoc2(arq+parms);
}

function getsfiltroMVEI(arq, cod, ordem, tpord){	
	var pag    = document.getElementById("pag").value;
	var nome   = document.getElementById("nome").value;
    var idmuni = document.getElementById("idmuni").value;
    var dtini  = document.getElementById("dtini").value;
    var dtfim  = document.getElementById("dtfim").value;
	var status = document.getElementById("status").value;
    var usu    = document.getElementById("usu").value;
    var xips   = document.getElementById("xips").value;

	var par  = "&status="+status+"&nome="+nome+"&ordem="+ordem+"&tpordem="+tpord+"&idmuni="+idmuni+"&dtini="+dtini+"&dtfim="+dtfim+"&usu="+usu+"&xips="+xips;	
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value;

    loadXMLDoc2(arq+parms);
}

function getsfiltroGRPPROD(arq, cod, ordem, tpord){	
	var pag     = document.getElementById("pag").value;
	var nome    = document.getElementById("nome").value;
	var status  = document.getElementById("status").value;
    var empresa = document.getElementById("empresa").value;
    
	var par  = "&status="+status+"&nome="+nome+"&ordem="+ordem+"&tpordem="+tpord+"&empresa="+empresa;	
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value;

    loadXMLDoc2(arq+parms);
}

function getsfiltroATVNFE(arq, cod, ordem, tpord){	
	var pag     = document.getElementById("pag").value;
	var nome    = document.getElementById("nome").value;
	var status  = document.getElementById("status").value;
    var empresa = document.getElementById("empresa").value;
    
	var par  = "&status="+status+"&nome="+nome+"&ordem="+ordem+"&tpordem="+tpord+"&empresa="+empresa;	
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value;

    loadXMLDoc2(arq+parms);
}

function getsfiltroPRODEMP(arq, cod, ordem, tpord){	
	var pag     = document.getElementById("pag").value;
	var nome    = document.getElementById("nome").value;
	var status  = document.getElementById("status").value;
    var empresa = document.getElementById("empresa").value;
    var usu     = document.getElementById("usu").value;
    
	var par  = "&status="+status+"&nome="+nome+"&ordem="+ordem+"&tpordem="+tpord+"&empresa="+empresa+"&usu="+document.getElementById("usu").value;	
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value;

    loadXMLDoc2(arq+parms);
}

function getsfiltroCLI(arq, cod, ordem, tpord){	
	var pag     = document.getElementById("pag").value;
	var nome    = document.getElementById("nome").value;
	var status  = document.getElementById("status").value;
    var tipo    = document.getElementById("tipo").value;
    var empresa = document.getElementById("empresa").value;
    
	var par  = "&status="+status+"&nome="+nome+"&ordem="+ordem+"&tpordem="+tpord+"&tipo="+tipo+"&empresa="+empresa;
    	
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value;

    loadXMLDoc2(arq+parms);
}

function getsfiltroMUNI(arq, cod, ordem, tpord){

	var pag    = document.getElementById("pag").value;
	var nome   = document.getElementById("nome").value;
	var status = document.getElementById("status").value;
    var gere   = document.getElementById("gere").value;
    var dese   = document.getElementById("dese").value;
    
	var par  = "&dese="+dese+"&gere="+gere+"&status="+status+"&nome="+nome+"&ordem="+ordem+"&tpordem="+tpord;	
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value;

    loadXMLDoc2(arq+parms);
}

function getsfiltroFAMRDE(arq, cod, ordem, tpord){
    
	var gere   = document.getElementById("gere").value;
	var muni   = document.getElementById("muni").value;
    var pesq   = document.getElementById("pesq").value;
	var mes    = document.getElementById("mes").value;
	var mes2   = document.getElementById("mes2").value;
    var proj   = document.getElementById("proj").value;
    var ano    = document.getElementById("ano").value;
    if (document.getElementById("meta").checked) var  meta = "S"; else var meta = "";
    
	var par  = "?meta="+meta+"&muni="+muni+"&ano="+ano+"&pesq="+pesq+"&mes="+mes+"&mes2="+mes2+"&gere="+gere+"&proj="+proj+"&mod="+document.getElementById("mod").value;
    
    loadXMLDoc2(arq+par);
}

function getsfiltroACEUSU(arq, cod, ordem, tpord){	

	var unid   = document.getElementById("unid").value;
	var muni   = document.getElementById("muni").value;	
	var nome   = document.getElementById("nome").value;
	var status = document.getElementById("status").value;
	var dtini  = document.getElementById("dtini").value;
	var dtfim  = document.getElementById("dtfim").value;
	var agrupa = document.getElementById("agrupa").value;	
	
	var mods   = "&mods=";		
	
	for (i=0;i<document.form1.mods.length;i++){
		mods = mods + document.form1.mods[i].value+"@";
	}

	var par  = "?agrupa="+agrupa+"&muni="+muni+"&status="+status+"&nome="+nome+"&unid="+unid+"&dtini="+dtini+"&dtfim="+dtfim+"&dtfim="+dtfim+mods+"&mod="+document.getElementById("mod").value;

	loadXMLDoc2(arq+par);

}

function getsfiltroPRD(arq, cod, ordem, tpord){	

	var pag     = document.getElementById("pag").value;
	var nome    = document.getElementById("nome").value;
	var dtini   = document.getElementById("dtini").value;
	var dtfim   = document.getElementById("dtfim").value;
    var empresa = document.getElementById("empresa").value;
	
	var par  = "&dtfim="+dtfim+"&dtini="+dtini+"&nome="+nome+"&ordem="+ordem+"&tpordem="+tpord+"&empresa="+empresa;	
	if (pag == "") pag = 10;
	
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value;

    loadXMLDoc2(arq+parms);
}

function getsfiltroCOMPRAS(arq, cod, ordem, tpord){	
	var pag    		= document.getElementById("pag").value;
	var nome   		= document.getElementById("nome").value;
	var aca 		= document.getElementById("aca").value;
	var fonte 	    = document.getElementById("fonte").value;
	var solicitante = document.getElementById("solicitante").value;
	var und 		= document.getElementById("und").value;
	var status 		= document.getElementById("status").value;

	var par  = "&und="+und+"&solicitante="+solicitante+"&fonte="+fonte+"&aca="+aca+"&status="+status+"&nome="+nome+"&ordem="+ordem+"&tpordem="+tpord;
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value;
    loadXMLDoc2(arq+parms);
}

function getsfiltroPEDIDOS(arq, cod, ordem, tpord){	
	var pag     = document.getElementById("pag").value;
	var id      = document.getElementById("id").value;
	var idcli   = document.getElementById("idcli").value;	

	var par  = "&id="+id;
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value+"&idcli="+document.getElementById("idcli").value+"&empresa="+document.getElementById("empresa").value;
    loadXMLDoc2(arq+parms);
}

function getsfiltroPEDIDOS2(arq, cod, ordem, tpord){	
	var pag    	  = document.getElementById("pag").value;
	var nome   	  = document.getElementById("nome").value;
	var numpedido = document.getElementById("numpedido").value;
	var seque     = document.getElementById("seque").value;	
	var dtini     = document.getElementById("dtini").value;
	var dtfim 	  = document.getElementById("dtfim").value;
	var tipo 	  = document.getElementById("tipo").value;	
	var status 	  = document.getElementById("status").value;
    var empresa   = document.getElementById("empresa").value;

	var par  = "&seque="+seque+"&dtini="+dtini+"&dtfim="+dtfim+"&numpedido="+numpedido+"&status="+status+"&nome="+nome+"&ordem="+ordem+"&tpordem="+tpord+"&tipo="+tipo+"&empresa="+empresa;
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value;
    loadXMLDoc2(arq+parms);
}

function getsfiltroNFE(arq, cod, ordem, tpord){	
	var pag    	  = document.getElementById("pag").value;
	var dtini     = document.getElementById("dtini").value;
	var dtfim 	  = document.getElementById("dtfim").value;
	var tipo 	  = document.getElementById("tipo").value;
    var empresa   = document.getElementById("empresa").value;

	var par  = "&dtini="+dtini+"&dtfim="+dtfim+"&ordem="+ordem+"&tpordem="+tpord+"&tipo="+tipo+"&empresa="+empresa;
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value;
    loadXMLDoc2(arq+parms);
}

function getsfiltroMOV(arq, cod, ordem, tpord){	
	var pag    = document.getElementById("pag").value;
	var mun    = document.getElementById("mun").value;
	var con_id = document.getElementById("con_id").value;
	var status = document.getElementById("status").value;
	var venc   = document.getElementById("venc").value;
	var pagto  = document.getElementById("pagto").value;
	
	var par  = "&mun="+mun+"&status="+status+"&con_id="+con_id+"&venc="+venc+"&pagto="+pagto+"&ordem="+ordem+"&tpordem="+tpord;
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value;
    loadXMLDoc2(arq+parms);
}

function getsfiltroCT(arq, cod, ordem, tpord){	
	var pag    = document.getElementById("pag").value;
	var muni   = document.getElementById("muni").value;
	var nome   = document.getElementById("nome").value;
	var desp   = document.getElementById("desp").value;
	var grp    = document.getElementById("grp").value;
	var cont   = document.getElementById("cont").value;
	var venc   = document.getElementById("venc").value;
	var status = document.getElementById("status").value;
	
	var par  = "&muni="+muni+"&venc="+venc+"&cont="+cont+"&grp="+grp+"&desp="+desp+"&status="+status+"&nome="+nome+"&ordem="+ordem+"&tpordem="+tpord;
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value;
    loadXMLDoc2(arq+parms);
}

function getsfiltroLOC(arq, cod, ordem, tpord){	
	var pag    = document.getElementById("pag").value;
	var nome   = document.getElementById("nome").value;
	var mun    = document.getElementById("mun").value;
	var tipo   = document.getElementById("tipo").value;
	var status = document.getElementById("status").value;
	var par  = "&status="+status+"&nome="+nome+"&ordem="+ordem+"&tpordem="+tpord+"&mun="+mun+"&tipo="+tipo;	
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value;
    loadXMLDoc2(arq+parms);
}

function getsfiltroLOG(arq, cod, ordem, tpord){	
	var pag   = document.getElementById("pag").value;
	var dtini = document.getElementById("dtini").value;	
	var dtfim = document.getElementById("dtfim").value;
    var gere  = document.getElementById("gere").value;
	var usu   = document.getElementById("usu").value;
	
	if (document.getElementById("login").checked){
		var par  = "&ordem="+ordem+"&tpordem="+tpord+"&usu="+usu+"&dtini="+dtini+"&dtfim="+dtfim+"&chk=S";		
	}else{
		var par  = "&ordem="+ordem+"&tpordem="+tpord+"&usu="+usu+"&dtini="+dtini+"&dtfim="+dtfim+"&chk=N";
	}
		
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value+"&gere="+gere;
    loadXMLDoc2(arq+parms);
}

function getsfiltroLOGFILES(arq, cod, ordem, tpord){	
	var pag    = document.getElementById("pag").value;
	var dtini  = document.getElementById("dtini").value;	
	var dtfim  = document.getElementById("dtfim").value;
    var gere   = document.getElementById("gere").value;
	var muni   = document.getElementById("idmuni").value;
    var status = document.getElementById("status").value;
    var usu    = document.getElementById("usu").value;

	var par    = "&ordem="+ordem+"&tpordem="+tpord+"&muni="+muni+"&dtini="+dtini+"&dtfim="+dtfim+"&status="+status+"&usu="+usu;
		
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value+"&gere="+gere;
    loadXMLDoc2(arq+parms);
}

function getsfiltroACO(arq, cod, ordem, tpord){	
	var pag     = document.getElementById("pag").value;
	var dtini   = document.getElementById("dtini").value;
	var dtini2  = document.getElementById("dtini2").value;
	var dtfim   = document.getElementById("dtfim").value;
	var dtfim2  = document.getElementById("dtfim2").value;
	var dtprev  = document.getElementById("dtprev").value;
	var dtprev2 = document.getElementById("dtprev2").value;
	var proj    = document.getElementById("proj").value;
	var prog    = document.getElementById("prog").value;	
	var nome    = document.getElementById("nome").value;
	var status  = document.getElementById("status").value;
	
	var par  = "&ordem="+ordem+"&tpordem="+tpord+"&nome="+nome+"&dtini="+dtini+"&dtfim="+dtfim+"&dtini2="+dtini2+"&dtprev="+dtprev+"&dtprev2="+dtprev2+"&dtfim2="+dtfim2+"&status="+status+"&proj="+proj+"&prog="+prog;	
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value;
    loadXMLDoc2(arq+parms);
}

function getsfiltroAtividadesPes(arq, cod, ordem, tpord){	
	var pag     = document.getElementById("pag").value;
	var proj    = document.getElementById("proj").value;
	var prog    = document.getElementById("prog").value;
	var par  = "&ordem="+ordem+"&tpordem="+tpord+"&proj="+proj+"&prog="+prog;	
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value;
    loadXMLDoc2(arq+parms);
}



function getsfiltroPPE(arq, cod, ordem, tpord){	
	var pag        = document.getElementById("pag").value;
	var mesprevini = document.getElementById("mesprevini").value;
	var anoprevini = document.getElementById("anoprevini").value;
	var mesini     = document.getElementById("mesini").value;
	var anoini     = document.getElementById("anoini").value;
	var mesprevfim = document.getElementById("mesprevfim").value;
	var anoprevfim = document.getElementById("anoprevfim").value;
	var mesfim     = document.getElementById("mesfim").value;
	var anofim     = document.getElementById("anofim").value;
	var prog       = document.getElementById("prog").value;
	var nome       = document.getElementById("nome").value;
	var status     = document.getElementById("status").value;
	
	var par  = "&ordem="+ordem+"&tpordem="+tpord+"&nome="+nome+"&mesprevini="+mesprevini+"&anoprevini="+anoprevini+"&mesini="+mesini+"&anoini="+anoini+"&mesprevfim="+mesprevfim+"&anoprevfim="+anoprevfim+"&mesfim="+mesfim+"&anofim="+anofim+"&status="+status+"&prog="+prog;	
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value;
    loadXMLDoc2(arq+parms);
}

function getsfiltroEXEC(arq, cod, ordem, tpord){
    
	var ano       = document.getElementById("ano").value;
	var proj      = document.getElementById("proj").value;
	var mun       = document.getElementById("mun").value;
    var acess     = document.getElementById("acess").value;
    var qtd       = document.getElementById("quant").value;
    var validamod = document.getElementById("validamod").value;
    var dtini     = document.getElementById("dtini").value;
    var dtfim     = document.getElementById("dtfim").value;
    var idatv     = document.getElementById("idatv").value;

    if (document.getElementById("meta").checked) var  meta = "S"; else var meta = "";    
    if (document.getElementById("conso1").checked) var flag = "RD"; else var flag = "GR";

    loadXMLDoc2(arq+"?ano="+ano+"&proj="+proj+"&idatv="+idatv+"&mun="+mun+"&mod="+document.getElementById("mod").value+"&flag="+flag+"&idconso="+document.getElementById("tipo").value+"&acess="+acess+"&qtd="+qtd+"&meta="+meta+"&validamod="+validamod+"&dtini="+dtini+"&dtfim="+dtfim);

}

function getsfiltroPA(arq, cod, ordem, tpord){	
	var ano   = document.getElementById("ano").value;
	var proj  = document.getElementById("proj").value;
	var mun   = document.getElementById("mun").value;
    var acess = document.getElementById("acess").value;
    
    if (document.getElementById("meta").checked) var  meta = "S"; else var meta = "";
    if (document.getElementById("conso1").checked) var flag = "RD"; else var flag = "GR";
    

    loadXMLDoc2(arq+"?ano="+ano+"&proj="+proj+"&mun="+mun+"&mod="+document.getElementById("mod").value+"&flag="+flag+"&idconso="+document.getElementById("tipo").value+"&acess="+acess+"&meta="+meta+"&quant="+document.getElementById("quant").value);
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
        document.getElementById("btn3").onclick();
        return false;
    }else{
        return true;
    }
}

var req;

function loadXMLDoc3(url,valor){	
    req = null;
	url = "../consultas/"+url;
    // Procura por um objeto nativo (Mozilla/Safari)
    if (window.XMLHttpRequest) {
        req = new XMLHttpRequest();
        req.onreadystatechange = processReqChange3;
        req.open("GET", url+"&pagina="+valor, true);
        req.send(null);
    // Procura por uma versao ActiveX (IE)
    } else if (window.ActiveXObject) {
        req = new ActiveXObject("Microsoft.XMLHTTP");
        if (req) {
            req.onreadystatechange = processReqChange3;
            req.open("GET", url+"&pagina="+valor, true);
            req.send();
        }
    }
}

function processReqChange3(){
    // apenas quando o estado for "completado"
    document.getElementById('resultado').innerHTML = "<span style='font-family:Verdana, Arial, Helvetica, sans-serif;font-size:11px;font-weight:bold;color:#000066;font-style:normal;text-decoration:none;'><img src='../img/ajax-loader.gif' />&nbsp;Carregando...</span>";
    if (req.readyState == 4) {
        // apenas se o servidor retornar "OK"
        if (req.status == 200) {
            // procura pela div id="resultado" e insere o conteudo
            // retornado nela, como texto HTML
            document.getElementById('resultado').innerHTML = req.responseText;
        } else {
            alert("Houve um problema ao obter os dados:\n" + req.statusText);
        }
    }
}

function getPaginacao(valor){
	var totpg  = document.getElementById('totalpag').value;
	var numpg  = document.getElementById('numeropag').value;
	var numpag = 0;
	
	lista = valor.split("#");	
	if (lista[1] == "primeiro"){		
		numpag = parseInt(1);		
	}else if (lista[1] == "anterior"){			
		numpag = parseInt(numpg) - 1;	  	
		if (numpag<=0) numpag = parseInt(1);		
	}else if (lista[1] == "proximo"){		
		numpag = parseInt(numpg) + 1;
		if (numpag>totpg) numpag = parseInt(totpg);		
	}else{		
		numpag = totpg;
	}
	
	var arq = document.getElementById("arq").value+"&numpag="+numpag;
	loadXMLDoc3(arq, lista[0]);
}


function getsfiltroEXECUCAO(arq, cod, ordem, tpord){						
	var ano    		= document.getElementById("ano").value;	
	var projeto		= document.getElementById("projeto").value;
	var atividade	= document.getElementById("atividade").value;
	var mes 	    = document.getElementById("mes").value;
	var dtini 		= document.getElementById("dtini").value;
	var dtfim 		= document.getElementById("dtfim").value;
	var usuario		= document.getElementById("usuario").value;
	var municipio   = document.getElementById("municipio").value;

	var par  = "&ano="+ano+"&projeto="+projeto+"&atividade="+atividade+"&mes="+mes+"&dtini="+dtini+"&dtfim="+dtfim+"&usuario="+usuario+"&municipio="+municipio;
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value;
    loadXMLDoc2(arq+parms);
}

function getsfiltroSigater(arq, cod, ordem, tpord){	
	var pag        = document.getElementById("pag").value;
	var cpf        = document.getElementById("cpf").value;
	var mun 	   = document.getElementById("municipio").value;
	var mesini     = document.getElementById("mesini").value;
	var anoini     = document.getElementById("anoini").value;
	var mesfim     = document.getElementById("mesfim").value;
	var anofim     = document.getElementById("anofim").value;

	var par  = "&ordem="+ordem+"&tpordem="+tpord+"&cpf="+cpf+"&mun="+mun+"&mesini="+mesini+"&anoini="+anoini+"&mesfim="+mesfim+"&anofim="+anofim;	
	if (pag == "") pag = 10;
	var parms = "?pag="+pag+par+"&numpag=1&id="+cod+"&mod="+document.getElementById("mod").value;
    loadXMLDoc2(arq+parms);

}

function getsfiltroSigater2(arq, cod)	{	
	var parms = "?cod="+cod;			
    loadXMLDoc2(arq+parms);
}

function getsfiltroRDE(arq){	
	
	var par = "";
	var gere	= document.getElementById("selgere").value;
	var muni	= document.getElementById("muni").value;
	var proj	= document.getElementById("proj").value;
	var ativ	= document.getElementById("ativ").value;
	var mes		= document.getElementById("mes").value;
	var ano		= document.getElementById("ano").value;
        
	var par  = "&gere="+gere+"&muni="+muni+"&proj="+proj+"&ativ="+ativ+"&mes="+mes+"&ano="+ano;
	var parms = "?"+par+"&numpag=1&mod="+document.getElementById("mod").value;
    loadXMLDoc2(arq+parms);

}

function getsfiltroACE(arq, cod, ordem, tpord){
	var muni   = document.getElementById("idmuni").value;	
	var gere   = document.getElementById("gere").value;
	var mes    = document.getElementById("mes").value;
    var mes2   = document.getElementById("mes2").value;    
	var ano    = document.getElementById("ano").value;
	var par    = "?muni="+muni+"&gere="+gere+"&mes="+mes+"&ano="+ano+"&mes2="+mes2;
    if (parseInt(mes)> parseInt(mes2)){
        alert("Semana Inicial precisa ser mario que Semana Final.");
    }else{
        loadXMLDoc2(arq+par);    
    }	
}