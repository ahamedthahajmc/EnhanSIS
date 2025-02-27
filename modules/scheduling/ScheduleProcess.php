<?php
 
session_start();

include('../../RedirectRootInc.php');
include('../../Warehouse.php');
DBQuery("CREATE TABLE IF NOT EXISTS temp_schedule AS SELECT * FROM schedule WHERE 0");
$course_period_id=$_REQUEST['cp_id'];
$insert=$_REQUEST['insert'];
$student_start_date=DBGet(DBQuery('SELECT START_DATE FROM student_enrollment WHERE student_id='.UserStudentID().' AND INSTITUTE_ID='.  UserInstitute().' AND SYEAR='.UserSyear()));
$student_start_date=$student_start_date[1]['START_DATE'];
$get_cp_date=DBGet(DBQuery('SELECT BEGIN_DATE FROM course_periods WHERE course_period_id='.$course_period_id));
if(strtotime($date)<strtotime($get_cp_date[1]['BEGIN_DATE']))
$date=(strtotime($get_cp_date[1]['BEGIN_DATE'])<strtotime($student_start_date)?$student_start_date:$get_cp_date[1]['BEGIN_DATE']);
//$date=  DBDate();
if($insert=='true')
{
    $course_RET=DBGet(DBQuery("SELECT *,cp.title AS CP_TITLE FROM course_periods cp,course_period_var cpv,institute_periods sp WHERE cp.course_period_id=cpv.course_period_id AND cpv.period_id=sp.period_id AND cp.course_period_id=$course_period_id"));
    $course=$course_RET[1];

    $varified=VerifyStudentSchedule($course_RET);
    if($varified===true)
    {
        $course['MP']=($course['MARKING_PERIOD_ID']!=''?$course['MP']:'FY');
        $qr=DBGet(DBQuery('SELECT END_DATE FROM student_enrollment WHERE STUDENT_ID ='.UserStudentID().' AND INSTITUTE_ID='.UserInstitute().' AND SYEAR='.  UserSyear().' AND ID=(SELECT max(id) FROM student_enrollment WHERE STUDENT_ID ='.UserStudentID().' AND INSTITUTE_ID='.UserInstitute().' AND SYEAR='.  UserSyear().')'));
        if($qr[1]['END_DATE']=='')
        {
            if($course['MARKING_PERIOD_ID']!='')
            {
                $course['MARKING_PERIOD_ID']=$course['MARKING_PERIOD_ID'];
                $mark_end_qry=DBGet(DBQuery('SELECT END_DATE FROM marking_periods WHERE MARKING_PERIOD_ID ='.$course['MARKING_PERIOD_ID'].''));
                $mark_end_date=date('d-M-Y',strtotime($mark_end_qry[1]['END_DATE' ]));
            }
            else 
            {
            $mark_end_qry=DBGet(DBQuery('SELECT END_DATE FROM course_details WHERE COURSE_PERIOD_ID ='.$course_period_id.''));
            $mark_end_date=date('d-M-Y',strtotime($mark_end_qry[1]['END_DATE' ]));
            }
        }
        else
        {         
            $mark_end=date('d-M-Y',strtotime($qr[1]['END_DATE' ]));   
            if($course['MARKING_PERIOD_ID']!='')
            {
                $course['MARKING_PERIOD_ID']=$course['MARKING_PERIOD_ID'];
                $mark_end_qry=DBGet(DBQuery('SELECT END_DATE FROM marking_periods WHERE MARKING_PERIOD_ID ='.$course['MARKING_PERIOD_ID'].''));
                $mark_end_date=date('d-M-Y',strtotime($mark_end_qry[1]['END_DATE' ]));
            }
            else 
            {
                $mark_end_qry=DBGet(DBQuery('SELECT END_DATE FROM course_details WHERE COURSE_PERIOD_ID ='.$course_period_id.''));
                $mark_end_date=date('d-M-Y',strtotime($mark_end_qry[1]['END_DATE' ]));
            }
            if(strtotime($qr[1]['END_DATE' ])<strtotime($mark_end_qry[1]['END_DATE' ]))
            $mark_end_date=$mark_end;
            else
            $mark_end_date=date('d-M-Y',strtotime($mark_end_qry[1]['END_DATE' ]));
        }

         DBQuery("INSERT INTO temp_schedule(SYEAR,INSTITUTE_ID,STUDENT_ID,START_DATE,END_DATE,MODIFIED_BY,COURSE_ID,COURSE_PERIOD_ID,MP,MARKING_PERIOD_ID) values('".UserSyear()."','".UserInstitute()."','".UserStudentID()."','".$date."','".$mark_end_date."','".User('STAFF_ID')."','".$course['COURSE_ID']."','".$course_period_id."','".$course['MP']."','".$course['MARKING_PERIOD_ID']."')");

        $html=$course_period_id."||".$course['CP_TITLE'].'||resp';
        // $html = 'resp';
        $html .= '<label class="checkbox-inline checkbox-switch switch-success switch-xs" id="selected_course_tr_'.$course["COURSE_PERIOD_ID"].'"><INPUT type="checkbox" id="selected_course_'.$course["COURSE_PERIOD_ID"].'" name="selected_course_periods[]" checked="checked" value="'.$course["COURSE_PERIOD_ID"].'"><span></span>'.$course["CP_TITLE"].'</label>';
        $_SESSION['course_periods'][$course_period_id]=$course['CP_TITLE'];
    }
    else
    {
        $html='conf<strong>'.$varified.'</strong>';
        $html .='<input type=hidden id=conflicted_cp value='.$course_period_id.'>';
    }
}
elseif($insert=='false')
{
    DBQuery("DELETE FROM temp_schedule WHERE course_period_id=$course_period_id");
    unset($_SESSION['course_periods'][$course_period_id]);
}
echo $html;
?>
