<?php
 

include 'RedirectRootInc.php';
include 'Warehouse.php';
include 'Data.php';

$email = sqlSecurityFilter($_REQUEST['email']);
$id = sqlSecurityFilter($_REQUEST['id']);
$type = sqlSecurityFilter($_REQUEST['type']);

        if(isset($_REQUEST['email']) && $_REQUEST['email']!='')
        {
            if($type=='3')
            {
                if($_REQUEST['id']==0)
        $result_stu=DBGet(DBQuery('SELECT COUNT(1) as EMAIL_EX FROM students WHERE EMAIL=\''.$email.'\''));
                else
        $result_stu=DBGet(DBQuery('SELECT COUNT(1) as EMAIL_EX FROM students WHERE EMAIL=\''.$email.'\' AND STUDENT_ID!='.$id));    

        $result_pe=DBGet(DBQuery('SELECT COUNT(1) as EMAIL_EX FROM people WHERE EMAIL=\''.$email.'\''));
        $result_stf=DBGet(DBQuery('SELECT COUNT(1) as EMAIL_EX FROM staff WHERE EMAIL=\''.$email.'\''));
            }
            if($type=='2')
            {
                if($_REQUEST['id']==0)
        $result_stf=DBGet(DBQuery('SELECT COUNT(1) as EMAIL_EX  FROM staff WHERE EMAIL=\''.$email.'\''));
                else
        $result_stf=DBGet(DBQuery('SELECT COUNT(1) as EMAIL_EX  FROM staff WHERE EMAIL=\''.$email.'\' AND STAFF_ID!='.$id));    
                
        $result_pe=DBGet(DBQuery('SELECT COUNT(1) as EMAIL_EX FROM people WHERE EMAIL=\''.$email.'\''));
        $result_stu=DBGet(DBQuery('SELECT COUNT(1) as EMAIL_EX FROM students WHERE EMAIL=\''.$email.'\''));
            }
            
            if($type=='4')
            {
                if($_REQUEST['id']==0)
        $result_stf=DBGet(DBQuery('SELECT COUNT(1) as EMAIL_EX  FROM people WHERE EMAIL=\''.$email.'\''));
                else
        $result_stf=DBGet(DBQuery('SELECT COUNT(1) as EMAIL_EX  FROM people WHERE EMAIL=\''.$email.'\' AND STAFF_ID!='.$id));    
                
        $result_pe=DBGet(DBQuery('SELECT COUNT(1) as EMAIL_EX FROM students WHERE EMAIL=\''.$email.'\''));
        $result_stu=DBGet(DBQuery('SELECT COUNT(1) as EMAIL_EX FROM staff WHERE EMAIL=\''.$email.'\''));
            }
            
            if($result_stf[1]['EMAIL_EX']==0 && $result_pe[1]['EMAIL_EX']==0 && $result_stu[1]['EMAIL_EX']==0 )
            {
                echo '0_'.$type;
            }
            else
            {
                echo '1_'.$type;
            }
            exit;
        }
?>
