<?php
 
include('../../RedirectModulesInc.php');
DrawBC(""._gradebook." > ".ProgramTitle());
echo '<div id="calculating" style="display: none; padding-top:20px; padding-bottom:15px;"><img src="assets/missing_attn_loader.gif" /><br/><br/><br/><span style="color:#c90000;"><span style=" font-size:15px; font-weight:bold;">'._pleaseWait.'.</span><br /><span style=" font-size:12px;">'._pleaseWait.'</span></span></div>
<div id="resp" style="font-size:14px"></div>';
$mps = GetAllMP(GetMPTable(GetMP(UserMP(),'TABLE')),  UserMP());
$mps = explode(',',str_replace("'",'',$mps));
$message = '<TABLE><TR><TD colspan=7 align=center>';
foreach($mps as $mp)
{
	if($mp && $mp!='0')
		$message .= '<INPUT type=radio name=marking_period_id value='.$mp.($mp==UserMP()?' CHECKED':'').'>'.GetMP($mp).'<BR>';
}
$message .= '</TD></TR></TABLE>';
if($_REQUEST['search_modfunc']=='list')
{
    echo "<FORM name=sav id=sav action=Modules.php?modname=".strip_tags(trim($_REQUEST[modname]))."&modfunc=save method=POST>";
    PopTable_wo_header ('header');
    echo '<CENTER><h4>'._whenDoYouWantToRecalculateTheRunningGpaNumbers.'</CENTER></h4><br/>';
    echo '<center>'.$message.'</center>';
    PopTable ('footer');
}
if(clean_param($_REQUEST['modfunc'],PARAM_ALPHA)=='save')
{
    if($_REQUEST['student'])
    {
        foreach($_REQUEST['student'] as $student_id=>$yes)
        {
            $stu_all.=$student_id.',';
        }
        $stu_all=  substr($stu_all, 0,-1);
        
        echo '<script type=text/javascript>recalculate_gpa(\''.$stu_all.'\',\''.$_REQUEST['marking_period_id'].'\');</script>';

    }
    else
    {
         $err="<b><font color=red>"._noStudentsAreSelected.".</font></b>";  
         unset($_REQUEST['modfunc']);
    }
  }
  echo $err;
if(!$_REQUEST['modfunc'])
{
	$extra['link'] = array('FULL_NAME'=>false);
	$extra['SELECT'] = ",Concat(NULL) AS CHECKBOX ";
	$extra['functions'] = array('CHECKBOX'=>'_makeChooseCheckbox');
	$extra['columns_before'] = array('CHECKBOX'=>'</A><INPUT type=checkbox value=Y name=controller onclick="checkAll(this.form,this.form.controller.checked,\'student\');"><A>');
	$extra['new'] = true;
        $extra['GROUP']="STUDENT_ID";
        
        Search('student_id',$extra);
	if($_REQUEST['search_modfunc']=='list')
	{
            if($_SESSION['count_stu']!=0)
		echo '<BR><CENTER>'.SubmitButton(_recalculateGpa,'','class=btn_re_enroll').'</CENTER>';
		echo "</FORM>";
	}

}

function _makeChooseCheckbox()
{	global $THIS_RET;

		return "<INPUT type=checkbox name=student[".$THIS_RET['STUDENT_ID']."] value=Y>";
}
?>