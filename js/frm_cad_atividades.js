function cadastrar(){ 
    if(jQuery.trim(jQuery("#prog").val()) == ""){
        alert("Por favor, informe o programa.");
        return;
    }
    
    if(jQuery.trim(jQuery("#proj").val()) == ""){
        alert("Por favor, informe o projeto.");
        return;
    }
    
    var acoes = Array();
    var tipos = null;
    
    var check = jQuery(".check").get();
    
    if(check != null){
        for(i=0; i<check.length; i++){
            if(check[i].checked){
                acoes.push(check[i].value);
            }
        }
    }
    
    if(acoes.length > 0){
        tipos = Array(acoes.length);        
        
        for(i=0; i<acoes.length; i++){
            tipos[i] = jQuery('input:radio[name=tipo' + acoes[i] + ']:checked').val();
        }
        
        jQuery.post("../ctrl/CtrlCadAtividade.php", { acao: "Cadastrar", "tipos[]": tipos, "acoes[]": acoes },
           function(data) {                
             if(data.sucesso == "true"){
                 alert("Cadastro realizado com sucesso.");
                 pega_valor(jQuery("#proj").val(), 'pega_proj_acoes')
             }else{
                 alert("Atividade não cadastrada.");
             }
           }, "json"
        );
    }else{
        alert("Por favor, informe as ações.");
    }
}

function marcarDesmarcar(acaoID){    
    if(jQuery("#acao" + acaoID).is(':checked')){
        jQuery("#tipo_1" + acaoID).attr("disabled", false);
        jQuery("#tipo_2" + acaoID).attr("disabled", false);
    }else{        
        jQuery('input:radio[name=tipo' + acaoID + ']').attr('checked',false);
        jQuery('input:radio[name=tipo' + acaoID + ']:nth(0)').attr('checked',true);
        
        jQuery("#tipo_1" + acaoID).attr("disabled", true);
        jQuery("#tipo_2" + acaoID).attr("disabled", true);        
    }
}

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
               alert("Alteração realizada com sucesso");
               getsfiltroAtividadesPes('lista_atividades_pes.php', '', '', 'DESC');
           }
           else{
               alert("Alteração não realizada")
           }
               
       },"json"
    );
    
}