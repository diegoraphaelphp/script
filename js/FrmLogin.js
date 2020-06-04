$(document).ready(function(){
    $("#dialog-atencao").dialog({
        autoOpen: false,
        width: 400,
        buttons: {
            "Ok": function() { 
                $($("#hddFocus").val()).focus();
                $(this).dialog("close"); 
            }
        }
    });
    
    $("#txtUsuario").focus();            
});

function ChecarAcesso(){
    if(jQuery.trim($("#txtUsuario").val()) == ""){
        $("#dialog-atencao").html("Por favor, informe o usu&aacute;rio.");
        $("#dialog-atencao").dialog('open');
        $("#hddFocus").val("#txtUsuario");
        return;
    }
    
    if(jQuery.trim($("#txtSenha").val()) == ""){
        $("#dialog-atencao").html("Por favor, informe a senha.");
        $("#dialog-atencao").dialog('open');
        $("#hddFocus").val("#txtSenha");
        return;
    }
    
    // exibe o loading
    $("#loading-login").html("<img src='img/loading-campo.gif' align='absmiddle' /> Autenticando... ");        
    
    $.post("controladores/UsuarioControlador.php", {login: $("#txtUsuario").val(), senha: $("#txtSenha").val(), acao: "ChecarAcesso"},
        function(data) {             
             
            if(data.sucesso == "true"){                
                if(data.status == "A"){                    
                    $.post("controladores/UsuarioControlador.php", {token: data.token, acao: "LiberarAcesso"},
                        function(data) {                           
                            if(data.sucesso == "true"){
                                window.location.href = 'menu.php';
                            }                            
                        }, "json"
                    );
                }else{
                    $("#loading-login").html(""); 
                    $("#dialog-atencao").html("O usu&aacute;rio est&aacute; cadastrado mas n&atilde;o est&aacute; ativado. Por favor, entre em contato com o ADMINISTRADOR do sistema para que seja realizada a libera&ccedil;&atilde;o.");        
                    $('#dialog-atencao').dialog('open'); 
                }
            }else{
                $("#loading-login").html("");  
                $("#dialog-atencao").html("<b>Acesso negado</b>. Por favor, cheque o usu&aacute;rio e senha.");                
                $('#dialog-atencao').dialog('open');        
            }
        } , "json"
    );
}