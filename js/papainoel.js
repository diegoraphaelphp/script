/******************************************
* Snow Effect Script- By Altan d.o.o. (http://www.altan.hr/snow/index.html)
* Visit Dynamic Drive DHTML code library (http://www.dynamicdrive.com/) for full source code
* Last updated Nov 9th, 05' by DD. This notice must stay intact for use
******************************************/
  
  //Configure below to change URL path to the snow image
  var snowsrc2="img/peres_noel-23.gif"
  // Configure below to change number of snow to render
  var no2 = 1;
  // Configure whether snow should disappear after x seconds (0=never):
  var hidesnowtime2 = 0;
  // Configure how much snow should drop down before fading ("windowheight" or "pageheight")
  var snowdistance2 = "windowheight";

///////////Stop Config//////////////////////////////////

  var ie4up2 = (document.all) ? 1 : 0;
  var ns6up2 = (document.getElementById&&!document.all) ? 1 : 0;

	function iecompattest2(){
	return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
	}

  var dx2, xp2, yp2;    // coordinate and position variables
  var am2, stx2, sty2;  // amplitude and step variables
  var i2, doc_width2 = 800, doc_height2 = 600; 
  
  if (ns6up2) {
    doc_width2 = self.innerWidth;
    doc_height2 = self.innerHeight;
  } else if (ie4up2) {
    doc_width2 = iecompattest2().clientWidth;
    doc_height2 = iecompattest2().clientHeight;
  }

  dx2 = new Array();
  xp2 = new Array();
  yp2 = new Array();
  am2 = new Array();
  stx2 = new Array();
  sty2 = new Array();
  snowsrc2=(snowsrc2.indexOf("clubepc.org.com")!=-1)? "papainoel.gif" : snowsrc2
  for (i2 = 0; i2 < no2; ++ i2) {  
    dx2[i2] = 0;                        // set coordinate variables
    xp2[i2] = Math.random()*(doc_width2-50);  // set position variables
    yp2[i2] = Math.random()*doc_height2;
    am2[i2] = Math.random()*100;         // set amplitude variables
    stx2[i2] = 0.02 + Math.random()/10; // set step variables
    sty2[i2] = 2.1 + Math.random();     // set step variables
		if (ie4up2||ns6up2) {
      if (i2 == 0) {
        document.write("<div id=\"dot2"+ i2 +"\" style=\"POSITION: absolute; Z-INDEX: "+ i2 +"; VISIBILITY: visible; TOP: 15px; LEFT: 15px;\"><a href=\"#\"><img src='"+snowsrc2+"' border=\"0\"><\/a><\/div>");
      } else {
        document.write("<div id=\"dot2"+ i2 +"\" style=\"POSITION: absolute; Z-INDEX: "+ i2 +"; VISIBILITY: visible; TOP: 15px; LEFT: 15px;\"><img src='"+snowsrc2+"' border=\"0\"><\/div>");
      }
    }
  }

  function snowIE_NS62() {  // IE and NS6 main animation function
    doc_width2 = ns6up2?window.innerWidth-10 : iecompattest2().clientWidth-10;
		doc_height2=(window.innerHeight && snowdistance2=="windowheight")? window.innerHeight : (ie4up2 && snowdistance2=="windowheight")?  iecompattest2().clientHeight : (ie4up2 && !window.opera && snowdistance2=="pageheight")? iecompattest2().scrollHeight : iecompattest2().offsetHeight;
    for (i2 = 0; i2 < no2; ++ i2) {  // iterate for every dot
      yp2[i2] += sty2[i2];
      if (yp2[i2] > doc_height2-50) {
        xp2[i2] = Math.random()*(doc_width2-am2[i2]-30);
        yp2[i2] = 0;
        stx2[i2] = 0.02 + Math.random()/10;
        sty2[i2] = 1.5 + Math.random();
      }
      dx2[i2] += stx2[i2];
      document.getElementById("dot2"+i2).style.top=yp2[i2]+"px";
      document.getElementById("dot2"+i2).style.left=xp2[i2] + am2[i2]*Math.sin(dx2[i2])+"px";  
    }
    snowtimer2=setTimeout("snowIE_NS62()", 20);
  }

	function hidesnow2(){
		if (window.snowtimer2) clearTimeout(snowtimer2)
		for (i2=0; i2<no2; i2++) document.getElementById("dot2"+i2).style.visibility="hidden"
	}
		

if (ie4up2||ns6up2){
    snowIE_NS62();
		if (hidesnowtime2>0)
		setTimeout("hidesnow2()", hidesnowtime2*1000)
		}
