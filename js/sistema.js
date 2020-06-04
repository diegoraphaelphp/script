function SetarFocus(id){  
    $("#" + id).css("background", "#FFFF99");    
}

function RetirarFocus(id){
    $("#" + id).css("background", "#FFFFFF");
}

function Redirecionar(url){
    document.location.href = url;
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