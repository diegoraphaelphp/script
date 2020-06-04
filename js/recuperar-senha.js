$(document).ready(function(){
    // Dialog
    $('#dialog-sucesso').dialog({
        autoOpen: false,
        width: 400,
        buttons: {
            "Ok": function() {
                Cancelar();
                $(this).dialog("close");                 
            }
        }
    });

    $('#dialog-atencao').dialog({
        autoOpen: false,
        width: 400,
        buttons: {
            "Ok": function() {
                $(this).dialog("close");                
                
                // seta o focus
                if($("#hddCampoFocus").val() != ""){
                    $("#" + $("#hddCampoFocus").val()).focus();                    
                }
            }
        }
    });
});

function Enviar(){    
    if($.trim($("#txtEmail").val()) == ""){        
        $("#hddCampoFocus").val("txtEmail");
        $("#dialog-atencao").html("Por favor, informe seu e-mail.");         
        $('#dialog-atencao').dialog('open');
        return;
    }
    
    if($.trim($("#txtEmail").val()) != ""){
        if(!ValidarEmail($.trim($("#txtEmail").val()))){
            $("#hddCampoFocus").val("txtEmail");
            $("#dialog-atencao").html("Por favor, informe um e-mail válido.");        
            $('#dialog-atencao').dialog('open');
            return;
        }
    }
    
    $("#loading-recuperar-senha").html("<br/><img src='img/loading-campo.gif' align='absmiddle' /> Enviando..."); 
    
    $.post("controladores/UsuarioControlador.php", {
        acao: "RecuperarSenha",
        email: $.trim($("#txtEmail").val())
    },
        function(data) {
            if(data.sucesso == "true"){
                $("#dialog-sucesso").html("Senha alterada com sucesso.");        
                $('#dialog-sucesso').dialog('open');
            }else{
                if(data.sucesso == "inativo"){
                    $("#loading-recuperar-senha").html("");
                    $("#dialog-atencao").html("O usuário foi identificado em nosso sistema mas não está ativo.");        
                    $('#dialog-atencao').dialog('open');
                }else{
                    if(data.sucesso == "erro_envio"){
                        $("#loading-recuperar-senha").html("");
                        $("#dialog-atencao").html("Não foi possível enviar o e-mail com a senha. Por favor, entre em contato o Administrador do sistema.");        
                        $('#dialog-atencao').dialog('open');
                    }else{
                        $("#loading-recuperar-senha").html("");
                        $("#dialog-atencao").html("<b>Não existe usuário</b> com este e-mail no sistema.");        
                        $('#dialog-atencao').dialog('open');
                    }
                }
            }           
        }, "json"
    );    
}

function Cancelar(){
    ExibirTela('content', 'modulos.php');
}