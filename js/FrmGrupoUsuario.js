var dg = $('#grid');

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
        width: 700,
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
    var valorVisualizar = "N";
    var valorIncluir = "N";
    var valorAlterar = "N";
    var valorRemover = "N";
    
    if($.trim($("#txtDescricao").val()) == ""){
        $("#hddCampoFocus").val("txtDescricao");
        $("#dialog-atencao").html("Por favor, informe a descrição do grupo de usuário.");         
        $('#dialog-atencao').dialog('open');
        return;
    }
    
    if($("#ckbStatus").is(":checked")){
        valorStatus = "I";
    }
    
    if($("#ckbVisualizar").is(":checked")){
        valorVisualizar = "S";
    }
    
    if($("#ckbIncluir").is(":checked")){
        valorIncluir = "S";
    }
    
    if($("#ckbAlterar").is(":checked")){
        valorAlterar = "S";
    }
    
    if($("#ckbRemover").is(":checked")){
        valorRemover = "S";
    } 
    
    $.post("controladores/GrupoUsuarioControlador.php", {
        acao: "Salvar",
        id: $("#hddCodigo").val(),
        descricao: $.trim($("#txtDescricao").val()),
        visualizar: valorVisualizar,
        incluir : valorIncluir,
        alterar : valorAlterar,
        remover : valorRemover,
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
            url: "controladores/GrupoUsuarioControlador.php"
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
                                       
                    $.post("controladores/GrupoUsuarioControlador.php", {
                        acao: "ConsultarDados",
                        codigo: id,                        
                        tipoPesquisa: "codigo"
                    },
                        function(data) {    
                            if(data.sucesso == "true"){
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
                                                    html += '<td bgcolor="#F4F4F4" align="right"><b>Descri&ccedil;&atilde;o</b></td>';
                                                    html += '<td>' + data.rows[0].descricao + '</td>';
                                                html += '</tr>';
                                                html += '<tr>';
                                                    var img = 'ico-naopermitido.png';
                                                
                                                    if(data.rows[0].visualizar == "S"){
                                                        img = 'ico-realizado.png';
                                                    }
                                                
                                                    html += '<td bgcolor="#F4F4F4" align="right"><b>Visualizar</b></td>';
                                                    html += '<td><img src="img/' + img + '" align="absmiddle" /></td>';
                                                html += '</tr>';
                                                html += '<tr>';
                                                    img = 'ico-naopermitido.png';
                                                
                                                    if(data.rows[0].incluir == "S"){
                                                        img = 'ico-realizado.png';
                                                    }
                                                
                                                    html += '<td bgcolor="#F4F4F4" align="right"><b>Incluir</b></td>';
                                                    html += '<td><img src="img/' + img + '" align="absmiddle" /></td>';
                                                html += '</tr>';
                                                html += '<tr>';
                                                    img = 'ico-naopermitido.png';
                                                
                                                    if(data.rows[0].alterar == "S"){
                                                        img = 'ico-realizado.png';
                                                    }
                                                    
                                                    html += '<td bgcolor="#F4F4F4" align="right"><b>Alterar</b></td>';
                                                    html += '<td><img src="img/' + img + '" align="absmiddle" /></td>';
                                                html += '</tr>';
                                                html += '<tr>';
                                                    img = 'ico-naopermitido.png';
                                                
                                                    if(data.rows[0].remover == "S"){
                                                        img = 'ico-realizado.png';
                                                    }
                                                    
                                                    html += '<td bgcolor="#F4F4F4" align="right"><b>Remover</b></td>';
                                                    html += '<td><img src="img/' + img + '" align="absmiddle" /></td>';
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
                    
                    $.post("controladores/GrupoUsuarioControlador.php", {
                        acao: "ConsultarDados",
                        codigo: id,                        
                        tipoPesquisa: "codigo"
                    },
                        function(data) {                            
                            if(data.sucesso == "true"){
                                $("#hddCodigo").val(data.rows[0].id);
                                $("#txtDescricao").val(data.rows[0].descricao);
                                
                                if(data.rows[0].visualizar == "S"){
                                    $("#ckbVisualizar").attr("checked", true);    
                                }else{
                                    $("#ckbVisualizar").attr("checked", false);    
                                }

                                if(data.rows[0].incluir == "S"){
                                    $("#ckbIncluir").attr("checked", true);    
                                }else{
                                    $("#ckbIncluir").attr("checked", false);    
                                }                                   
                                if(data.rows[0].alterar == "S"){
                                    $("#ckbAlterar").attr("checked", true);    
                                }else{
                                    $("#ckbAlterar").attr("checked", false);    
                                }

                                if(data.rows[0].remover == "S"){
                                    $("#ckbRemover").attr("checked", true);    
                                }else{
                                    $("#ckbRemover").attr("checked", false);    
                                }
                                    
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
                label: 'Desmarcar linhas selecionadas'
                ,icon: 'refresh'
                ,fn: function(){
                    $(this).datagrid('clearSelectedRow')
                }
            }
        ]
        ,mapper:[{
            name: 'id',title:'ID',width:50,align:'center'
        },{
            
            name: 'descricao',title:'Grupo Usuário',width:500,align:'left'
        },{ 
            name: 'visualizar',title:'Visualizar',width:20,align:'center'         
        },{
            name: 'incluir',title:'Incluir',width:20,align:'center'
        },{
            name: 'alterar',title:'Alterar',width:20,align:'center'
        },{
            name: 'remover',title:'Remover',width:20,align:'center'
        },{           
            name: 'status',title:'Ativo',width:20,align:'center'
        }]
    });    
}

function Cancelar(){
    if($("#hddCodigo").val() != ""){ // caso o hddCodigo seja diferente de vazio, é uma alteração
        $('#tabs').tabs().tabs('select', 0);
    }
    
    $("#hddCodigo").val("");
    $("#txtDescricao").val("");
    
    $("#ckbVisualizar").attr("checked", false);
    $("#ckbIncluir").attr("checked", false);
    $("#ckbAlterar").attr("checked", false);
    $("#ckbRemover").attr("checked", false);
    $("#ckbStatus").attr("checked", false);
}

function ExibirHelpCartao(){
    $("#dialog-help").html("<img src='img/loading.gif'/>"); 
    $("#dialog-help").html("<img src='img/help-cartao.jpg'/>");        
    $('#dialog-help').dialog('open');
}