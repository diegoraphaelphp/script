function ExibirTela(div, tela){
    $("#" + div).html("<center><img src='img/loading.gif' style='margin-top: 100px;'></center>");
    $("#" + div).load(tela);
}