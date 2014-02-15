$(document).ready(function(){

	// hide #back-top first
	$("#back-top").hide();
	
	// fade in #back-top
	$(function () {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 100) {
				$('#back-top').fadeIn();
			} else {
				$('#back-top').fadeOut();
			}
		});

		// scroll body to 0px on click
		$('#back-top a').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});

	$(".dobDatePicker").datepicker({ 
		dateFormat: 'yy-mm-dd',
		changeMonth: true,
	        changeYear: true,
		yearRange: "c-50:c+50",
		maxDate: new Date, 
		defaultDate: new Date()
	});

});

/*$(function() {

});*/	

/*function validateProfileForm()
{
	//var txtDate = document.forms["editProfile"]["dob"].value;
	alert(txtDate);
	isDate(txtDate);
}*/

function isDate(txtDate)
{
  var currVal = txtDate;
  if(currVal == '')
    return false;
  
  //Declare Regex  
  var rxDatePattern = /^(\d{1,4})(\/|-)(\d{1,2})(\/|-)(\d{1,2})$/; 
  var dtArray = currVal.match(rxDatePattern); // is format OK?

  if (dtArray == null)
     return false;
 
  //Checks for mm/dd/yyyy format.
   dtDay= dtArray[5]; 
   dtMonth = dtArray[3];
   dtYear = dtArray[1];

  if (dtMonth < 1 || dtMonth > 12)
      return false;
  else if (dtDay < 1 || dtDay> 31)
      return false;
  else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
      return false;
  else if (dtMonth == 2)
  {
     var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
     if (dtDay> 29 || (dtDay ==29 && !isleap))
          return false;
  }
  return true;
}

/***********************
 *  Auxilary function  *
 ***********************/
//function updateTips( t ) {}

function checkLength( o, n, min, max ) {
	if(checkLengthVal(o.val(), n, min, max)){
		return true;
	}
	o.addClass( "ui-state-error" );
	return false;
}
function checkLengthVal(v,n,min,max){
	//alert(v);
	if ( v.length > max || v.length < min ) {
		if(window.updateTips)
		updateTips( "Length of " + n + " must be between " + min + " and " + max + "." );
		return false;
	} else {
		return true;
	}
}
function checkRegexpVal(v, n, regexp){
	if ( !( regexp.test( v ) ) ) {
		if(window.updateTips)
		updateTips( n );
		return false;
	} else {
		return true;
	}
}
function checkRegexp( o, regexp, n ) {
	if(checkRegexpVal(o.val(), n, regexp))
		return true
	o.addClass( "ui-state-error" );
	return false
}
function checkMailVal(v){
	return checkRegexpVal( v, "eg. ui@jquery.com", /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i );
}
function checkMail(o){
	if(checkMailVal(o.val()))
		return true;
	o.addClass( "ui-state-error" );
	return false
}
/***********************
 *  Auxilary function  *
 ***********************/
