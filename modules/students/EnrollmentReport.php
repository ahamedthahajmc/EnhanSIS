<?php

include('../../RedirectModulesInc.php');
include 'modules/grades/ConfigInc.php';

if(isset($_SESSION['student_id']) && $_SESSION['student_id'] != '')
{
    $_REQUEST['search_modfunc'] = 'list';
}

if($_REQUEST['modfunc']=='save')
{
 $cur_session_RET=DBGet(DBQuery('SELECT YEAR(start_date) AS PRE,YEAR(end_date) AS POST FROM institute_years WHERE INSTITUTE_ID=\''.UserInstitute().'\' AND SYEAR=\''.UserSyear().'\''));
 if($cur_session_RET[1]['PRE']==$cur_session_RET[1]['POST'])
 {
    $cur_session=$cur_session_RET[1]['PRE'];
 }
 else
 {
    $cur_session=$cur_session_RET[1]['PRE'].'-'.$cur_session_RET[1]['POST'];
 }
	if(count($_REQUEST['st_arr']))
	{	
	$st_list = '\''.implode('\',\'',$_REQUEST['st_arr']).'\'';
        $RET=DBGet(DBQuery('SELECT CONCAT(s.LAST_NAME,\''.', ' .'\',coalesce(s.COMMON_NAME,s.FIRST_NAME)) AS FULL_NAME,s.LAST_NAME,s.FIRST_NAME,s.MIDDLE_NAME,s.STUDENT_ID,s.PHONE,ssm.INSTITUTE_ID,s.ALT_ID,ssm.INSTITUTE_ID AS LIST_INSTITUTE_ID,ssm.GRADE_ID,ssm.START_DATE,ssm.END_DATE,
                (SELECT sec.title FROM  student_enrollment_codes sec where ssm.enrollment_code=sec.id)AS ENROLLMENT_CODE,
                (SELECT sec.title FROM  student_enrollment_codes sec where ssm.drop_code=sec.id) AS DROP_CODE,ssm.INSTITUTE_ID 
                FROM  students s , student_enrollment ssm
                WHERE ssm.STUDENT_ID=s.STUDENT_ID AND s.STUDENT_ID IN ('.$st_list.')  
                ORDER BY FULL_NAME ASC,START_DATE DESC'),array('START_DATE'=>'ProperDate','END_DATE'=>'ProperDate','INSTITUTE_ID'=>'GetInstitute','GRADE_ID'=>'GetGrade'),array('STUDENT_ID'));
        if(count($RET))
	{
			$columns = array('START_DATE'=>_startDate,
			'ENROLLMENT_CODE'=>_enrollmentCode,
			'END_DATE'=>_dropDate,
			'DROP_CODE'=>_dropCode,
			'INSTITUTE_ID'=>_instituteName,
		);
		$handle = PDFStart();
		foreach($RET as $student_id=>$value)
		{
			echo "<table width=100%  style=\" font-family:Arial; font-size:12px;\" >";
			echo "<tr><td width=105>".DrawLogo()."</td><td  style=\"font-size:15px; font-weight:bold; padding-top:20px;\">". GetInstitute(UserInstitute()).' ('.$cur_session.')'."<div style=\"font-size:12px;\">"._studentEnrollmentReport."</div></td><td align=right style=\"padding-top:20px\">". ProperDate(DBDate()) ."<br \>"._studentEnrollmentReport."</td></tr><tr><td colspan=3 style=\"border-top:1px solid #333;\">&nbsp;</td></tr></table>";
			echo '<!-- MEDIA SIZE 8.5x11in -->';
			
				unset($_hani['DrawHeader']);
                            unset($enroll_RET);
                            $i = 0;
                            foreach($value as $key=>$enrollment)
                            {
				
                                $i++;
                                $enroll_RET[$i]['START_DATE'] = ($enrollment['START_DATE']?$enrollment['START_DATE']:'--');
                                $enroll_RET[$i]['ENROLLMENT_CODE'] = ($enrollment['ENROLLMENT_CODE']?$enrollment['ENROLLMENT_CODE']:'--');
                                $enroll_RET[$i]['END_DATE'] = ($enrollment['END_DATE']?$enrollment['END_DATE']:'--');
                                $enroll_RET[$i]['DROP_CODE'] = ($enrollment['DROP_CODE']?$enrollment['DROP_CODE']:'--');
                                $enroll_RET[$i]['INSTITUTE_ID'] = ($enrollment['INSTITUTE_ID']?$enrollment['INSTITUTE_ID']:'--');
                            }
				echo '<table border=0>';
				echo '<tr><td>'._studentName.' :</td>';
				echo '<td>'.$enrollment['FULL_NAME'].'</td></tr>';
				echo '<tr><td>'._studentId.' :</td>';
				echo '<td>'.$student_id.'</td></tr>';
                                echo '<tr><td>'._alternateId.' :</td>';
				echo '<td>'.$enrollment['ALT_ID'].'</td></tr>';
				echo '<tr><td>'._studentGrade.' :</td>';
                                $grade=DBGet(DBQuery('SELECT GRADE_ID FROM student_enrollment WHERE SYEAR='.UserSyear().' AND INSTITUTE_ID='.UserInstitute().' AND STUDENT_ID='.$student_id.' AND (END_DATE>=\''.date('Y-m-d').'\' OR END_DATE IS NULL OR END_DATE=\'0000-00-00\')  '),array('GRADE_ID'=>'GetGrade'));
				echo '<td>'.$grade[1]['GRADE_ID'].'</td></tr>';
				echo '</table>';
                            
                            ListOutputPrint($enroll_RET,$columns,'','',array(),array(),array('print'=>false));
                echo '<span style="font-size:13px; font-weight:bold;"></span>';
				echo '<!-- NEW PAGE -->';
				echo "<div style=\"page-break-before: always;\"></div>";
		}
		PDFStop($handle);
            }
        }
	else
		BackPrompt(_youMustChooseAtLeastOneStudent.'.');
}

if(!$_REQUEST['modfunc'])
{
	DrawBC(""._student." > ".ProgramTitle());

	if($_REQUEST['search_modfunc']=='list')
	{
		echo "<FORM action=ForExport.php?modname=$_REQUEST[modname]&modfunc=save&include_inactive=$_REQUEST[include_inactive]&HaniIMS_PDF=true&head_html=Student+Report+Card method=POST target=_blank>";
	}

	$extra['link'] = array('FULL_NAME'=>false);
	$extra['SELECT'] = ",s.STUDENT_ID AS CHECKBOX";
	if(isset($_SESSION['student_id']) && $_SESSION['student_id'] != '')
    {
        $extra['WHERE'] .= ' AND s.STUDENT_ID=' . $_SESSION['student_id'];
    }
	$extra['functions'] = array('CHECKBOX'=>'_makeChooseCheckbox');
	// $extra['columns_before'] = array('CHECKBOX'=>'</A><INPUT type=checkbox value=Y name=controller onclick="checkAll(this.form,this.form.controller.checked,\'unused\');"><A>');
	$extra['columns_before'] = array('CHECKBOX'=>'</A><INPUT type=checkbox value=Y name=controller onclick="checkAllDtMod(this,\'st_arr\');"><A>');
	$extra['options']['search'] = false;
	$extra['new'] = true;
	

	Search('student_id',$extra,'true');
	if($_REQUEST['search_modfunc']=='list')
	{
		if($_SESSION['count_stu']!=0)
		echo '<div class="text-right p-b-20 p-r-20"><INPUT type=submit class="btn btn-primary" value=\''._createEnrollmentReportForSelectedStudents.'\'></div>';
		echo "</FORM>";
	}
}

function _makeChooseCheckbox($value,$title)
{
//	return '<INPUT type=checkbox name=st_arr[] value='.$value.' checked>';
    //     global $THIS_RET;
    // return "<input name=unused[$THIS_RET[STUDENT_ID]] value=" . $THIS_RET[STUDENT_ID] . "  type='checkbox' id=$THIS_RET[STUDENT_ID] onClick='setHiddenCheckboxStudents(\"st_arr[]\",this,$THIS_RET[STUDENT_ID]);' />";
	global $THIS_RET;
	//	return '<INPUT type=checkbox name=st_arr[] value='.$value.' checked>';
			
			return "<input  class=fd name=unused_var[$THIS_RET[STUDENT_ID]] value=" . $THIS_RET['STUDENT_ID'] . " type='checkbox' id=$THIS_RET[STUDENT_ID] onClick='setHiddenCheckboxStudents(\"st_arr[$THIS_RET[STUDENT_ID]]\",this,$THIS_RET[STUDENT_ID]);' />";
		
}

function _makeTeacher($teacher,$column)
{
	return substr($teacher,strrpos(str_replace(' - ',' ^ ',$teacher),'^')+2);
}
?>