// JavaScript Document
function showAbstract(who)
{
	abstractChico = document.getElementById( who + "Chico" );
	abstractCompleto = document.getElementById( who + "Completo" );
	
	abstractChico.style.display = "none";
	abstractCompleto.style.display = "inline";
}

function hideAbstract(who)
{
	abstractChico = document.getElementById( who + "Chico" );
	abstractCompleto = document.getElementById( who + "Completo" );
	
	abstractChico.style.display = "inline";
	abstractCompleto.style.display = "none";
}

// Mapas
 function popUpMapa(tipo) {
       tam = screen.width;
			 url = "http://www.rosario.gov.ar/infomapas/";
 		if(tam <= 800){
    		mywindow = window.open(url + "inicio.do?tipoMapa=" + tipo + "&tamano=800",'','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=800,height=600');
    	}else{
    		mywindow = window.open(url +"inicio.do?tipoMapa=" + tipo + "&tamano=1024",'', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=1024,height=768');
    	}
  		mywindow.moveTo(0,0);
 }
 
 function showMapa(linea) 
 {
	 mapas = "lineas/new/";
	 imgtag = top.bodyFrame.document.getElementById('img_mapa');
	 
	 imgtag.src = mapas + "Linea" + linea + ".png";
 }