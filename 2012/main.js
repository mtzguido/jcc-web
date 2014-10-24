$(document).ready(function() {
	// Comodidad... target="_blank" tampoco es Strict
	$("a").each(function() {
		$(this).attr("onclick", "window.open(this.href); return false");					 
	});
	// Agrego el link para la inscripcion. Si lo hacia en el html, no lo validaba
	$(".thickbox").each(function() {
		$(this).attr({"href":"#TB_inline?height=100&width=300&inlineId=inscripcion", "onclick":""});							 
	});
	
	// Variables para el efecto del cronograma
	var i = 0;
	$(".titles").each(function() {
		$(this).data("id", i);
		i++;
	});
	var i = 0;
	$(".abstract").each(function() {
		$(this).attr("id", "abstract_"+i);
		i++;
	});
	
	
	function Toogle(t)
	{
	    var label;
		var id = $(t).data("id");
		var p = $("#abstract_"+id);
		if (p.css("display") == "none") {
			label = '-';
			p.show('slow');
		} else {
			label = '+';
			p.hide('slow');
		}
		$(t).children('strong').html('(' + label + ')');
	
	}
	
	// Efecto cronograma
	$(".titles").click(function() {
		Toogle(this);
	});
	
	$(".click").click(function (event){
		event.preventDefault();
	    var id = $(this).attr("data");   
	    
		$("html:not(:animated),body:not(:animated)").animate({ scrollTop: ($("#" + id).offset().top - 50) }, 1500);
	    Toogle("#" + id);
	} );
	
	// Submit de la inscripcion
	/*$("#submit").click(function() {
		if ( jQuery.trim($("#mail").val()) == "" || !isValidEmailAddress( $("#mail").val() ) )
		{
			alert("Debes ingresar un email valido");	
			return false;
		}
		
		$.ajax({   
			type: "POST",
			url: "ajax.php",
			data: "cmd=inscripcion&nya="+$("#nya").val()+"&mail="+$("#mail").val()+"&dni="+$("#dni").val(),
			success: function(msg) 
			{	
				switch (msg)
				{
					case "-2":
						alert("Problemas!");
					break;
					case "-1":
						alert("Ya estas inscripto");
					break;
					case "0":
						alert("Los cupos estan completos, pero has sido inscripto al curso\nSi se genera un lugar, te avisaremos");
					break;
					case "1":
						alert("Has sido inscripto al curso\nSi quieres darte de baja, envia un mail a jcc@fceia.unr.edu.ar");
					break;
				}
				
				tb_remove();
				$("input[type=text]").each(function() {
					$(this).val("");				 
				});
			}
		});
		
		return false;
	});*/
});

function isValidEmailAddress(emailAddress) 
{
	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	return pattern.test(emailAddress);
}
