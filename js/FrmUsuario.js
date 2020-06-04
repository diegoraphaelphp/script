var dg = $('#grid');

$(document).ready(function(){
    // tabs
    $('#tabs').tabs();    
    
    $('#txtLogin').alphanumeric({allow:"."});
    $('#txtEmail').alphanumeric({allow:".@"});
    $("#txtTelefone").mask("(99) 9999.9999");
    
    // Dialog
    $('#dialog-sucesso').dialog({
        autoOpen: false,
        width: 400,
        buttons: {
            "Ok": function() {
                Cancelar();
                ExibirDadosGrid();
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
    
    $('#dialog-detalhe').dialog({
        autoOpen: false,
        width: 500,
        buttons: {
            "Ok": function() {
                $(this).dialog("close");
            }
        }
    });
    
    ExibirDadosGrid();
});

function SelecionarFiltro(){
    ExibirDadosGrid();
}

function Salvar(){ 
    var valorStatus = "A"; 
    
    if($.trim($("#txtNome").val()) == ""){
        $("#hddCampoFocus").val("txtNome");
        $("#dialog-atencao").html("Por favor, informe o nome do usuário.");         
        $('#dialog-atencao').dialog('open');
        return;
    }
    
    if($.trim($("#txtEmail").val()) == ""){
        $("#hddCampoFocus").val("txtEmail");
        $("#dialog-atencao").html("Por favor, informe o e-mail do usuário.");         
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
    
    if($.trim($("#txtLogin").val()) == ""){
        $("#hddCampoFocus").val("txtLogin");
        $("#dialog-atencao").html("Por favor, informe o login usuário.");         
        $('#dialog-atencao').dialog('open');
        return;
    }
    
    if($.trim($("#txtLogin").val()).length < 3){
        $("#hddCampoFocus").val("txtLogin");
        $("#dialog-atencao").html("Por favor, informe o login com o número de caracteres superior a três.");         
        $('#dialog-atencao').dialog('open');
        return;
    }
    
    if($.trim($("#selGrupo").val()) == ""){
        $("#hddCampoFocus").val("selGrupo");
        $("#dialog-atencao").html("Por favor, selecione o grupo do usuário.");         
        $('#dialog-atencao').dialog('open');
        return;
    }
    
    if($.trim($("#selMunicipio").val()) == ""){
        $("#hddCampoFocus").val("selMunicipio");
        $("#dialog-atencao").html("Por favor, selecione o município do usuário.");         
        $('#dialog-atencao').dialog('open');
        return;
    }
    
    if($.trim($("#selUnidade").val()) == ""){
        $("#hddCampoFocus").val("selUnidade");
        $("#dialog-atencao").html("Por favor, selecione a unidade do usuário.");         
        $('#dialog-atencao').dialog('open');
        return;
    }    
    
    if($("#ckbStatus").is(":checked")){
        valorStatus = "I";
    }
    
    $.post("controladores/UsuarioControlador.php", {
        acao: "Salvar",
        id: $.trim($("#hddCodigo").val()),
        nome: $.trim($("#txtNome").val()),
        login: $.trim($("#txtLogin").val()),
        senha: $.trim($("#hddSenha").val()),
        email: $.trim($("#txtEmail").val()),
        telefone: $.trim($("#txtTelefone").val()),
        grupo : $.trim($("#selGrupo").val()),
        municipio : $.trim($("#selMunicipio").val()),
        unidade : $.trim($("#selUnidade").val()),
        status : valorStatus

    },
        function(data) {
           if(data.sucesso == "true"){
                $("#dialog-sucesso").html("Operação realizada com sucesso.");        
                $('#dialog-sucesso').dialog('open');
            }else{
                $("#dialog-atencao").html("Operação não realizada. Entre em contato com o suporte.");        
                $('#dialog-atencao').dialog('open');
            }            
        }, "json"
    );    
}
 

function ExibirDadosGrid(){
    // limpa o grid a cada vez que a consulta é realizada
    dg.datagrid('getTbody').empty(); 
    
    var filtro = $('input:radio[name=rdbFiltro]:checked').val();
    var pesquisarPor = $("#txtPesquisa").val();
 
    dg.datagrid({
        jsonStore: {
            url: "controladores/UsuarioControlador.php"
            ,params: {acao: "Consultar", valorPesquisa: pesquisarPor, tipoPesquisa: filtro}
        }
        ,ajaxMethod: "POST"
        ,pagination: false
        ,autoLoad: true
        ,title: ''
        ,rowNumber: true
        ,onClickRow: function() {}
        ,toolBarButtons:[
            {
                label: 'Detalhe'
                ,icon: 'pencil'
                ,fn: function() {
                    // posicao cells[0] => refere-se a linha <div>1</div>, <div>2</div>, etc
                    // posicao cells[1] => refere-se as colunas do grid
                    var id = dg.datagrid('getSelectedRow')[0].cells[1].innerHTML;                    
                                       
                    $.post("controladores/UsuarioControlador.php", {
                        acao: "ConsultarDados",
                        codigo: id,                          
                        tipoPesquisa: "codigo"
                    },
                        function(data) {    
                            if(data.sucesso == "true"){                                
                               if(data.rows[0].status =="A"){
                                  var status = "Sim";
                               }else{
                                   status= "Não";
                               }
                                
                                html  = '';
                                html += '<table align="left">';
                                    html += '<tr>';
                                        html += '<td valign="top">';
                                            html += '<table cellpadding="5">';
                                                html += '<tr>';
                                                    html += '<td bgcolor="#F4F4F4" align="right"><b>ID</b></td>';
                                                    html += '<td>' + data.rows[0].id + '</td>';
                                                html += '</tr>';
                                                html += '<tr>';
                                                    html += '<td bgcolor="#F4F4F4" align="right"><b>Nome Usuário</b></td>';
                                                    html += '<td>' + data.rows[0].nome + '</td>';
                                                html += '</tr>';
                                                html += '<tr>';
                                                    html += '<td bgcolor="#F4F4F4" align="right"><b>Login</b></td>';
                                                    html += '<td>' + data.rows[0].login + '</td>';
                                                html += '</tr>';
                                                html += '<tr>';
                                                    html += '<td bgcolor="#F4F4F4" align="right"><b>Email</b></td>';
                                                    html += '<td>' + data.rows[0].email + '</td>';
                                                html += '</tr>';
                                                html += '<tr>';
                                                    html += '<td bgcolor="#F4F4F4" align="right"><b>Telefone</b></td>';
                                                    html += '<td>' + data.rows[0].telefone + '</td>';
                                                html += '</tr>';
                                                html += '<tr>';
                                                    html += '<td bgcolor="#F4F4F4" align="right"><b>Grupo Usuário</b></td>';
                                                    html += '<td>' + data.rows[0].grupoD + '</td>';
                                                html += '</tr>';
                                                html += '<tr>';
                                                    html += '<td bgcolor="#F4F4F4" align="right"><b>Municipio</b></td>';
                                                    html += '<td>' + data.rows[0].municipioD + '</td>';
                                                html += '</tr>';
                                               html += '<tr>';
                                                    html += '<td bgcolor="#F4F4F4" align="right"><b>Unidade</b></td>';
                                                    html += '<td>' + data.rows[0].unidadeD + '</td>';
                                                html += '</tr>';
                                                html += '<tr>';
                                                    html += '<td bgcolor="#F4F4F4" align="right"><b>Ativo</b></td>';
                                                    html += '<td>' + status + '</td>';
                                                html += '</tr>';
                                                
                                            html += '</table>';
                                    
                                
                                $("#dialog-detalhe").html(html);
                                $('#dialog-detalhe').dialog('open');
                            }
                        }, "json"
                    );
                }
            },{
                label: 'Alterar'
                ,icon: 'pencil'
                ,fn: function() {
                    // posicao cells[0] => refere-se a linha <div>1</div>, <div>2</div>, etc
                    // posicao cells[1] => refere-se as colunas do grid
                    var id = dg.datagrid('getSelectedRow')[0].cells[1].innerHTML;                    
                    $('#tabs').tabs().tabs('select', 1);
                    
                    $.post("controladores/UsuarioControlador.php", {
                        acao: "ConsultarDados",
                        codigo: id,                        
                        tipoPesquisa: "codigo"
                    },
                        function(data) {                            
                            if(data.sucesso == "true"){
                                $("#hddCodigo").val(data.rows[0].id);
                                $("#txtNome").val(data.rows[0].nome);
                                $("#txtLogin").val(data.rows[0].login);
                                $("#txtEmail").val(data.rows[0].email);
                                $("#txtTelefone").val(data.rows[0].telefone);
                                $("#selGrupo").val(data.rows[0].grupo);
                                $("#selMunicipio").val(data.rows[0].municipio);
                                $("#selUnidade").val(data.rows[0].unidade);
                                
                                if(data.rows[0].status == "A"){
                                    $("#ckbStatus").attr("checked", false);
                                }else{
                                    $("#ckbStatus").attr("checked", true); // se estiver inativo
                                }
                            }                            
                        }, "json"
                    );
                }
            },{
                label: 'Desmarcar linha selecionada'
                ,icon: 'refresh'
                ,fn: function(){
                    $(this).datagrid('clearSelectedRow')
                }
            }
        ]
        ,mapper:[{
            name: 'id',title:'ID',width:50,align:'center'
        },{
            name: 'nome',title:'Nome Usuário',width:350,align:'left'
        },{ 
            name: 'login',title:'Login',width:200,align:'left'
        
        },{ 
            name: 'grupo',title:'Grupo Usuário',width:200,align:'left'
        },{ 
            name: 'status',title:'Ativo',width:50,align:'left'            
       
        }]
    });    
} 

function Cancelar(){
    if($("#hddCodigo").val() != ""){ // caso o hddCodigo seja diferente de vazio, é uma alteração
        $('#tabs').tabs().tabs('select', 0);
    }
    
    $("#hddCodigo").val("");    
    $("#txtNome").val("");
    $("#txtLogin").val("");
    $("#txtSenha").val("");
    $("#txtEmail").val("");
    $("#txtTelefone").val("");
    $("#selGrupo").val("");
    $("#selMunicipio").val("");
    $("#selUnidade").val("");
    $("#loading-checar-login").html("");
}

function ChecarLogin(){
    $("#loading-checar-login").html("<img src='img/loading-campo.gif' align='absmiddle' /> Verificando..."); 
    
    if($.trim($("#txtLogin").val()) != ""){
        $.post("controladores/UsuarioControlador.php", {
            acao: "Consultar",
            valorPesquisa: $("#txtLogin").val(),
            tipoPesquisa: "checarLogin"
        },
            function(data) {
                // no caso de alteração
                // o sistema não deverá levar em conta
                // o checarLogin, já que o usuário já existe
                if($("#hddCodigo").val() != "" && data.sucesso == "true"){  
                    if($("#txtLogin").val().toUpperCase() == data.rows[0].login.toUpperCase()){
                        $("#loading-checar-login").html("");
                        $("#btnCadastrar").attr("disabled", false);
                        return; // interrompe a checagem
                    }
                }
                
                if(data.sucesso == "true"){
                    $("#loading-checar-login").html("<img src='img/ico-naopermitido.png' align='absmiddle' /> <span style='color: red;'>Login já cadastrado.</span>");          
                    $("#btnCadastrar").attr("disabled", true);
                }else{
                    $("#loading-checar-login").html("<img src='img/ico-realizado.png' align='absmiddle' /> <span style='color: green;'>Login disponível.</span>");
                    $("#btnCadastrar").attr("disabled", false);
                }
            }, "json"
        );
    }else{
        $("#loading-checar-login").html(""); 
    }
}