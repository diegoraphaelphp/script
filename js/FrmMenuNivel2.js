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
   
    if($.trim($("#txtNome").val()) == ""){
        $("#hddCampoFocus").val("txtNome");
        $("#dialog-atencao").html("Por favor, informe o nome do Menu.");         
        $('#dialog-atencao').dialog('open');
        return;
    }
    if($.trim($("#txtCaminho").val()) == ""){
        $("#hddCampoFocus").val("txtCaminho");
        $("#dialog-atencao").html("Por favor, informe o caminho do Menu.");         
        $('#dialog-atencao').dialog('open');
        return;
    }
    if($.trim($("#selMenu").val()) == ""){
        $("#hddCampoFocus").val("selMenu");
        $("#dialog-atencao").html("Por favor, selecione o Menu do Nível 1.");         
        $('#dialog-atencao').dialog('open');
        return;
    }    
    if($("#ckbStatus").is(":checked")){
        valorStatus = "I";
    }
   
                $.post("controladores/MenuNivel2Controlador.php", {
                    acao: "Salvar",
                    id: $("#hddCodigo").val(),
                    nome: $.trim($("#txtNome").val()),
                    caminho: $.trim($("#txtCaminho").val()),
                    menu1: $.trim($("#selMenu").val()),
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
            url: "controladores/MenuNivel2Controlador.php"
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
                                       
                    $.post("controladores/MenuNivel2Controlador.php", {
                        acao: "ConsultarDados",
                        codigo: id,                        
                        tipoPesquisa: "codigo"
                    },
                        function(data) {    
                            if(data.sucesso == "true"){
                                
                                if((data.rows[0].status)=="A"){
                                    
                                    var status= "S";
                                }else{
                                    status = "N";
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
                                                    html += '<td bgcolor="#F4F4F4" align="right"><b>Nome do Menu</b></td>';
                                                    html += '<td>' + data.rows[0].nome + '</td>';
                                                html += '</tr>'; 
                                                html += '<tr>';
                                                    html += '<td bgcolor="#F4F4F4" align="right"><b>Menu Nível 1</b></td>';
                                                    html += '<td>' + data.rows[0].menu1 + '</td>';
                                                html += '</tr>';
                                                html += '<tr>';
                                                    html += '<td bgcolor="#F4F4F4" align="right"><b>Caminho</b></td>';
                                                    html += '<td>' + data.rows[0].caminho + '</td>';
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
                    
                    $.post("controladores/MenuNivel2Controlador.php", {
                        acao: "ConsultarDados",
                        codigo: id,                        
                        tipoPesquisa: "codigo"
                    },
                        function(data) {                            
                            if(data.sucesso == "true"){
                           
                                $("#hddCodigo").val(data.rows[0].id);
                                $("#txtNome").val(data.rows[0].nome);
                                $("#txtCaminho").val(data.rows[0].caminho);
                                $("#selMenu").val(data.rows[0].menu1_id);
                                
                                
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
            
            name: 'nome',title:'Menu Nível 2',width:500,align:'left'
        },{ 
              name: 'menu1',title:'Menu Nível 1',width:500,align:'left'
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
    $("#txtNome").val("");
    $("#txtCaminho").val("");
    $("#selMenu").val("");
    $("#ckbStatus").attr("checked", false);
}

function ExibirHelpCartao(){
    $("#dialog-help").html("<img src='img/loading.gif'/>"); 
    $("#dialog-help").html("<img src='img/help-cartao.jpg'/>");        
    $('#dialog-help').dialog('open');
}