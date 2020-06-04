function alterarTipo(){    
    var t = jQuery(".tipo").get();
    var c = jQuery(".cod").get();
    
    var cods  = Array();
    var tipos = Array();
        
    if(c != null){
        for(i=0; i<c.length; i++){
            cods.push(c[i].value);
            tipos.push(t[i].value);
        }
    }    
    
    if(cods.length == 0 || tipos.length == 0){
        alert("Por favor, selecione um registro.");
        return;
    }
    
    jQuery.post("../ctrl/CtrlCadAtividade.php", { acao: "Alterar", "tipos[]": tipos, "cods[]": cods },
       function(data) {              
           if(data.sucesso == "true"){
               alert("Alteração realizada com sucesso")
           }
           else{
               alert("Alteração não realizada")
           }
               
       },"json"
    );
    
}


