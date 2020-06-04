<?php
	if(!empty($_GET["acao"])){
    
        $acao = base64_decode($_GET["acao"]);
        //Selecionando controlador respectivo	
        require_once("../class/UsuarioException.php");
        require_once("../class/TecnicoException.php");

        // arquivo respons�vel por gerenciar os m�dulos (tempor�rio)
        require_once("../include/modulo.php");

    	switch($acao){
    		//GERAIS...
    		case "frmAcesso":
    			include_once("../ctrl/Ctrl.php");
    		break;		
    		
    		case "formPlanoAno":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "frmAlterarSenha":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "AlterarSenha":
    			include_once("../ctrl/Ctrl.php");
    		break;		
    		
    		case "acessoNegado":
    			include_once("../ctrl/Ctrl.php");
    		break;		
    		
    		case "formGerarTXT":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarTXT":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "visualizaProjDetail":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    		
    		case "filtrarLOG":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "gerarXLSLOG":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		
    		case "incluirPlanoAno":		
    			include_once("../ctrl/Ctrl.php");
    		break;
    	
    		case "MenuPrincipal":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		//UNIDADES...
    		case "cadastrarUnidades":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirUnidades":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarUnidades":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarUnidades":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirUnidades":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "gerarXLSUnidades":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    		/**/
    		
    		//USUARIOS...
    		case "mudaStatusUsuario":
    			include_once("../ctrl/Ctrl.php");
    		break;	
    		
    		
    		case "cadastrarUsuarios":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarUsuarios":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirUsuarios":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirUsuarios":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarUsuarios":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "validaLogin":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSUsuarios":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    		/**/
    		
    		//REGIONAL...
    		case "cadastrarRegional":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarRegional":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirRegional":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirRegional":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarRegional":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSRegional":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    		
    		/* REGIAO DESENVOLVIMENTO */
    		case "cadastrarDesenv":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarDesenv":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirDesenv":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirDesenv":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarDesenv":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSDesenv":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    		
    		/* MESSOREGIAO */
    		case "cadastrarMeso":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarMeso":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirMeso":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirMeso":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarMeso":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "gerarXLSMeso":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    		/**/
    		
    		/* MICRORREGI�O */
    		case "cadastrarMicro":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarMicro":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirMicro":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirMicro":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarMicro":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "gerarXLSMicro":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    		/**/
    		
    		/* PROJETOS */
    		case "cadastrarProjetos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarProjetos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirProjetos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirProjetos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarProjetos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "gerarXLSProjetos":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    		
    		case "gerarXLSProjetosAtv":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    		/**/
    		
    		/* ATIVIDADES */
    		case "cadastrarAtividades":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarAtividades":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirAtividades":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirAtividades":
    			include_once("../ctrl/Ctrl.php");
    		break;
            
    		case "alterarAtividades":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		/**/
    		
    		/* ELABORA��O */
    		case "cadastrarElaboracao":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarElaboracao":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirElaboracao":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirElaboracao":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirElaboracao2":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "gerarXLSElaboracao":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    		/**/
    		
    		//EQUIPES...
    		case "cadastrarEquipes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarEquipes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirEquipes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirEquipes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarEquipes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSEquipes":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    		
    		//LINHA DE PESQUISA ...
    		case "cadastrarLinhap":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarLinhap":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirLinhap":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirLinhap":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarLinhap":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSLinhap":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    		
    		//PROJETO PESQUISA ...
    		case "cadastrarPPE":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarPPE":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirPPE":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirPPE":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarPPE":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSPPE":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    		
    		//A��ES ...
    		case "cadastrarAcoes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarAcoes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirAcoes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirAcoes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarAcoes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSAcoes":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    		
    		//SEGMENTO AGROPECUARIO ...
    		case "cadastrarSAG":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarSAG":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirSAG":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirSAG":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarSAG":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSSAG":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    		
    		//SEGMENTO EMPRESAS ...
    		case "cadastrarEmpresa":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarEmpresa":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirEmpresa":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirEmpresa":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarEmpresa":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSEmpresa":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    		
    		//MUNICIPIOS...
    		case "cadastrarMunicipios":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarMunicipios":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirMunicipios":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirMunicipios":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarMunicipios":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSMunicipios":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    		
    		//PROGRAMAS...
    		case "cadastrarProgramas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarProgramas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirProgramas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirProgramas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarProgramas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSProgramas":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    		
    		case "cadastrarADDLP":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirADDLP":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "filtrarADDLP":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirADDLP":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		/**/
    
    		//INSTITUI��ES...
    		case "cadastrarInstituicoes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarInstituicoes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirInstituicoes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirInstituicoes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarInstituicoes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSInstituicoes":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    		
    		//PESSOAS...
    		case "cadastrarPessoas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarPessoas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirPessoas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirPessoas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarPessoas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSPessoas":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    		
    		//GRUPOS...
    		case "cadastrarGrupos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarGrupos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirGrupos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirGrupos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarGrupos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSGrupos":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    		
    		//DESPESAS...
    		case "cadastrarDespesas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarDespesas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirDespesas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirDespesas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarDespesas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSDespesas":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    		
    		//CONTAS...
    		case "cadastrarContas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarContas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirContas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirContas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarContas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSContas":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    		
    		//LOCAIS...
    		case "cadastrarLocal":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarLocal":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirLocal":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirLocal":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarLocal":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSLocal":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    		
    		//MODULOS...
    		case "cadastrarModulos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarModulos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirModulos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirModulos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarModulos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSModulos":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    		
    		//APLICA��ES...
    		case "cadastrarApl":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarApl":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirApl":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirApl":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarApl":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSApl":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    		
    		//APLICA��ES DOS M�DULOS...
    		case "cadastrarAplMod":	
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarAplMod":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirAplMod":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirAplMod":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSAplMod":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    
    		//M�DULOS DOS USU�RIOS...
    		case "cadastrarModUsu":	
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarModUsu":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirModUsu":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirModUsu":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSModUsu":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    		
    		//MOV DE CONTAS...
    		case "cadastrarMovContas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarMovContas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirMovContas":		
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirMovContas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarMovContas":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSMovContas":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/		
    		
    		//CONVENIOS...
    		case "cadastrarConvenios":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarConvenios":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirConvenios":		
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirConvenios":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarConvenios":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSConvenios":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    		/**/		
    		
    		//CONTAS SOLICITA��ES...
    		case "cadastrarContasSOL":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarContasSOL":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirContasSOL":		
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirContasSOL":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarContasSOL":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSContasSOL":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    		/**/	
    		
    		//COMPRAS...
    		case "cadastrarCompras":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarCompras":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirCompras":		
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirItensCompra":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirCompras":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "detalhesCompras":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarCompras":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSCompras":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    
    		case "gerarXLSComprasItens":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    		/**/
    		
    		//PROGRAMA DAS PESSOAS...
    		case "cadastrarProgPessoa":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarProgPessoa":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirProgPessoa":		
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirProgPessoa":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSProgPessoa":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    		/**/
    		
    		//MENUS...
    		case "cadastrarMenu":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarMenu":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirMenu":		
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirMenu":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarMenu":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSMenu":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    		/**/
    		
    		//MENUS DOS MODULOS...
    		case "cadastrarMenuMod":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarMenuMod":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirMenuMod":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirMenuMod":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarMenuMod":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSMenuMod":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    		/**/
    		
    		//METODOLOGIAS...
    		case "cadastrarMeto":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarMeto":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirMeto":		
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirMeto":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarMeto":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSMeto":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    		
    		//ORIENTA��ES...
    		case "cadastrarOrientacoes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarOrientacoes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirOrientacoes":		
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirOrientacoes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarOrientacoes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSOrientacoes":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    		
    		//FONTES FINANCIAMENTOS...
    		case "cadastrarFinanciamentos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarFinanciamentos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirFinanciamentos":		
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirFinanciamentos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarFinanciamentos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSFinanciamentos":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    		
    		//ORIENTA��ES X PROJETOS...
    		case "cadastrarOriProj":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarOriProj":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirOriProj":		
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirOriProj":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSOriProj":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    		
    		//PROCESSOS...
    		case "cadastrarProcessos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarProcessos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirProcessos":		
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirProcessos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarProcessos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSProcessos":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    		
    		//FAMILIAS...
    		case "filtrarFamilias":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "gerarXLSFamilias":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
            		
    		//PEDIDOS E DETALHES DO PEDIDO...
    		case "filtrarNFE":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "consultarDANFE":
    			include_once("../ctrl/Ctrl2.php");
    		break;
    		
    		case "filtrarNFE2":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "cadastrarPedidos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarPedidos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirPedidos":		
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "detalhePedido":		
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirPedidos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarPedidos":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirItensPedido":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSPedidos":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;	
    		
    		case "gerarXLSPedidosItens":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;	
    		
    		case "finalizarPedido":
    			include_once("../ctrl/Ctrl.php");
    		break;	
    		
    		case "gerarXLSNFE":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    		
    		case "gerarXLSNFE2":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    		
    		/* PRODUTOS EMPENHADOS */
    		case "cadastrarProdEmp":
    			include_once("../ctrl/Ctrl2.php");
    		break;
    		
    		case "cancelarProdEmp":
    			include_once("../ctrl/Ctrl2.php");
    		break;
    		
    		case "filtrarProdEmp":
    			include_once("../ctrl/Ctrl2.php");
    		break;
    		
    		case "incluirProdEmp":
    			include_once("../ctrl/Ctrl2.php");
    		break;
    		
    		case "excluirProdEmp":
    			include_once("../ctrl/Ctrl2.php");
    		break;
    
    		case "excluirProdEmp2":
    			include_once("../ctrl/Ctrl2.php");
    		break;
    		
    		case "alterarProdEmp":
    			include_once("../ctrl/Ctrl2.php");
    		break;
    
    		case "gerarXLSProdEmp":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    		
    				//	plano execu��o
    		case "filtrarPlnExecucao":
    			include_once("../ctrl/Ctrl.php");
    		break;	
    		case "gerarPdfExecucao":
    			include_once("../ctrl/Ctrl.php");
    		break;	
    		
    		case "importarSigater":
    			include_once("../ctrl/Ctrl.php");
    		break;	
    				
    		case "importandoSigater":
    			include_once("../ctrl/Ctrl.php");
    		break;	
    		
    		case "filtrarSigater":
    			include_once("../ctrl/Ctrl.php");
    		break;	
    		
    		case "importarRDE":
    			include_once("../ctrl/Ctrl.php");
    		break;	
    		
    		case "importandoRDE":
    			include_once("../ctrl/Ctrl.php");
    		break;	
    		
    		case "filtrarRDE":
    			include_once("../ctrl/Ctrl.php");
    		break;	
    
    		case "filtrarACE":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "importarEntidade":
    			include_once("../ctrl/Ctrl.php");
    		break;	
    		
    		case "importandoEntidade":
    			include_once("../ctrl/Ctrl.php");
    		break;	
    		
    		case "gerarXLSAcompanhamentoAcoes":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
    		
    		/* BASES EMPRESAS */
    		case "cadastrarBases":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarBases":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirBases":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirBases":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSBases":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    
    		/* CLIENTES */
    		case "cadastrarClientes":
    			include_once("../ctrl/Ctrl2.php");
    		break;
    		
    		case "filtrarClientes":
    			include_once("../ctrl/Ctrl2.php");
    		break;
    
    		case "incluirClientes":
    			include_once("../ctrl/Ctrl2.php");
    		break;
    
    		case "alterarClientes":
    			include_once("../ctrl/Ctrl2.php");
    		break;
    		
    		case "excluirClientes":
    			include_once("../ctrl/Ctrl2.php");
    		break;
    
    		case "gerarXLSClientes":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    		
    		/* ENQUETES */
    		case "aprovarEnquetes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "reprovarEnquetes":
    			include_once("../ctrl/Ctrl.php");
    		break;				
    
    		case "cadastrarEnquetes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarEnquetes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirEnquetes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirEnquetes":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSEnquetes":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    		
    		/* STATUS */
    		case "cadastrarStatus":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "filtrarStatus":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "incluirStatus":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "excluirStatus":
    			include_once("../ctrl/Ctrl.php");
    		break;
    		
    		case "alterarStatus":
    			include_once("../ctrl/Ctrl.php");
    		break;
    
    		case "gerarXLSStatus":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    		
    		case "gerarBKPBD":
    			include_once("../ctrl/Ctrl2.php");
    		break;	
    		
    		case "filtrarBKPBD":
    			include_once("../ctrl/Ctrl2.php");
    		break;	
    		
    		case "filtrarAcessoUSU":
    			include_once("../ctrl/Ctrl2.php");
    		break;	
    		
    		/* AGENTE FINANCEIRO */
    		case "cadastrarAgente":
    			include_once("../ctrl/Ctrl2.php");
    		break;
    		
    		case "filtrarAgente":
    			include_once("../ctrl/Ctrl2.php");
    		break;
    
    		case "incluirAgente":
    			include_once("../ctrl/Ctrl2.php");
    		break;
    
    		case "alterarAgente":
    			include_once("../ctrl/Ctrl2.php");
    		break;
    		
    		case "excluirAgente":
    			include_once("../ctrl/Ctrl2.php");
    		break;
    
    		case "gerarXLSAgente":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;		
    		/**/
    
                    // CADASTRO DE ATIVIDADES PESQUISA
    		case "cadastrarAtividadesPesquisa":                    
                        include_once("../ctrl/Ctrl.php");
                        break;
                    case "consultarAtividadesPesquisa":                    
                        include_once("../ctrl/Ctrl.php");
                        break;
            
    		case "formularioParametrosNFE":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "atualizarParametrosNFE":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
            /* ATIVIDADE NFE */
    		case "cadastrarAtividadeNFE":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "incluirAtividadeNFE":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "excluirAtividadeNFE":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "alterarAtividadeNFE":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "filtrarAtividadeNFE":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "gerarXLSAtividadeNFE":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
            /**/
            
          /* GRUPOS PRODUTO */
    		case "cadastrarGruposProd":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "incluirGruposProd":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "excluirGruposProd":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "alterarGruposProd":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "filtrarGruposProd":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "gerarXLSGruposProd":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
            /**/
            
            /* SUB-GRUPOS PRODUTO */
    		case "cadastrarSubGruposProd":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "incluirSubGruposProd":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "excluirSubGruposProd":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "alterarSubGruposProd":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "filtrarSubGruposProd":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "gerarXLSSubGruposProd":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
            /**/
            
            /* LINHA DE PRODUTO */
    		case "cadastrarLinhaProd":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "incluirLinhaProd":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "excluirLinhaProd":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "alterarLinhaProd":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "filtrarLinhaProd":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "gerarXLSLinhaProd":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
            /**/
            
            /* PRODUTOS */
    		case "cadastrarProdutos":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "incluirProdutos":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "excluirProdutos":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "alterarProdutos":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "filtrarProdutos":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "gerarXLSProdutos":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
            /**/
            
            /* FABRICANTES */
    		case "cadastrarFabricantes":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "incluirFabricantes":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "excluirFabricantes":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "alterarFabricantes":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "filtrarFabricantes":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
    		case "gerarXLSFabricantes":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
            /**/
            
            case "incluirPlanoAnual":
    			include_once("../ctrl/Ctrl.php");
    		break;
            
            case "alterrPlanoAnual":
    			include_once("../ctrl/Ctrl.php");
    		break;
            
            case "alterarMuniUnid":
    			include_once("../ctrl/Ctrl.php");
    		break;
            
    		case "filtrarACE":
    			include_once("../ctrl/Ctrl.php");
    		break;
            
    		case "uploadArquivosSigater":
    			include_once("../ctrl/Ctrl.php");
    		break;
            
    		case "frmuploadArquivosSigater":
    			include_once("../ctrl/Ctrl.php");
    		break;
            
    		case "gerarXLSAcompanhamentoEnvio":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
            
            case "gerarPDFCadastros":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
            
            case "filtrarVeiculos":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
            case "gerarPDFVeiculos":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
            
            case "filtrarMovVeiculos":
    			include_once("../ctrl/Ctrl2.php");
    		break;

            case "gerarPDFMovVeiculos":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
            
            case "validaLogineditar":
    			include_once("../ctrl/Ctrl.php");
    		break;
            
            case "validaLoginbtn":
    			include_once("../ctrl/Ctrl.php");
    		break;
            
            case "validaUsuario":
                include_once("../ctrl/Ctrl.php");                
    		break;

            case "validaBTNUsuario":
    			include_once("../ctrl/Ctrl.php");
    		break;
            
            case "filtrarFamiliaRDE":
    			include_once("../ctrl/Ctrl2.php");
    		break;
            
            case "gerarPDFFamiliaRDE":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
            
            case "atualizaQTDExecucao":
    			include_once("../ctrl/Ctrl.php");
    		break;
            
            case "atualizaQTDExecucao2":
    			include_once("../ctrl/Ctrl.php");
    		break;
            
            case "gerarPDFTecnicos":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
            
            case "gerarPDFCadastrosTotal":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;
            
            case "gerarLOGFilesPDF":
    			include_once("../ctrl/CtrlImpressao.php");
    		break;

            case "filtrarLOGFiles":
    			include_once("../ctrl/Ctrl2.php");
    		break;

    		default:
    			//Erro de par�metro invalido
    			header("location: index.html");
    		break;		
    	}

    }
?>