<?php
 
include 'RedirectRootInc.php';
include 'Warehouse.php';
include 'Data.php';
// include('functions/SqlSecurityFnc.php');

$email = sqlSecurityFilter($_REQUEST['email']);
$p_id = sqlSecurityFilter($_REQUEST['p_id']);

if (isset($_REQUEST['email']) && $_REQUEST['email'] != '') {
    if ($_REQUEST['p_id'] == 0) {
        $result = DBGet(DBQuery('SELECT STAFF_ID FROM people WHERE EMAIL=\'' . $email . '\''));
        $res_stf = DBGet(DBQuery('SELECT STAFF_ID FROM staff WHERE EMAIL=\'' . $email . '\''));
        $res_stu = DBGet(DBQuery('SELECT STUDENT_ID FROM students WHERE EMAIL=\'' . $email . '\''));
    } else {
        $result = DBGet(DBQuery('SELECT STAFF_ID FROM people WHERE EMAIL=\'' . $email . '\' AND STAFF_ID!=' . $p_id));
        $res_stf = DBGet(DBQuery('SELECT STAFF_ID FROM staff WHERE EMAIL=\'' . $email . '\''));
        $res_stu = DBGet(DBQuery('SELECT STUDENT_ID FROM students WHERE EMAIL=\'' . $email . '\''));
    }
    if (count($result) > 0 || count($res_stf) > 0 || count($res_stu) > 0) {
        echo '0_' . urlencode($_REQUEST['opt'])[0];
    } else {
        echo '1_' . urlencode($_REQUEST['opt'])[0];
    }
    exit;
}
