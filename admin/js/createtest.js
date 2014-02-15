function rendertest()
{
	$("#testname").text(test[1]+" ("+test[3]+")")
	//alert(test[0])
	added=0;
	$("#testdesc").text(test[2])
	
}
function allmod(i)
{
	//acrbody  ='<h3 modid="'+i+'"><table width="100%"><tr><td><a href="#" class="mod_disp" modid="'+i+'">'+module[i][0]+'</a></td>'
	//acrbody +='<td align="right" width="20%"><span class="deletmodule" mod="'+i+'" href="#">Delete</span></td></tr></table></h3>\n'
	//acrbody +='<div><table width=90% align=center>\n'
	//acrbody +='<tr><td colspan=3>'+$("<div\>").text(module[i][1]).html()+'<br><br></td><td><a class="editmodule" mod="'+i+'" href="#">Edit</a></td></tr>'
	acrbody  ='<h3 modid="'+i+'"><a href="#" class="mod_disp" modid="'+i+'">'+module[i][0]+'</a></h3>\n'
	acrbody +='<div><table width=90% align=center>\n'
	acrbody +='<tr><td colspan="2">'+$("<div\>").text(module[i][1]).html()+'</td>'
	
	/// module wise pass marks
	acrbody +='<td align="center">Qualifying marks: <span mod="'+i+'" id="ipmodqua-'+i+'">'+module[i][5]+'</span><br /><br />\n'//<!--<input type="text" readonly="readonly" mod="'+i+'" id="modqu-'+i+'" value="'+module[i][3][0][5]+'" style="border: 0pt none; color: rgb(246, 147, 31); font-weight: bold; text-align:center;" />-->\n'
	acrbody +='<div class="slidermod" style="width:200px" mod="'+i+'" id="modqua-'+i+'" val="'+module[i][5]+'" maxval="'+maxmodqlval(i)+'"></div></td>\n'
	
	acrbody +='<td width="20px"><a class="editmodule" title="Edit this module" mod="'+i+'" href="#"><span class="ui-icon ui-icon-pencil"/></a>'
	acrbody +='<a title="Delete this module" href="#"><span class="ui-icon ui-icon-closethick deletmodule" mod="'+i+'" href="#">close</span></a></td>'
	acrbody +='</tr></table><br><br>'
	acrbody +='<table  class="questiontable" width="90%" align="center"><tr><th >Subject</th><th> Number of Question </th width=20px><th>Delete</th>'
	for ( j in module[i][3])
	{
		
		//alert(i+" "+j+" "+module[i][3][j][0])
		acrbody +="<tr>\n"
		acrbody +='<td  class="questiontable" align=center><select class="subsel" mod="'+i+'" sub="'+j+'" id="mod-sub-sel-'+i+'-'+j+'">\n'
		for(m in subject)
		{
			if(m == module[i][3][j][0])
				acrbody += '<option value="'+m+'" selected="selected">'+subject[m][1]+'</option>\n'
			else
				acrbody += '<option value="'+m+'">'+subject[m][1]+'</option>\n'
				//alert(m)
		}
		acrbody +='</select></td>\n'
		acrbody +='<td  class="questiontable" align="center"><span class="tstpad" mod="'+i+'" sub="'+j+'" id="ipqsn-'+i+'-'+j+'">'+module[i][3][j][1]+'</span><br /><div class="sliderqsn" style="width:200px" mod="'+i+'" sub="'+j+'" id="qsn-'+i+'-'+j+'" val="'+module[i][3][j][1]+'" maxval="'+subject[module[i][3][j][0]][4]+'"></div></td>\n'
		//acrbody +='<td><input type="text" readonly="readonly" size="5" mod="'+i+'" sub="'+j+'" id="ipqsn-'+i+'-'+j+'" value="'+module[i][3][j][1]+'" /></td>\n'
		acrbody +='<td class="questiontable" width="20px" align=center><a href="#" class="ui-dialog-titlebar-close ui-corner-all delmodsub" role="button" mod="'+i+'" sub="'+j+'" title="Delete this subject"><span class="ui-icon ui-icon-closethick">close</span></a></td>\n'
		acrbody +='</tr>\n'
	}
	acrbody +='<tr><td><a class="addsubmodule" mod="'+i+'" href="#">Add Subject</a></td></tr>\n' 
	acrbody +="</table>\n"
	acrbody +='</div>\n'
	return acrbody
}

function minmaxqsn(i)
{
	minval=subject[module[i][3][0][0]][4]
	for(j in module[i][3])
	{
		if(subject[module[i][3][j][0]][4]<minval)
			minval=subject[module[i][3][j][0]][4]
	}
	return minval
}

function maxminqsn(i)
{
	minval=module[i][3][0][1]
	for(j in module[i][3])
	{
		if(module[i][3][j][1] < minval)
			minval=module[i][3][0][1]
	}
	return minval
}

function maxmodqlval(i)
{
	if(module[i][2]==1)
		return module[i][3][0][1]
	else{
		x=0;
		for(j in module[i][3])
		{
			x += parseInt(module[i][3][j][1])
		}
		return x;
	}
}

function anymod(i)
{
	acrbody  ='<h3 modid="'+i+'"><a href="#" class="mod_disp" modid="'+i+'">'+module[i][0]+'</a></h3>\n'
	acrbody +='<div><table width=90% align=center>\n'
	acrbody +='<tr><td colspan="2">'+$("<div\>").text(module[i][1]).html()+'</td>'
	
	/// module wise pass marks
	acrbody +='<td align="center">Qualifying marks: <span mod="'+i+'" id="ipmodqua-'+i+'">'+module[i][5]+'</span><br /><br />\n'//<!--<input type="text" readonly="readonly" mod="'+i+'" id="modqu-'+i+'" value="'+module[i][3][0][5]+'" style="border: 0pt none; color: rgb(246, 147, 31); font-weight: bold; text-align:center;" />-->\n'
	acrbody +='<div class="slidermod" style="width:200px" mod="'+i+'" id="modqua-'+i+'" val="'+module[i][5]+'" maxval="'+maxmodqlval(i)+'"></div></td>\n'
	
	
	acrbody +='<td width="20px"><a class="editmodule" title="Edit this module" mod="'+i+'" href="#"><span class="ui-icon ui-icon-pencil"/></a>'
	acrbody +='<a title="Delete this module" href="#"><span class="ui-icon ui-icon-closethick deletmodule" mod="'+i+'" href="#">close</span></a></td>'
	
	
	
	acrbody +='</tr></table><br><br>'
	acrbody +='<table class="questiontable" width="90%" align="center"><tr><th >Subject</th><th> Number of Question </th width="20px"><th>Delete</th>'
	//acrbody +='<tr><td>
	//alert(j)
	iscounteradded=false
	for ( j in module[i][3])
	{
		
		//alert(i+" "+j+" "+module[i][3][j][0])
		acrbody +="<tr>\n"
		acrbody +='<td  class="questiontable" align="center"><select class="subsel" mod="'+i+'" sub="'+j+'" id="mod-sub-sel-'+i+'-'+j+'">\n'
		for(m in subject)
		{
			if(m == module[i][3][j][0])
				acrbody += '<option value="'+m+'" selected="selected">'+subject[m][1]+'</option>\n'
			else
				acrbody += '<option value="'+m+'">'+subject[m][1]+'</option>\n'
				//alert(m)
		}
		acrbody +='</select></td>\n'
		if(!iscounteradded){
			acrbody +='<td  class="questiontable" rowspan="'+module[i][3].length+'" align="center"><span mod="'+i+'" sub="-1" id="ipqsn-'+i+'">'+module[i][3][0][1]+'</span><br /><br /><!--<input type="text" readonly="readonly" mod="'+i+'" sub="-1" id="ipqsn-'+i+'" value="'+module[i][3][0][1]+'" style="border: 0pt none; color: rgb(246, 147, 31); font-weight: bold; text-align:center;" />-->\n'
			acrbody +='<div class="sliderqsn" style="width:200px" mod="'+i+'" sub="-1" id="qsn-'+i+'" val="'+module[i][3][0][1]+'" maxval="'+minmaxqsn(i)+'"></div></td>\n'
			iscounteradded=true;
		}
		//acrbody +='<td><div class="sliderqsn" style="width:200px" mod="'+i+'" sub="'+j+'" id="qsn-'+i+'-'+j+'" val="'+module[i][3][j][1]+'" maxval="'+subject[module[i][3][j][0]][4]+'"></div></td>\n'
		//acrbody +='<td><input type="text" readonly="readonly" size="5" mod="'+i+'" sub="'+j+'" id="ipqsn-'+i+'-'+j+'" value="'+module[i][3][j][1]+'" /></td>\n'
		acrbody +='<td  class="questiontable" align=center><a href="#" class="delmodsub" role="button" mod="'+i+'" sub="'+j+'" title="Delete this subject"><span class="ui-icon ui-icon-closethick">close</span></a></td>\n'
		acrbody +='</tr>\n'
	}
	acrbody +='<tr><td><a class="addsubmodule" mod="'+i+'" href="#">Add Subject</a></td></tr>\n' 
	acrbody +="</table>\n"
	acrbody +='</div>\n'
	return acrbody
}



function rendermod()
{
	//alert()
	$("#testaccordion").accordion( "destroy" )
	$("#testaccordion").empty()
	acrbody=""
	try{
	for (i in module)
	{
		//create html
		if(module[i][2]==0)
			acrbody +=  allmod(i)
		else	
			acrbody +=  anymod(i)
		//alert("rendmod: "+i+" "+module[i][3]);
			
	}
	}catch(e){alert("p "+e)}
	$( "#testaccordion" ).append(acrbody)
	
	$( ".slidermod" ).each(function(){
		$(this).slider({
			range: "min",
			value: parseInt($(this).attr("val")),
			min: 0,
			max: parseInt($(this).attr("maxval")),
			slide: function( event, ui ) {
				//$( "#amount" ).val( "$" + ui.value );
				try{
				id=$(this).attr("id")
				i=parseInt($(this).attr("mod"))
				//j=parseInt($(this).attr("sub"))
				/*if(j==-1)
				{
					for ( j in module[i][3])
						module[i][3][j][1]=parseInt(ui.value)
				}
				else*/
				module[i][5]=parseInt(ui.value)
				$( "#ip"+id ).text( ui.value )
				}catch(e){alert("ip "+e)}
				//alert($( "#qsn"+id ).attr("value"))
			}
		});	
	});	
	
	//make slider
	$( ".sliderqsn" ).each(function(){
		$(this).slider({
			range: "min",
			value: parseInt($(this).attr("val")),
			min: 1,
			max: parseInt($(this).attr("maxval")),
			slide: function( event, ui ) {
				//$( "#amount" ).val( "$" + ui.value );
				try{
				id=$(this).attr("id")
				i=parseInt($(this).attr("mod"))
				j=parseInt($(this).attr("sub"))
				//alert($( "#modqua-"+i ).attr("val"))
				if(j==-1)
				{
					for ( j in module[i][3])
						module[i][3][j][1]=parseInt(ui.value)
				}
				else
					module[i][3][j][1]=parseInt(ui.value)
				$( "#ip"+id ).text( ui.value )
				$( "#modqua-"+i ).slider( "option", "max", maxmodqlval(i));
				$( "#testqua" ).slider( "option", "max", maxtestqlval());
				}catch(e){alert("ip "+e)}
				//alert($( "#qsn"+id ).attr("value"))
			}
		});	
	});	
	//update subject
	$(".subsel").change(function(){
		i=parseInt($(this).attr("mod"))
		j=parseInt($(this).attr("sub"))
		//str="";
		$("#mod-sub-sel-"+i+"-"+j+" option:selected").each(function () {
                subid = parseInt($(this).val());
         });
         //alert(str)
         module[i][3][j][0]=subid
         if(subject[subid][4]<module[i][3][j][1])
         	module[i][3][j][1]=subject[subid][4]
         	
         if(module[i][5]>maxmodqlval(i))
         	module[i][5]=maxmodqlval(i)
         if(test[4] > maxtestqlval())
         	test[4] = maxtestqlval()
         
         render()
		
	})	
	//add subject
	$(".addsubmodule").click(function(){
		//alert(module[i][3])
		//alert(module[i])		
		i=parseInt($(this).attr("mod"))
		if(module[i][2]==0)
			module[i][3].push([0,1])
		else{
			num=Math.min(module[i][3][0][1],subject[0][4])
			//alert(num)
			module[i][3].push([0,num])
		}
		$( "#modqua-"+i ).slider( "option", "max", maxmodqlval(i) );
		//alert(module[i][3])
		//alert(module[i])
		if(module[i][5]>maxmodqlval(i))
         	module[i][5]=maxmodqlval(i)
         if(test[4] > maxtestqlval())
         	test[4] = maxtestqlval()
		render()
		return false;
	})
	$(".editmodule").click(function(){
		i=parseInt($(this).attr("mod"))
		enablediaclosebutton()
		editmodule(i)
		return false;
	})
	//del sub
	$(".delmodsub").click(function(){
		i=parseInt($(this).attr("mod"))
		j=parseInt($(this).attr("sub"))
		if(module[i][2]==0 && module[i][3].length<2)
		{
			alertdia("Madatory module must contain atlest one subject")
			return false
		}
		else if(module[i][2]==1 && module[i][3].length<3)
		{
			alertdia("Optional module must contain atlest two subject")
			return false
		}
		confdia("Are u sure?",function(){
			module[i][3].splice(j,1)
			render();
		});
		return false;
	})
	
	//Delete module
	
	$(".deletmodule").click(function(){
		i=parseInt($(this).attr("mod"))
		//j=parseInt($(this).attr("sub"))
		if(module.length<2)
		{
			alertdia("Minimum one mudule required")
			return false
		}
		confdia("Are you sure?",function(){
			module.splice(i,1)
			render();
		})
		return false;
	})
	//make accordion
	$( "#testaccordion" ).accordion({
		autoHeight: false,
		//navigation: true,
		//collapsible:true,
		change: function(event, ui) { activeind=parseInt(ui.newHeader.attr("modid")); },
		active: activeind
	});//.accordion( "activate" , active )
}

activeind=0;

function editmodule(i,n)
{
	//alert(i)
	/*if(i==module.length)
		module[i]=["","",0,[[0,1]],10]*/
	if(n==1 || n==2)
	{
		i=module.length
		module[i]=["","",0,[[0,1]],10,0]
	}
	body  ='<fieldset><form id="cmdform">'
	body +='<div class="ui-widget"></div><br />'
	body +='	<label class="login" for="modname">Name:</label>'
	body +='	<input class="login text ui-widget-content ui-corner-all"  type="text" name="modname" id="modname" value="'+module[i][0]+'"/>'
	body +='	<label class="login" for="moddesc">Description</label>'
	body +='	<textarea class="login text ui-widget-content ui-corner-all" name="moddesc" id="moddesc" >'+module[i][1]+'</textarea>'
	body +='	<label class="login" for="modoptional">Name:</label>'
	body +='	<select class="login text ui-widget-content ui-corner-all" name="modoptional" id="modoptional">'
	body +='	<option value=0'
	if(module[i][2]==0) body+=' selected="selected"'	
	body +='>All Subject are Mandatory</option>'
	body +='	<option value=1'
	if(module[i][2]==1) body+=' selected="selected"'
	body +='>Any One</option>'
	body +='	</select>'
	body +='	<label class="login" for="moddur">Duration(in min):</label>'
	body +='	<input class="login text ui-widget-content ui-corner-all"  type="text" name="moddur" id="moddur" value="'+module[i][4]+'"/>'
	body +='</form></fieldset>'
	body +='<br><a id="cmdsubmit">Done</a> '
	if(n!=2) body+='<a id="cmdcancel">Cancel</a>'
	
	if(n==1 || n==2)
		diaopen("Add Module",body)
	else
		diaopen("Update Module",body)
	//alert(i)
	$("#moddur").change(function(){
		//alert($(this).val())
		try{
		val=parseInt($(this).val())
		//alert(val)
		if(val>0 ){$(this).val(val)}
			
		else
			$(this).val(1)
		}catch(e){alert(e) }
	})
	fcls=function(){
		//alert("her")
		res=$("#cmdform").serializeArray()
		module[i][0]=$.trim(res[0].value)
		module[i][1]=$.trim(res[1].value)
		module[i][2]=parseInt($.trim(res[2].value))
		module[i][4]=parseInt($.trim(res[3].value))
		if(module[i][0]==null || module[i][0]=="" || module[i][1]==null || module[i][1]=="")
		{
			$("#cmdform div").html('<div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all"> <p><small> <strong>Alert:</strong> Field can not be empty.<small></p></div>')	
			return false;
		}
		if(module[i][4]==0)
		{
			$("#cmdform div").html('<div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all"> <p><small> <strong>Alert:</strong> Duration field should be positive integer<small></p></div>')
			return false;
		}
		if(module[i][2]==1 && module[i][3].length==1)
		{
			num=Math.min(subject[1][4],module[i][3][0][1])
			module[i][3].push([1,num])
		}
		if(module[i][2]==1)
		{
			num=maxminqsn(i)
			for(j in module[i][3])
				module[i][3][j][1]=num
		}
		render();
		//alert(res[0].name)
		diaclose();
		
		return false
	}	
	
	$("#cmdsubmit").button()
	$("#cmdform").submit(fcls)
	$("#cmdsubmit").click(fcls)
	if(n!=2){
		$("#cmdcancel").button()
		$("#cmdcancel").click(function(){
			if(n==1)
			{
				//i=module.length
				module.splice(i,1)
			}
			diaclose();
			render()
		})
	}
}

function addtest(fnc,type)
{
	body  ='<fieldset><form id="ctdform">'
	body +='<div class="ui-widget"></div><br />'
	body +='	<label class="login" for="testname">Name:</label>'
	body +='	<input class="login text ui-widget-content ui-corner-all"  type="text" name="testname" id="testname" value="'+test[1]+'"/>'
	body +='	<label class="login" for="testdesc">Description</label>'
	body +='	<textarea class="login text ui-widget-content ui-corner-all" name="testdesc" id="testdesc" >'+test[2]+'</textarea>'
	body +='	<label class="login" for="testcode">Test code:</label>'
	body +='	<input class="login text ui-widget-content ui-corner-all"  type="text" name="testcode" id="testcode" value="'+test[3]+'"/>'
	body +='</form></fieldset>'
	body +='<a id="ctdsubmit">Done</a>'
	if(type==1)
	{
		body +='<a id="ctdcancel">Cancel</a>'
		diaopen("Add Test",body)
	}
	else
	{
		diaopen("Modify Test",body)
	}
	
	//alert()
	fcls=function(){
		res=$("#ctdform").serializeArray()
		//alert($("ctdform").html())
		test[1]=$.trim(res[0].value)
		test[2]=$.trim(res[1].value)
		test[3]=$.trim(res[2].value)
		if((test[2]==null || test[2]=="") || (test[1]==null || test[1]=="") || (test[3]==null || test[3]==""))
		{
			$("#ctdform div").html('<div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all"> <p><small> <strong>Alert:</strong> Field can not be empty.<small></p></div>')	
			return false;
		}
		//render();
		diaclose();
		if(typeof fnc == "function")
			fnc()
		return false
	}	
	
	$("#ctdsubmit").button()
	$("#ctdform").submit(fcls)
	$("#ctdsubmit").click(fcls)
	if(type==1)
	{
		$("#ctdcancel").button()
		.click(function(){
			window.onbeforeunload=null;
			window.open("tstmng.php","_self")
		})
	}
}

/*function htmlEscape(str) {
    return String(str)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
}*/


function alertdia(txt)
{
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
function confdia(txt,onyes)
{
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