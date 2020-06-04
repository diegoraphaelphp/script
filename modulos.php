<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
    <table id="tabela-modulos" cellspacing="20" align="center">
        <tr align="center">
            <td><b>Acompanhamento</b></td>
            <td><b>Administrativo</b></td>
            <td><b>Financeiro</b></td>
            <td><b>Planejamento</b></td>
            <td><b>Gerencial</b></td>
        </tr>
        <tr>
            <td valign="top">
                <table cellspacing="5">
                    <tr>
                        <td><img src="img/modulo-acompanhamento-ater.jpg" /></td>
                    </tr>
                </table>
            </td>
            <td valign="top">
                <table cellspacing="5">
                    <tr>
                        <td>
                            <img src="img/modulo-administrativo-plano-contas.jpg" />
                        </td>
                    </tr>
                    <tr>
                        <td><img src="img/modulo-administrativo-compra.jpg" /></td>
                    </tr>
                </table>
            </td>
            <td valign="top">
                <table cellspacing="5">
                    <tr>
                        <td>
                            <a href="javascript:void(0);" onclick="ExibirTela('content', 'modulos/financeiro/nfe/FrmMenu.php');" title="M&oacute;dulo financeiro"><img src="img/modulo-financeiro-nfe.jpg" border="0" /></a>
                        </td>
                    </tr>
                </table>
            </td>
            <td valign="top">
                <table cellspacing="5">
                    <tr>
                        <td><img src="img/modulo-planejamento-ater.jpg" /></td>
                    </tr>
                    <tr>
                        <td><img src="img/modulo-planejamento-pesquisa.jpg" /></td>
                    </tr>
                </table>
            </td>
            <td valign="top">
                <table cellspacing="5">
                    <tr>
                        <td>
                            <?php
                                if($_SESSION["GRUPO_ID"] == "1"){
                            ?>
                            <a href="javascript:void(0);" onclick="ExibirTela('content', 'modulos/gerencial/FrmMenu.php');" title="M&oacute;dulo Administra&ccedil;&atilde;o"><img src="img/modulo-suporte-administracao.jpg" border="0" /></a>
                            <?php
                                }else{
                            ?>
                            <img src="img/modulo-suporte-administracao.jpg" border="0" />                            
                            <?php
                                }
                            ?>
                        </td>
                    </tr>                            
                </table>
            </td>
        </tr>                
    </table>
</body>
</html>