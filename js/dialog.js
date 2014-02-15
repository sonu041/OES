var oesdialog=null;
function intiatedialog(){
	oesdialog=$('<div></div>')
	oesdialog.attr( "attr","no" )
	//.css("width","400px")
	$("body").append(oesdialog)
	oesdialog.dialog({
			modal: true,
			autoOpen: false,
			closeOnEscape: false,
			minWidth: 400,
			beforeClose: function(event, ui) { 
				if(oesdialog.attr("attr")=="no")
					return false
				oesdialog.attr( "attr","no" )
				return true 
			}
		});
	/*wdi=oesdialog.dialog( "widget" )
	//wdi.css("width","400px")
	alert(wdi.css("width"))*/
}

function diaclose(){
	oesdialog.attr( "attr","yes" )
	oesdialog.dialog( "close" )
	//$( "#dialogtest" ).attr( "attr","no" )
	//if(typeof fnc != "function")
		//fnc();
	
}
function diaonclose(func){
	oesdialog.bind( "dialogclose", function(event, ui) {
			func(event, ui)
			oesdialog.bind( "dialogclose", function(event, ui) { });
		});
}
function enablediaclosebutton()
{
	if(oesdialog==null)
		intiatedialog()
	oesdialog.attr( "attr","yes" )
}
function diaopen(title,body,func)
{
	diaopen(title,body)
	oesdialog.bind( "dialogopen", function(event, ui) {
			func(event, ui)
			oesdialog.bind( "dialogopen", function(event, ui) {});
	});
}
function diaopen(title,body)
{
	//alert("here");
	try{
	if(oesdialog==null)
		intiatedialog()
	if(title!=null && title!="")
	{
		oesdialog.dialog( "option", "title",title )
	}
	if(body!=null && body!="")
	{
		oesdialog.html(body)
	}
	
	oesdialog.dialog( "open" )
	$(".ui-dialog-titlebar-close").hide();
	}catch(e){alert(e)}
	//diacls=oesdialog.dialog( "widget" ).children().children().next().remove()
}

function alertdia(txt)
{
	if(oesdialog==null)
		intiatedialog()
	body  ='<fieldset>'
	body +='<div class="ui-widget">'
	body +='<div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">'
	body +='<p>'+txt+'</p></div>'
	body +='</div></br>'
	body +='</fieldset>'
	body +='<a id="alertok">Ok</a>'
	diaopen("Alert",body)
	$("#alertok").button()
	$("#alertok").click(function(){
		diaclose()
	})
}

function alertdia(txt,fnc)
{
	if(oesdialog==null)
		intiatedialog()
	body  ='<fieldset>'
	body +='<div class="ui-widget">'
	body +='<div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">'
	body +='<p>'+txt+'</p></div>'
	body +='</div></br>'
	body +='</fieldset>'
	body +='<a id="alertok">Ok</a>'
	diaopen("Alert",body)
	$("#alertok").button()
	$("#alertok").click(function(){
		diaclose()
		fnc();
	})
}

function confdia(txt,onyes)
{
	if(oesdialog==null)
		intiatedialog()
	body  ='<fieldset>'
	body +='<div class="ui-widget">'
	body +='<div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">'
	body +='<p>'+txt+'</p></div>'
	body +='</div></br>'
	body +='</fieldset>'
	body +='<a id="confok">Ok</a><a id="confno">Cancel</a>'
	diaopen("Confirm",body)
	$("#confok, #confno").button()
	$("#confno").click(function(){
		diaclose()
	})
	$("#confok").click(function(){
		onyes()
		diaclose()
	})
}