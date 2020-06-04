$(document).ready(function(){
    // tabs
    $('#tabs').tabs();     
        
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

function Salvar(){    
    if($.trim($("#txtNovaSenha").val()) == ""){        
        $("#hddCampoFocus").val("txtNovaSenha");
        $("#dialog-atencao").html("Por favor, informe a nova senha.");         
        $('#dialog-atencao').dialog('open');
        return;
    }
    
    if($.trim($("#txtConfirmaSenha").val()) == ""){
        $("#hddCampoFocus").val("txtConfirmaSenha");
        $("#dialog-atencao").html("Por favor, confirme a senha.");         
        $('#dialog-atencao').dialog('open');
        return;
    }
    
    if($.trim($("#txtConfirmaSenha").val()) != $.trim($("#txtNovaSenha").val())){
        $("#hddCampoFocus").val("txtConfirmaSenha");
        $("#dialog-atencao").html("Por favor, confirmação de senha inválida.");         
        $('#dialog-atencao').dialog('open');
        return;
    }
    
    $.post("controladores/UsuarioControlador.php", {
        acao: "AlterarSenha",
        senha: $.trim($("#txtNovaSenha").val())

    },
        function(data) {
            if(data.sucesso == "true"){
                $("#dialog-sucesso").html("Senha alterada com sucesso.");        
                $('#dialog-sucesso').dialog('open');
            }else{
                $("#dialog-atencao").html("Operação não realizada. Entre em contato com o suporte.");        
                $('#dialog-atencao').dialog('open');
            }         
        }, "json"
    );    
}

function Cancelar(){
    ExibirTela('content', 'modulos.php');
}