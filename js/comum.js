function MarcarCampo(id){
    $("#" + id).css("background-color","#EDEDED");
}

function DesmarcarCampo(id){
    $("#" + id).css("background-color","#FFFFFF");
}

function ValidarCPF(cpf){  
    erro = new String;  
    cpf = cpf.replace( /[.-]/g, "" );
	
    if (cpf.length == 11){
        var nonNumbers = /\D/;  

        if (nonNumbers.test(cpf))   
        {  
                return false;
        }  
        else  
        {  
            if (cpf == "00000000000" ||   
                    cpf == "11111111111" ||   
                    cpf == "22222222222" ||   
                    cpf == "33333333333" ||   
                    cpf == "44444444444" ||   
                    cpf == "55555555555" ||   
                    cpf == "66666666666" ||   
                    cpf == "77777777777" ||   
                    cpf == "88888888888" ||   
                    cpf == "99999999999") { 

                    return false;  
            }  

            var a = [];  
            var b = new Number;  
            var c = 11;  

            for (i=0; i<11; i++){  
                    a[i] = cpf.charAt(i);  
                    if (i < 9) b += (a[i] * --c);  
            }  

            if ((x = b % 11) < 2) { a[9] = 0 } else { a[9] = 11-x } 

            b = 0;  
            c = 11;  

            for (y=0; y<10; y++) b += (a[y] * c--);   

            if ((x = b % 11) < 2) { a[10] = 0; } else { a[10] = 11-x; }  

            if ((cpf.charAt(9) != a[9]) || (cpf.charAt(10) != a[10])) {  
                    return false;
            }  
        }  
    }  
    else  
    {  
        if(cpf.length == 0)  
            return false  
        else  
            return false;
    }
	   
    return true;      
}

function ValidarCNPJ(cnpj){
    var result = true;
    
    // Limpa pontos e Traços da string
    cnpj = cnpj.replace(/\./g, "");
    cnpj = cnpj.replace(/\-/g, "");
    cnpj = cnpj.replace(/\_/g, "");
    cnpj = cnpj.replace(/\//g, "");
    
    if(jQuery.trim(cnpj) != ""){
        if(cnpj.length!=14){ result = false; }

        pri = eval(cnpj.substring(0,2));
        seg = eval(cnpj.substring(3,6));
        ter = eval(cnpj.substring(7,10));
        qua = eval(cnpj.substring(11,15));
        qui = eval(cnpj.substring(16,18));

        var i;
        var numero;

        numero = (pri+seg+ter+qua+qui);

        s = numero;

        c = cnpj.substr(0,12);

        var dv = cnpj.substr(12,2);
        var d1 = 0;

        for (i = 0; i < 12; i++){
            d1 += c.charAt(11-i)*(2+(i % 8));
        }

        if (d1 == 0){
            result = false;
        }

        d1 = 11 - (d1 % 11);

        if (d1 > 9) d1 = 0;

        if (dv.charAt(0) != d1){
            result = false;
        }

        d1 *= 2;

        for (i = 0; i < 12; i++){
            d1 += c.charAt(11-i)*(2+((i+1) % 8));
        }

        d1 = 11 - (d1 % 11);

        if (d1 > 9) d1 = 0;

        if (dv.charAt(1) != d1){
            result = false;
        }
    }
    
    if(!result){
        return false; 
    }
        
    return true;
}

function ValidarEmail(email){
    var er = new RegExp(/^[A-Za-z0-9_\-\.]+@[A-Za-z0-9_\-\.]{2,}\.[A-Za-z0-9]{2,}(\.[A-Za-z0-9])?/);

    if(typeof(email) == "string"){
        if(er.test(email)){ return true; }
    }else if(typeof(email) == "object"){
        if(er.test(email.value)){ 
            return true; 
        }
    }
    
    return false;
}

function IsInteger(sNum){
   // EXPRESSAO REGULAR PARA ACEITAR APENAS NUMEROS INTEIROS
   var reDigits = /^\d+$/;
 
   return reDigits.test(sNum);
}

function IsNumero(sText) {  
   // caso queira utilizar a virgula como separador decimal coloque nesta variável
   var ValidChars = "0123456789.,";
   var IsNumber = true;
   var Char;
 
   for (ixNumerico = 0; ixNumerico < sText.length && IsNumber == true; ixNumerico++) 
   { 
      Char = sText.charAt(ixNumerico); 
      if (ValidChars.indexOf(Char) == -1) 
      {
         IsNumber = false;
      }
   }
   
   return IsNumber;
} 

function ValidarData(data)
{
    /******** VALIDA DATA NO FORMATO DD/MM/AAAA *******/
    
    var regExpCaracter = /[^\d]/;     //Expressão regular para procurar caracter não-numérico.
    var regExpEspaco = /^\s+|\s+$/g;  //Expressão regular para retirar espaços em branco.
    
    if(data.length != 10)
    {        
        return false;
    }
    
    splitData = data.split('/');
    
    if(splitData.length != 3)
    {        
        return false;
    }
    
    /* Retira os espaços em branco do início e fim de cada string. */
    splitData[0] = splitData[0].replace(regExpEspaco, '');
    splitData[1] = splitData[1].replace(regExpEspaco, '');
    splitData[2] = splitData[2].replace(regExpEspaco, '');
    
    if ((splitData[0].length != 2) || (splitData[1].length != 2) || (splitData[2].length != 4))
    {        
        return false;
    }
    
    /* Procura por caracter não-numérico. EX.: o "x" em "28/09/2x11" */
    if (regExpCaracter.test(splitData[0]) || regExpCaracter.test(splitData[1]) || regExpCaracter.test(splitData[2]))
    {        
        return false;
    }
    
    dia = parseInt(splitData[0],10);
    mes = parseInt(splitData[1],10)-1; //O JavaScript representa o mês de 0 a 11 (0->janeiro, 1->fevereiro... 11->dezembro)
    ano = parseInt(splitData[2],10);
    
    var novaData = new Date(ano, mes, dia);
    
    /* O JavaScript aceita criar datas com, por exemplo, mês=14, porém a cada 12 meses mais um ano é acrescentado à data
    final e o restante representa o mês. O mesmo ocorre para os dias, sendo maior que o número de dias do mês em
    questão o JavaScript o converterá para meses/anos.
    Por exemplo, a data 28/14/2011 (que seria o comando "new Date(2011,13,28)", pois o mês é representado de 0 a 11)
    o JavaScript converterá para 28/02/2012.
    Dessa forma, se o dia, mês ou ano da data resultante do comando "new Date()" for diferente do dia, mês e ano da
    data que está sendo testada esta data é inválida. */
    if ((novaData.getDate() != dia) || (novaData.getMonth() != mes) || (novaData.getFullYear() != ano))
    {        
        return false;
    }
    else
    {       
        return true;
    }
}

function FormatarNumeroDecimal(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? ',' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + '.' + '$2');
    }
    
    return x1 + x2;
}