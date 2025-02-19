<?php
 
	include('RedirectRootInc.php'); 
        include('Warehouse.php');
include('ConfigInc.php');
// include('functions/SqlSecurityFnc.php');

$student_fname = sqlSecurityFilter($_REQUEST['fn']);
$student_mname = sqlSecurityFilter($_REQUEST['mn']);
$student_lname = sqlSecurityFilter($_REQUEST['ln']);
$student_byear = sqlSecurityFilter($_REQUEST['byear']);
$student_bmonth = sqlSecurityFilter($_REQUEST['bmonth']);
$student_bday = sqlSecurityFilter($_REQUEST['bday']);
	
                                        if($student_bmonth == 'JAN')
						$student_bmonth = '01';
					elseif($student_bmonth == 'FEB')
						$student_bmonth = '02';
					elseif($student_bmonth == 'MAR')
						$student_bmonth = '03';
					elseif($student_bmonth == 'APR')
						$student_bmonth = '04';
					elseif($student_bmonth == 'MAY')
						$student_bmonth = '05';
					elseif($student_bmonth == 'JUN')
						$student_bmonth = '06';
					elseif($student_bmonth == 'JUL')
						$student_bmonth = '07';
					elseif($student_bmonth == 'AUG')
						$student_bmonth = '08';
					elseif($student_bmonth == 'SEP')
						$student_bmonth = '09';
					elseif($student_bmonth == 'OCT')
						$student_bmonth = '10';
					elseif($student_bmonth == 'NOV')
						$student_bmonth = '11';
					elseif($student_bmonth == 'DEC')
						$student_bmonth = '12';
   $student_birthday =trim($student_byear).'-'.  trim($student_bmonth).'-'.  trim($student_bday);
if(trim($student_mname)=='')
$chechk_stu = 'SELECT s.student_id AS ID FROM students s,student_enrollment se WHERE s.student_id=se.student_id AND lcase(s.last_name)="'.strtolower($student_lname).'" AND lcase(s.first_name)="'.strtolower($student_fname).'" AND (lcase(s.middle_name)="" OR lcase(s.middle_name) IS NULL ) AND s.birthdate="'.$student_birthday.'"  AND se.syear="'.$_SESSION['UserSyear'].'" AND se.institute_id="'.$_SESSION['UserInstitute'].'" ';
else   
$chechk_stu = 'SELECT s.student_id AS ID FROM students s,student_enrollment se WHERE s.student_id=se.student_id AND lcase(s.last_name)="'.strtolower($student_lname).'" AND lcase(s.first_name)="'.strtolower($student_fname).'" AND lcase(s.middle_name)="'.strtolower($student_mname).'" AND s.birthdate="'.$student_birthday.'"  AND se.syear="'.$_SESSION['UserSyear'].'" AND se.institute_id="'.$_SESSION['UserInstitute'].'"  ';
$chechk_stu_result = DBGet(DBQuery($chechk_stu));
$prev_student = count($chechk_stu_result);
  echo $prev_student;  

?>
