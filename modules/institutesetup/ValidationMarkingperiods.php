<?php

include('../../RedirectModulesInc.php');
		echo '
	<script language="JavaScript" type="text/javascript">
	
	function formcheck_institute_setup_marking_onedit(){

  	var frmvalidator  = new Validator("marking_period");
  	frmvalidator.addValidation("tables['.$_REQUEST['marking_period_id'].'][TITLE]","req",pleaseEnterTheTitle);
  	frmvalidator.addValidation("tables['.$_REQUEST['marking_period_id'].'][TITLE]","maxlen=50", "Max length for title is 50");
	
	frmvalidator.addValidation("tables['.$_REQUEST['marking_period_id'].'][SHORT_NAME]","req","Please enter the Short Name");
  	frmvalidator.addValidation("tables['.$_REQUEST['marking_period_id'].'][SHORT_NAME]","maxlen=10", "Max length for Short Name is 10");
	
	frmvalidator.addValidation("tables['.$_REQUEST['marking_period_id'].'][SORT_ORDER]","maxlen=5", "Max length for Short Order is 5");
  	frmvalidator.addValidation("tables['.$_REQUEST['marking_period_id'].'][SORT_ORDER]","num", "Enter Only Numeric Value");
	
	frmvalidator.setAddnlValidationFunction("ValidateDate");
	
	}
	




function DisplayFormValues()
{
var str = "";
var elem = frm.elements;
for(var i = 0; i < elem.length; i++)
{
str += "Type:" + elem[i].type + "  ";
str += "Name:" + elem[i].name + "  ";
str += "Value:" + elem[i].value + " ";
str += "\n";
} 

alert(str);
}

   



function ValidateDate()
{
var sm, sd, sy, em, ed, ey, psm, psd, psy, pem, ped, pey, grd ;

var frm = document.forms["marking_period"];
var elem = frm.elements;
for(var i = 0; i < elem.length; i++)
{

if(elem[i].name=="month_tables['.$_REQUEST['marking_period_id'].'][START_DATE]")
{
sm=elem[i];
}
if(elem[i].name=="day_tables['.$_REQUEST['marking_period_id'].'][START_DATE]")
{
sd=elem[i];
}
if(elem[i].name=="year_tables['.$_REQUEST['marking_period_id'].'][START_DATE]")
{
sy=elem[i];
}


if(elem[i].name=="month_tables['.$_REQUEST['marking_period_id'].'][END_DATE]")
{
em=elem[i];
}
if(elem[i].name=="day_tables['.$_REQUEST['marking_period_id'].'][END_DATE]")
{
ed=elem[i];
}
if(elem[i].name=="year_tables['.$_REQUEST['marking_period_id'].'][END_DATE]")
{
ey=elem[i];
}


if(elem[i].name=="month_tables['.$_REQUEST['marking_period_id'].'][POST_START_DATE]")
{
psm=elem[i];
}
if(elem[i].name=="day_tables['.$_REQUEST['marking_period_id'].'][POST_START_DATE]")
{
psd=elem[i];
}
if(elem[i].name=="year_tables['.$_REQUEST['marking_period_id'].'][POST_START_DATE]")
{
psy=elem[i];
}


if(elem[i].name=="month_tables['.$_REQUEST['marking_period_id'].'][POST_END_DATE]")
{
pem=elem[i];
}
if(elem[i].name=="day_tables['.$_REQUEST['marking_period_id'].'][POST_END_DATE]")
{
ped=elem[i];
}
if(elem[i].name=="year_tables['.$_REQUEST['marking_period_id'].'][POST_END_DATE]")
{
pey=elem[i];
}

if(elem[i].name=="tables['.$_REQUEST['marking_period_id'].'][DOES_GRADES]")
{
grd=elem[i];
}

}




try
{
if (false==isDate(sm, sd, sy))
   {
   alert("Please enter the Start Date");
   sm.focus();
   return false;
   }
}
catch(err)
{

}
try
{  
   if (false==isDate(em, ed, ey))
   {
   alert("Please Enter the End Date");
   em.focus();
   return false;
   }
}   
catch(err)
{

}
try
{
   if (false==CheckDate(sm, sd, sy, em, ed, ey))
   {
   em.focus();
   return false;
   }
}
catch(err)
{

}

if (true==validate_chk(grd))
{

try
{  
   if (false==isDate(psm, psd, psy))
   {
   alert("Please enter the Grade Posting Start Date");
   psm.focus();
   return false;
   }
}   
catch(err)
{

}

try
{  
   if (true==isDate(pem, ped, pey))
   {
   if (false==CheckDate(psm, psd, psy, pem, ped, pey))
   {
   pem.focus();
   return false;
   }
   }

}   
catch(err)
{

}






try
{
   if (false==CheckDateMar(sm, sd, sy, psm, psd, psy))
   {
	   psm.focus();
	   return false;
   }
}
catch(err)
{

}



try
{
   if (false==CheckDateMarEnd(pem, ped, pey, em, ed, ey))
   {
	   pem.focus();
	   return false;
   }
}
catch(err)
{

}

}




   return true;
}
   


</script>
';
		



?>