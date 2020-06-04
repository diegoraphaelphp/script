// Ac�es do TextArea


	function quebraLinha(campo, e){
		var valor = campo.value;
		if (e.keyCode == 13){
			valor += "<br>";
			campo.value = valor; 
		}
	}

<!--
	// bbCode control by
	// subBlue design
	// www.subBlue.com
	
	// Startup variables
	var imageTag     = false;
	var theSelection = false;
	// Check for Browser & Platform for PC & IE specific bits
	// More details from: http://www.mozilla.org/docs/web-developer/sniffer/browser_type.html
	var clientPC = navigator.userAgent.toLowerCase(); // Get client info
	var clientVer = parseInt(navigator.appVersion); // Get browser version
	
	var is_ie = ((clientPC.indexOf("msie") != -1) && (clientPC.indexOf("opera") == -1));
	var is_nav = ((clientPC.indexOf('mozilla')!=-1) && (clientPC.indexOf('spoofer')==-1)
					&& (clientPC.indexOf('compatible') == -1) && (clientPC.indexOf('opera')==-1)
					&& (clientPC.indexOf('webtv')==-1) && (clientPC.indexOf('hotjava')==-1));
	var is_moz = 0;
	
	var is_win = ((clientPC.indexOf("win")!=-1) || (clientPC.indexOf("16bit") != -1));
	var is_mac = (clientPC.indexOf("mac")!=-1);
	
	// Helpline messages
	b_help = "Negrito: <b>texto</b>  (alt+b)";
	i_help = "Italico: <i>texto</i>  (alt+i)";
	u_help = "Sublinhado: <u>texto</u>  (alt+u)";
	q_help = "Citacao: [quote]texto[/quote]  (alt+q)";
	c_help = "C�digo: <code>c�digo</code>  (alt+c)";
	l_help = "Lista: [list]texto[/list] (alt+l)";
	o_help = "Lista ordenada: [list=]texto[/list]  (alt+o)";
	p_help = "Inserir imagem: [img]http://url_da_imagem[/img]  (alt+p)";
	w_help = "Inserir URL: [url]http://url[/url] ou [url=http://url]texto da URL[/url]  (alt+w)";
	a_help = "Fechar todas as tags BBCode abertas";
	s_help = "Cor da fonte: [color=red]texto[/color]  Dica: voc� pode tamb�m usar color=#FF0000";
	f_help = "Tamanho da fonte: [size=x-small]texto pequeno[/size]";
	
	// Define the bbCode tags
	bbcode = new Array();
	bbtags = new Array('<b>','</b>','<i>','</i>','<u>','</u>','[quote]','[/quote]','<code>','</code>','[list]','[/list]','[list=]','[/list]','[img]','[/img]','[url]','[/url]');
	imageTag = false;
	
	// Shows the help messages in the helpline window
	function helpline(help) {
		document.form1.helpbox.value = eval(help + "_help");
	}


	// Replacement for arrayname.length property
	function getarraysize(thearray) {
		for (i = 0; i < thearray.length; i++) {
			if ((thearray[i] == "undefined") || (thearray[i] == "") || (thearray[i] == null))
				return i;
			}
		return thearray.length;
	}
	
	// Replacement for arrayname.push(value) not implemented in IE until version 5.5
	// Appends element to the array
	function arraypush(thearray,value) {
		thearray[ getarraysize(thearray) ] = value;
	}
	
	// Replacement for arrayname.pop() not implemented in IE until version 5.5
	// Removes and returns the last element of an array
	function arraypop(thearray) {
		thearraysize = getarraysize(thearray);
		retval = thearray[thearraysize - 1];
		delete thearray[thearraysize - 1];
		return retval;
	}
	
	
	function checkForm() {
	
		formErrors = false;
	
		if (document.form1.message.value.length < 2) {
			formErrors = "A mensagem deve ser escrita para ser postada.";
		}
	
		if (formErrors) {
			alert(formErrors);
			return false;
		} else {
			bbstyle(-1);
			//formObj.preview.disabled = true;
			//formObj.submit.disabled = true;
			return true;
		}
	}
	
	function emoticon(text) {
		var txtarea = document.form1.message;
		text = ' ' + text + ' ';
		if (txtarea.createTextRange && txtarea.caretPos) {
			var caretPos = txtarea.caretPos;
			caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? caretPos.text + text + ' ' : caretPos.text + text;
			txtarea.focus();
		} else {
			txtarea.value  += text;
			txtarea.focus();
		}
	}
	
	function bbfontstyle(bbopen, bbclose) {
		var txtarea = document.form1.message;
	
		if ((clientVer >= 4) && is_ie && is_win) {
			theSelection = document.selection.createRange().text;
			if (!theSelection) {
				txtarea.value += bbopen + bbclose;
				txtarea.focus();
				return;
			}
			document.selection.createRange().text = bbopen + theSelection + bbclose;
			txtarea.focus();
			return;
		}
		else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0))
		{
			mozWrap(txtarea, bbopen, bbclose);
			return;
		}
		else
		{
			txtarea.value += bbopen + bbclose;
			txtarea.focus();
		}
		storeCaret(txtarea);
	}
	
	
	function bbstyle(bbnumber) {
		var txtarea = document.form1.message;
	
		txtarea.focus();
		donotinsert = false;
		theSelection = false;
		bblast = 0;
	
		if (bbnumber == -1) { // Close all open tags & default button names
			while (bbcode[0]) {
				butnumber = arraypop(bbcode) - 1;
				txtarea.value += bbtags[butnumber + 1];
				buttext = eval('document.form1.addbbcode' + butnumber + '.value');
				eval('document.form1.addbbcode' + butnumber + '.value ="' + buttext.substr(0,(buttext.length - 1)) + '"');
			}
			imageTag = false; // All tags are closed including image tags :D
			txtarea.focus();
			return;
		}
	
		if ((clientVer >= 4) && is_ie && is_win)
		{
			theSelection = document.selection.createRange().text; // Get text selection
			if (theSelection) {
				// Add tags around selection
				document.selection.createRange().text = bbtags[bbnumber] + theSelection + bbtags[bbnumber+1];
				txtarea.focus();
				theSelection = '';
				return;
			}
		}
		else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0))
		{
			mozWrap(txtarea, bbtags[bbnumber], bbtags[bbnumber+1]);
			return;
		}
	
		// Find last occurance of an open tag the same as the one just clicked
		for (i = 0; i < bbcode.length; i++) {
			if (bbcode[i] == bbnumber+1) {
				bblast = i;
				donotinsert = true;
			}
		}
	
		if (donotinsert) {		// Close all open tags up to the one just clicked & default button names
			while (bbcode[bblast]) {
					butnumber = arraypop(bbcode) - 1;
					txtarea.value += bbtags[butnumber + 1];
					buttext = eval('document.form1.addbbcode' + butnumber + '.value');
					eval('document.form1.addbbcode' + butnumber + '.value ="' + buttext.substr(0,(buttext.length - 1)) + '"');
					imageTag = false;
				}
				txtarea.focus();
				return;
		} else { // Open tags
	
			if (imageTag && (bbnumber != 14)) {		// Close image tag before adding another
				txtarea.value += bbtags[15];
				lastValue = arraypop(bbcode) - 1;	// Remove the close image tag from the list
				document.form1.addbbcode14.value = "Img";	// Return button back to normal state
				imageTag = false;
			}
	
			// Open tag
			txtarea.value += bbtags[bbnumber];
			if ((bbnumber == 14) && (imageTag == false)) imageTag = 1; // Check to stop additional tags after an unclosed image tag
			arraypush(bbcode,bbnumber+1);
			eval('document.form1.addbbcode'+bbnumber+'.value += "*"');
			txtarea.focus();
			return;
		}
		storeCaret(txtarea);
	}
	
	// From http://www.massless.org/mozedit/
	function mozWrap(txtarea, open, close)
	{
		var selLength = txtarea.textLength;
		var selStart = txtarea.selectionStart;
		var selEnd = txtarea.selectionEnd;
		if (selEnd == 1 || selEnd == 2)
			selEnd = selLength;
	
		var s1 = (txtarea.value).substring(0,selStart);
		var s2 = (txtarea.value).substring(selStart, selEnd)
		var s3 = (txtarea.value).substring(selEnd, selLength);
		txtarea.value = s1 + open + s2 + close + s3;
		return;
	}
	
	// Insert at Claret position. Code from
	// http://www.faqts.com/knowledge_base/view.phtml/aid/1052/fid/130
	function storeCaret(textEl) {
		if (textEl.createTextRange) textEl.caretPos = document.selection.createRange().duplicate();
	}

//-->
