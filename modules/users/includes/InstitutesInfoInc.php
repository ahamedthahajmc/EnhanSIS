
<?php



if ((isset($_REQUEST['teacher_view']) && ($_REQUEST['teacher_view'] != 'y')) || (!isset($_REQUEST['teacher_view']) && isset($_REQUEST['values']))) {
    $sql_institute_admin = 'SELECT ssr.INSTITUTE_ID FROM institutes s,staff st INNER JOIN staff_institute_relationship ssr USING(staff_id) WHERE s.id=ssr.institute_id AND ssr.syear=' . UserSyear() . ' AND st.staff_id=' . User('STAFF_ID');
    $institute_admin = DBGet(DBQuery($sql_institute_admin));

    foreach ($institute_admin as $index => $institute) {
        if ($_REQUEST['day_values']['START_DATE'][$institute['INSTITUTE_ID']]) {
            $start_date = $_REQUEST['year_values']['START_DATE'][$institute['INSTITUTE_ID']] . "-" . $_REQUEST['month_values']['START_DATE'][$institute['INSTITUTE_ID']] . "-" . $_REQUEST['day_values']['START_DATE'][$institute['INSTITUTE_ID']];
            $check_start_date = $_REQUEST['year_values']['START_DATE'][$institute['INSTITUTE_ID']] . '-' . $_REQUEST['month_values']['START_DATE'][$institute['INSTITUTE_ID']] . '-' . $_REQUEST['day_values']['START_DATE'][$institute['INSTITUTE_ID']];
        } else {
            $start_date = '';
            $check_start_date = '';
        }
        if ($_REQUEST['day_values']['END_DATE'][$institute['INSTITUTE_ID']]) {
            $end_month = array("01" => "JAN", "02" => "FEB", "03" => "MAR", "04" => "APR", "05" => "MAY", "06" => "JUN", "07" => "JUL", "08" => "AUG", "09" => "SEP", "10" => "OCT", "11" => "NOV", "12" => "DEC");
            foreach ($end_month as $ei => $ed) {
                if ($ed == $_REQUEST['month_values']['END_DATE'][$institute['INSTITUTE_ID']])
                    $_REQUEST['month_values']['END_DATE'][$institute['INSTITUTE_ID']] = $ei;
            }
            $end_date = $_REQUEST['year_values']['END_DATE'][$institute['INSTITUTE_ID']] . "-" . $_REQUEST['month_values']['END_DATE'][$institute['INSTITUTE_ID']] . "-" . $_REQUEST['day_values']['END_DATE'][$institute['INSTITUTE_ID']];
        } else {
            $end_date = '';
        }

        if (($start_date != '' && VerifyDate(date('d-M-Y', strtotime($start_date)))) || ($end_date != '' && VerifyDate(date('d-M-Y', strtotime($end_date)))) || ($start_date == '' && $end_date == '')) {
            // if (is_array($institute) && in_array(UserInstitute(),$institute)) {
            if ((is_array($institute) && in_array(UserInstitute(),$institute)) || (isset($_REQUEST['values']['INSTITUTES'][$institute['INSTITUTE_ID']]) && $_REQUEST['values']['INSTITUTES'][$institute['INSTITUTE_ID']] == 'Y' && $_REQUEST['day_values']['START_DATE'][$institute['INSTITUTE_ID']])) {
                $institutes_each_staff = DBGet(DBQuery('SELECT INSTITUTE_ID,START_DATE,END_DATE FROM staff_institute_relationship WHERE staff_id=\'' . $_REQUEST['staff_id'] . '\' AND syear=\'' . UserSyear() . '\' AND INSTITUTE_ID=' . $institute['INSTITUTE_ID']));
                if ($institutes_each_staff[1]['START_DATE'] == '')
                    DBQuery('UPDATE staff_institute_relationship SET START_DATE=\'0000-00-00\' WHERE staff_id=\'' . $_REQUEST['staff_id'] . '\' AND syear=\'' . UserSyear() . '\' AND INSTITUTE_ID=' . $institute['INSTITUTE_ID']);

                $institutes_each_staff = DBGet(DBQuery('SELECT INSTITUTE_ID,START_DATE,END_DATE FROM staff_institute_relationship WHERE staff_id=\'' . $_REQUEST['staff_id'] . '\' AND syear=\'' . UserSyear() . '\' AND INSTITUTE_ID=' . $institute['INSTITUTE_ID']));
                $start = $institutes_each_staff[1]['START_DATE'];

                $institutes_start_date = DBGet(DBQuery('SELECT START_DATE FROM institute_years WHERE INSTITUTE_ID=' . $institute['INSTITUTE_ID'] . ' AND SYEAR=' . UserSyear()));
                $institutes_start_date = $institutes_start_date[1]['START_DATE'];
                if ($institutes_each_staff[1]['START_DATE'] > $end_date && $end_date != '') {
                    $error = 'end_date';
                }
                
                if (!empty($institutes_each_staff) && $start != '') {
                    $update = 'false';
                    unset($sql_up);
                    
                    foreach ($_REQUEST['values']['INSTITUTES'] as $index => $value) {
                        if ($value != 'Y' && $value != 'N' && $value != '')
                            $value = 'Y';
                        if ($index == $institute['INSTITUTE_ID'] && $value == 'Y') {
                            $update = 'go';
                        }
                    }
                    
                    if ($update == 'go') {
                        if ($start_date != '' && $end_date != '' && $end_date != NULL) {
                            if (strtotime($start_date) <= strtotime($end_date))
                                $sql_up = 'UPDATE staff_institute_relationship SET START_DATE=\'' . date('Y-m-d', strtotime($start_date)) . '\', END_DATE=\'' . date('Y-m-d', strtotime($end_date)) . '\' where staff_id=\'' . $_REQUEST['staff_id'] . '\' AND syear=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . $institute['INSTITUTE_ID'] . '\'';
                            else
                                $error = 'end_date';
                        } elseif ($start_date == '' && $end_date != '') {
                            if (isset($_REQUEST['day_values']['START_DATE'][$institute['INSTITUTE_ID']]) && $_REQUEST['day_values']['START_DATE'][$institute['INSTITUTE_ID']] == '') {
                                $error1 = 'start_date';
                            } else {
                                if (strtotime($institutes_each_staff[1]['START_DATE']) <= strtotime($end_date))
                                    $sql_up = 'UPDATE staff_institute_relationship SET END_DATE=\'' . date('Y-m-d', strtotime($end_date)) . '\' where staff_id=\'' . $_REQUEST['staff_id'] . '\' AND syear=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . $institute['INSTITUTE_ID'] . '\'';
                                else
                                    $error = 'end_date';
                            }
                        } elseif ($start_date != '' && ($end_date == '' || $end_date == NULL) && strtotime($start) != strtotime($start_date)) {
                            if (strtotime($institutes_each_staff[1]['END_DATE']) >= strtotime($start_date) || $institutes_each_staff[1]['END_DATE'] == '0000-00-00' || $institutes_each_staff[1]['END_DATE'] == NULl) {
                                $cp_check = DBGet(DBQuery('SELECT * FROM course_periods WHERE SYEAR=' . UserSyear() . ' AND BEGIN_DATE <\'' . date('Y-m-d', strtotime($start_date)) . '\' AND (TEACHER_ID=' . $_REQUEST['staff_id'] . ' OR SECONDARY_TEACHER_ID=' . $_REQUEST['staff_id'] . ') AND INSTITUTE_ID=\'' . $institute['INSTITUTE_ID'] . '\' '));

                                if ($cp_check[1]['COURSE_PERIOD_ID'] == '') {
                                    $sql_up = 'UPDATE staff_institute_relationship SET START_DATE=\'' . date('Y-m-d', strtotime($start_date)) . '\' where staff_id=\'' . $_REQUEST['staff_id'] . '\' AND syear=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . $institute['INSTITUTE_ID'] . '\'';
                                } else {
                                    $error = 'cp_association';
                                }
                            } else
                                $error = 'end_date';
                        } elseif (isset($_REQUEST['day_values']['START_DATE'][$institute['INSTITUTE_ID']]) && isset($_REQUEST['day_values']['END_DATE'][$institute['INSTITUTE_ID']]) && $_REQUEST['day_values']['START_DATE'][$institute['INSTITUTE_ID']] == '' && $_REQUEST['day_values']['END_DATE'][$institute['INSTITUTE_ID']] == '') {
                            $sql_up = 'UPDATE staff_institute_relationship SET START_DATE=NULL, END_DATE=NULL where staff_id=\'' . $_REQUEST['staff_id'] . '\' AND syear=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . $institute['INSTITUTE_ID'] . '\'';
                        } elseif (isset($_REQUEST['day_values']['END_DATE'][$institute['INSTITUTE_ID']]) && $_REQUEST['day_values']['END_DATE'][$institute['INSTITUTE_ID']] == '') {
                            $sql_up = 'UPDATE staff_institute_relationship SET end_date=NULL where staff_id=\'' . $_REQUEST['staff_id'] . '\' AND syear=\'' . UserSyear() . '\' AND institute_id=\'' . $institute['INSTITUTE_ID'] . '\'';
                        }
                        
                        if (!$error && !$error1 && $sql_up != '') {
                            DBQuery($sql_up);
                        }
                    }   
                } else {

                    $sql_up = 'INSERT INTO staff_institute_relationship(staff_id,syear,institute_id';
                    $sql_up_data = 'VALUES(\'' . $_REQUEST['staff_id'] . '\',\'' . UserSyear() . '\',\'' . $institute['INSTITUTE_ID'] . '\'';

                    if ($start_date != '') {
                        $sql_up .= ',start_date';
                    }
                    if ($end_date != '') {
                        if ($_REQUEST['day_values']['START_DATE'][$institute['INSTITUTE_ID']] != '') {

                            $sql_up .= ',end_date';
                        }
                    }
                    if ($start_date != '') {
                        $sql_up_data .= ',\'' . date('Y-m-d', strtotime($start_date)) . '\'';
                    }
                    if ($end_date != '') {
                        if ($_REQUEST['day_values']['START_DATE'][$institute['INSTITUTE_ID']] != '')
                            $sql_up_data .= ',\'' . date('Y-m-d', strtotime($end_date)) . '\'';
                    }
                    $sql_up .= ')' . $sql_up_data . ')';

                    if ($start_date != '' && $end_date != '' && $end_date != NULL) {
                        if (strtotime($start_date) > strtotime($end_date))
                            $error = 'end_date';
                    }


                    if (!$error)
                        DBQuery($sql_up);
                }
            } else {
                $user_profile = DBGet(DBQuery("SELECT PROFILE_ID FROM staff WHERE STAFF_ID='" . $_REQUEST['staff_id'] . "'"));
                if ($user_profile[1]['PROFILE_ID'] != '' && is_countable($cur_institute) && count($cur_institute) > 0) {
                    $institute_selected = '';
                    if (isset($_REQUEST['values']['INSTITUTES']))
                        $institute_selected = implode(',', array_unique(array_keys($_REQUEST['values']['INSTITUTES'])));

                    $del_qry .= "DELETE FROM staff_institute_relationship WHERE STAFF_ID='" . $_REQUEST['staff_id'] . "' AND SYEAR='" . UserSyear() . "'";
                    if ($institute_selected != '')
                        $del_qry .= " AND INSTITUTE_ID NOT IN (" . $institute_selected . ")";

                    DBQuery($del_qry);

                    $del_qry = '';
                }
            }
        
        } else {
            $err = "<div class=\"alert bg-danger alert-styled-left\">" . _theInvalidDateCouldNotBeSaved . "</div>";
        }
    }
    
    if ($error == 'end_date') {
        echo '<script type=text/javascript>document.getElementById(\'sh_err\').innerHTML=\'<b><font color=red>Start date can not be greater than end date</font></b>\';</script>';

        unset($error);
    }
    
    if ($error == 'cp_association') {
        echo '<script type=text/javascript>document.getElementById(\'sh_err\').innerHTML=\'<b><font color=red>Can not change the staff start date because it has association</font></b>\';</script>';

        unset($error);
    }
    if ($error1 == 'start_date') {
        echo '<script type=text/javascript>document.getElementById(\'sh_err\').innerHTML=\'<font color=red><b>Start date can not be blank</b></font>\';</script>';
        unset($error1);
    }
}

if ($_REQUEST['month_values']['JOINING_DATE'] && $_REQUEST['day_values']['JOINING_DATE'] && $_REQUEST['year_values']['JOINING_DATE']) {
    $_REQUEST['values']['INSTITUTE']['JOINING_DATE'] = $_REQUEST['year_values']['JOINING_DATE'] . '-' . $_REQUEST['month_values']['JOINING_DATE'] . '-' . $_REQUEST['day_values']['JOINING_DATE'];
    $_REQUEST['values']['INSTITUTE']['JOINING_DATE'] = date("Y-m-d", strtotime($_REQUEST['values']['INSTITUTE']['JOINING_DATE']));
} elseif (isset($_REQUEST['month_values']['JOINING_DATE']) && isset($_REQUEST['day_values']['JOINING_DATE']) && isset($_REQUEST['year_values']['JOINING_DATE']))
    $_REQUEST['values']['INSTITUTE']['JOINING_DATE'] = '';


if ($_REQUEST['month_values']['ENDING_DATE'] && $_REQUEST['day_values']['ENDING_DATE'] && $_REQUEST['year_values']['ENDING_DATE']) {
    $_REQUEST['values']['INSTITUTE']['ENDING_DATE'] = $_REQUEST['year_values']['ENDING_DATE'] . '-' . $_REQUEST['month_values']['ENDING_DATE'] . '-' . $_REQUEST['day_values']['ENDING_DATE'];
    $_REQUEST['values']['INSTITUTE']['ENDING_DATE'] = date("Y-m-d", strtotime($_REQUEST['values']['INSTITUTE']['ENDING_DATE']));
} elseif (isset($_REQUEST['month_values']['ENDING_DATE']) && isset($_REQUEST['day_values']['ENDING_DATE']) && isset($_REQUEST['year_values']['ENDING_DATE']))
    $_REQUEST['values']['INSTITUTE']['ENDING_DATE'] = '';

$end_date = $_REQUEST['values']['INSTITUTE']['ENDING_DATE'];
unset($_REQUEST['values']['INSTITUTE']['ENDING_DATE']);
$_REQUEST['values']['INSTITUTE']['END_DATE'] = $end_date;

if ($_REQUEST['values']['INSTITUTE_IDS']) {
    $_REQUEST['values']['INSTITUTE']['INSTITUTE_ACCESS'] = ',';
    foreach ($_REQUEST['values']['INSTITUTE_IDS'] as $key => $val) {
        $_REQUEST['values']['INSTITUTE']['INSTITUTE_ACCESS'] .= $key . ",";
    }
}

$select_RET = DBGet(DBQuery("SELECT STAFF_ID FROM staff_institute_info where STAFF_ID='" . UserStaffID() . "'"));
$select = $select_RET[1]['STAFF_ID'];

//$_REQUEST['staff_institute']['PASSWORD'];
if (isset($_REQUEST['staff_institute']['PASSWORD']))
    $password = md5($_REQUEST['staff_institute']['PASSWORD']);

if ($_REQUEST['values']['INSTITUTE']['HANIIMS_PROFILE'] == '1') {
    $institute_id1 = DBGet(DBQuery("SELECT ID FROM institutes"));

    foreach ($institute_id1 as $index => $val) {
        $institutes[] = $val['ID'];
    }

    $institutes = implode(",", $institutes);
    $_REQUEST['values']['INSTITUTE']['INSTITUTE_ACCESS'] = "," . $institutes . ",";
} else {
    foreach ($_REQUEST['values']['INSTITUTES'] as $institute => $val) {
        if ($val == 'Y') {
            $institutes[] = $institute;
        }
    }
    $institutes = is_array($institutes) ? implode(",", $institutes) : $institutes;
    $_REQUEST['values']['INSTITUTE']['INSTITUTE_ACCESS'] = "," . $institutes . ",";
}

if ($select == '') {
    //    print_r($_REQUEST);exit;
    if ($_REQUEST['values']['INSTITUTE']['HANIIMS_ACCESS'] == 'Y') {
        $sql = "INSERT INTO staff_institute_info ";
        $fields = 'STAFF_ID,';
        $values = "'" . UserStaffID() . "',";
        foreach ($_REQUEST['values']['INSTITUTE'] as $column => $value) {


            if ($column == 'INSTITUTE_ACCESS' && $value == ',,')
                $value = ',' . UserInstitute() . ',';
            if ($value) {

                $fields .= $column . ',';
                //                                      if(stripos($_SERVER['SERVER_SOFTWARE'], 'linux')){
                //                                                 $values .= "'".str_replace("'","\'",$value)."',";
                //                                        }else
                $values .= "'" . singleQuoteReplace('', '', $value) . "',";
            }
            if ($column == 'HANIIMS_PROFILE' && $value == 0) {
                $fields .= $column . ',';
                //                                      if(stripos($_SERVER['SERVER_SOFTWARE'], 'linux')){
                //                                                 $values .= "'".str_replace("'","\'",$value)."',";
                //                                        }else
                $values .= "'" . singleQuoteReplace('', '', $value) . "',";
            }
        }
        $sql .= '(' . substr($fields, 0, -1) . ') values(' . substr($values, 0, -1) . ')';

        DBQuery($sql);
        $update_staff_RET = DBGet(DBQuery("SELECT  * FROM staff_institute_info where STAFF_ID='" . UserStaffID() . "'"));
        $update_staff = $update_staff_RET[1];
        $profile_name_RET = DBGet(DBQuery("SELECT PROFILE from user_profiles WHERE id=" . $update_staff['HANIIMS_PROFILE']));
        $profile = $profile_name_RET[1]['PROFILE'];
        $staff_CHECK = DBGet(DBQuery("SELECT  s.*,la.*  FROM staff s,login_authentication la where s.STAFF_ID='" . UserStaffID() . "' AND la.PROFILE_ID NOT IN (3,4) AND la.USER_ID=s.STAFF_ID"));
        $staff = $staff_CHECK[1];
        $sql_staff = "UPDATE staff SET ";

        if ($_REQUEST['staff_institute']['CURRENT_INSTITUTE_ID'])
            $sql_staff .= "PROFILE_ID='" . $update_staff['HANIIMS_PROFILE'] . "',PROFILE='" . $profile . "',CURRENT_INSTITUTE_ID='" . $_REQUEST['staff_institute']['CURRENT_INSTITUTE_ID'] . "',";
        else
            $sql_staff .= "PROFILE_ID='" . $update_staff['HANIIMS_PROFILE'] . "',PROFILE='" . $profile . "',";

        foreach ($_REQUEST['staff_institute'] as $field => $value) {
            if ($field == 'IS_DISABLE') {
                if ($value) {
                    $sql_staff .= $field . "='" . singleQuoteReplace('', '', $value) . "',";
                }
            } elseif ($field == 'PASSWORD') {
                $password = ($value);
                /*
                    $sql = DBQuery('SELECT PASSWORD FROM login_authentication  WHERE PASSWORD=\'' . $password . '\'');
                    $number = $sql->num_rows;
                */

                //code for match password in login table
                $number = 0;
                $sqlquery = DBQuery('SELECT PASSWORD FROM login_authentication');
                foreach ($sqlquery as $val) {
                    $sqloldpass = $val['PASSWORD'];
                    $login_status = VerifyHash($password, $sqloldpass);
                    if ($login_status == 1) {
                        $number = $number + 1;
                    }
                }
                //end

                if ($number == 0) {
                    if ((!$staff['USERNAME']) && (!$staff['PASSWORD'])) {
                        $sql_staff_pwd = $field . "=NULL";
                    } else {
                        $value = singleQuoteReplace('', '', ($value));
                        $new_password = GenerateNewHash($value);
                        $sql_staff_pwd = $field . "='" . $new_password . "'";
                    }
                }
            }
        }
        $sql_staff = substr($sql_staff, 0, -1) . " WHERE STAFF_ID='" . UserStaffID() . "'";
        if ($sql_staff_pwd != '') {
            $sql_staff_pwd = 'Update login_authentication SET ' . $sql_staff_pwd . ' WHERE USER_ID=' . UserStaffID();


            if (SelectedUserProfile('PROFILE_ID') != '')
                $sql_staff_pwd .= ' AND PROFILE_ID=' . SelectedUserProfile('PROFILE_ID');
        }

        if ($update_staff['HANIIMS_PROFILE'] != '') {
            $check_rec = DBGet(DBQuery('SELECT COUNT(1) AS REC_EXISTS FROM login_authentication WHERE USER_ID=' . UserStaffID() . ' AND PROFILE_ID NOT IN (3,4) '));
            if ($check_rec[1]['REC_EXISTS'] == 0)
                $sql_staff_prf = 'INSERT INTO login_authentication (PROFILE_ID,USER_ID) VALUES (\'' . $update_staff['HANIIMS_PROFILE'] . '\',\'' . UserStaffID() . '\') ';
            else
                $sql_staff_prf = 'Update login_authentication SET  PROFILE_ID=\'' . $update_staff['HANIIMS_PROFILE'] . '\' WHERE PROFILE_ID NOT IN (3,4) AND USER_ID=' . UserStaffID();
        }

        DBQuery($sql_staff);
        if ($sql_staff_pwd != '') {
            DBQuery($sql_staff_pwd);
        }
        if ($update_staff['HANIIMS_PROFILE'] != '')
            DBQuery($sql_staff_prf);
        if ((!$staff['USERNAME']) && (!$staff['PASSWORD']) && $_REQUEST['USERNAME'] != '' && $_REQUEST['PASSWORD'] != '') {

            $new_password_hash = GenerateNewHash($_REQUEST['PASSWORD']);
            $sql_staff_algo = "UPDATE login_authentication l,staff s, staff_institute_info ssi SET
                                l.username = '" . $_REQUEST['USERNAME'] . "',
                               l.password ='" . $new_password_hash . "' 
                                WHERE s.staff_id = ssi.staff_id AND l.user_id=s.staff_id AND l.profile_id NOT IN (3,4) AND s.staff_id = " . UserStaffID();

            DBQuery($sql_staff_algo);
        }
        if ($update_staff['HANIIMS_PROFILE'] == '1') {

            $institute_id3 = DBGet(DBQuery("SELECT ID FROM institutes WHERE ID NOT IN (SELECT institute_id FROM staff_institute_relationship WHERE
                                      STAFF_ID='" . $_REQUEST['staff_id'] . "' AND SYEAR='" . UserSyear() . "')"));
            foreach ($institute_id3 as $index => $val) {

                $sql_up = 'INSERT INTO staff_institute_relationship(staff_id,syear,institute_id';
                $sql_up .= ')VALUES(\'' . $_REQUEST['staff_id'] . '\',\'' . UserSyear() . '\',\'' . $val['ID'] . '\'';


                $sql_up .= ')';
            }
        }
    } elseif ($_REQUEST['values']['INSTITUTE']['HANIIMS_ACCESS'] == 'N') {
        $sql = "INSERT INTO staff_institute_info ";
        $fields = 'STAFF_ID,';
        $values = "'" . UserStaffID() . "',";
        foreach ($_REQUEST['values']['INSTITUTE'] as $column => $value) {

            //            if ($column == 'HANIIMS_PROFILE') {
            //                $fields .= $column . ',';
            //                $values .= "NULL,";
            //            } else {
            if ($value) {
                $fields .= $column . ',';
                //                                    if(stripos($_SERVER['SERVER_SOFTWARE'], 'linux'))
                //                                      {
                //                                        $values .= "'".str_replace("'","\'",$value)."',";
                //                                    }
                //                                    else
                $values .= "'" . singleQuoteReplace('', '', $value) . "',";
            }
            //            }
        }
        $sql .= '(' . substr($fields, 0, -1) . ') values(' . substr($values, 0, -1) . ')';

        DBQuery($sql);
        $update_staff_RET = DBGet(DBQuery("SELECT  * FROM staff_institute_info where STAFF_ID='" . UserStaffID() . "'"));
        $update_staff = $update_staff_RET[1];
        $staff_CHECK = DBGet(DBQuery("SELECT  *  FROM staff where STAFF_ID='" . UserStaffID() . "'"));
        $staff = $staff_CHECK[1];

        if ($update_staff['HANIIMS_PROFILE'] != '') {
            $profile_det = DBGet(DBQuery('SELECT * FROM user_profiles WHERE ID=' . $update_staff['HANIIMS_PROFILE']));

            $sql_staff = "UPDATE staff SET ";
            $sql_staff .= "PROFILE_ID='" . $update_staff['HANIIMS_PROFILE'] . "',PROFILE='" . $profile_det[1]['PROFILE'] . "' ";
        } else {
            $sql_staff = "UPDATE staff SET ";
            $sql_staff .= "PROFILE_ID='" . $update_staff['HANIIMS_PROFILE'] . "',";
        }
        $sql_staff = substr($sql_staff, 0, -1) . " WHERE STAFF_ID='" . UserStaffID() . "'";
        DBQuery($sql_staff);


        if ($update_staff['HANIIMS_PROFILE'] != '') {
            $check_rec = DBGet(DBQuery('SELECT COUNT(1) AS REC_EXISTS FROM login_authentication WHERE USER_ID=' . UserStaffID() . ' AND PROFILE_ID NOT IN (3,4) '));
            if ($check_rec[1]['REC_EXISTS'] == 0)
                $sql_staff_prf = 'INSERT INTO login_authentication (PROFILE_ID,USER_ID) VALUES (\'' . $update_staff['HANIIMS_PROFILE'] . '\',\'' . UserStaffID() . '\') ';
            else
                $sql_staff_prf = 'Update login_authentication SET  PROFILE_ID=\'' . $update_staff['HANIIMS_PROFILE'] . '\' WHERE PROFILE_ID NOT IN (3,4) AND USER_ID=' . UserStaffID();
        }


        if ($update_staff['HANIIMS_PROFILE'] != '')
            DBQuery($sql_staff_prf);

        if ($update_staff['HANIIMS_PROFILE'] == '1') {

            $institute_id3 = DBGet(DBQuery("SELECT ID FROM institutes WHERE ID NOT IN (SELECT institute_id FROM staff_institute_relationship WHERE
                                      STAFF_ID='" . $_REQUEST['staff_id'] . "' AND SYEAR='" . UserSyear() . "')"));
            foreach ($institute_id3 as $index => $val) {

                $sql_up = 'INSERT INTO staff_institute_relationship(staff_id,syear,institute_id';
                $sql_up .= ')VALUES(\'' . $_REQUEST['staff_id'] . '\',\'' . UserSyear() . '\',\'' . $val['ID'] . '\'';


                $sql_up .= ')';
            }
        }
    }
} else {
    $STAFF_INSTITUTE_COUNT = 0;
    if (isset($_REQUEST['values']['INSTITUTES']) && is_countable($_REQUEST['values']['INSTITUTES']))
        $STAFF_INSTITUTE_COUNT = count($_REQUEST['values']['INSTITUTES']);

    if ($_REQUEST['values']['INSTITUTE']['HANIIMS_ACCESS'] == 'Y') {
        if ($STAFF_INSTITUTE_COUNT == 0) {
            $sch_err = "<div class=\"alert bg-danger alert-styled-left\">" . _pleaseSelectAtleastOneInstitute . "</div>";
        }
        $sql = "UPDATE staff_institute_info  SET ";
        foreach ($_REQUEST['values']['INSTITUTE'] as $column => $value) {

            if (strtoupper($column) == 'HANIIMS_PROFILE' || strtoupper($column) == 'CATEGORY') {
                $check_prof = DBGet(DBQuery('SELECT * FROM staff_institute_info WHERE STAFF_ID=' . UserStaffID()));
                if (strtoupper($column) == 'HANIIMS_PROFILE' && $value != $check_prof[1]['HANIIMS_PROFILE']) {
                    if ($value != '') {
                        $check_staff_cp = DBGet(DBQuery('SELECT COUNT(*) AS TOTAL_ASSIGNED FROM course_periods WHERE TEACHER_ID=' . UserStaffID() . ' OR SECONDARY_TEACHER_ID=' . UserStaffID() . ''));
                    }
                    if ($check_staff_cp[1]['TOTAL_ASSIGNED'] == 0 && $value != '') {
                        $sql .= $column . '=\'' . singleQuoteReplace('', '', trim($value)) . '\',';
                    }
                    if ($check_staff_cp[1]['TOTAL_ASSIGNED'] > 0 && $value != '') {
                        $get_staff_prof = DBGet(DBQuery('SELECT PROFILE FROM user_profiles WHERE ID=' . $value));
                        if ($get_staff_prof[1]['PROFILE'] == 'teacher') {
                            DBQuery('UPDATE staff SET PROFILE_ID=' . $value . ',PROFILE=\'teacher\' WHERE STAFF_ID=' . UserStaffID());
                            DBQuery('UPDATE staff_institute_info SET HANIIMS_PROFILE=' . $value . ' WHERE STAFF_ID=' . UserStaffID());
                        } else {
                            if (strtoupper($column) == 'HANIIMS_PROFILE')
                                echo '<script type=text/javascript>document.getElementById(\'prof_err\').innerHTML=\'<font color=red><b>Cannot change the profile as this staff has one or more course periods.</b></font>\';</script>';
                        }
                    }
                }
                if (strtoupper($column) == 'CATEGORY' && $value != $check_prof[1]['CATEGORY']) {
                    if ($value != '') {
                        $check_staff_cp = DBGet(DBQuery('SELECT COUNT(*) AS TOTAL_ASSIGNED FROM course_periods WHERE TEACHER_ID=' . UserStaffID() . ' OR SECONDARY_TEACHER_ID=' . UserStaffID() . ''));
                    }
                    if ($check_staff_cp[1]['TOTAL_ASSIGNED'] == 0 && $value != '') {
                        $go = true;

                        $sql .= $column . '=\'' . singleQuoteReplace('', '', trim($value)) . '\',';
                    }
                    if ($check_staff_cp[1]['TOTAL_ASSIGNED'] > 0 && $value != '') {
                        if (strtoupper($column) == 'CATEGORY')
                            echo '<script type=text/javascript>document.getElementById(\'cat_err\').innerHTML=\'<font color=red><b>Cannot change the category as this staff has one or more course periods.</b></font>\';</script>';
                    }
                }
            } else
                $sql .= "$column='" . singleQuoteReplace('', '', $value) . "',";
        }
        $sql = substr($sql, 0, -1) . " WHERE STAFF_ID='" . UserStaffID() . "'";
        DBQuery($sql);
        $update_staff_RET = DBGet(DBQuery("SELECT  * FROM staff_institute_info where STAFF_ID='" . UserStaffID() . "'"));
        $update_staff = $update_staff_RET[1];
        $profile_name_RET = DBGet(DBQuery("SELECT PROFILE from user_profiles WHERE id=" . $update_staff['HANIIMS_PROFILE']));
        $profile = $profile_name_RET[1]['PROFILE'];
        $staff_CHECK = DBGet(DBQuery("SELECT  s.*,l.*  FROM staff s,login_authentication l where s.STAFF_ID='" . UserStaffID() . "' AND l.USER_ID=s.STAFF_ID AND l.PROFILE_ID NOT IN (3,4) "));
        $staff = $staff_CHECK[1];

        $sql_staff = "UPDATE staff SET ";

        $sql_staff .= " PROFILE_ID='" . $update_staff['HANIIMS_PROFILE'] . "',
                                       PROFILE='" . $profile . "',CURRENT_INSTITUTE_ID='" . $_REQUEST['staff_institute']['CURRENT_INSTITUTE_ID'] . "',";

        foreach ($_REQUEST['staff_institute'] as $field => $value) {
            if ($field == 'IS_DISABLE') {
                if ($value) {
                    $sql_staff .= $field . "='" . singleQuoteReplace('', '', $value) . "',";
                }
            } elseif ($field == 'PASSWORD') {
                $password = ($value);
                /*$sql = DBQuery('SELECT PASSWORD FROM login_authentication WHERE PASSWORD=\'' . $password . '\'');
                $number = $sql->num_rows;*/

                //code for match password in login table
                $number = 0;
                $sqlquery = DBQuery('SELECT PASSWORD FROM login_authentication');
                foreach ($sqlquery as $val) {
                    $sqloldpass = $val['PASSWORD'];
                    $login_status = VerifyHash($password, $sqloldpass);
                    if ($login_status == 1) {
                        $number = $number + 1;
                    }
                }
                //end

                if ($number == 0) {
                    if ((!$staff['USERNAME']) && (!$staff['PASSWORD'])) {
                        $sql_staff_pwd = $field . "=NULL";
                    } else {
                        $value = singleQuoteReplace('', '', ($value));
                        $new_password = GenerateNewHash($value);
                        $sql_staff_pwd = $field . "='" . $new_password . "'";
                    }
                }
            }
        }
        $sql_staff = substr($sql_staff, 0, -1) . " WHERE STAFF_ID='" . UserStaffID() . "'";
        if ($sql_staff_pwd != '')
            $sql_staff_pwd = 'Update login_authentication SET ' . $sql_staff_pwd . ' WHERE USER_ID=' . UserStaffID() . ' AND PROFILE_ID=' . SelectedUserProfile('PROFILE_ID');

        if ($update_staff['HANIIMS_PROFILE'] != '') {
            $check_rec = DBGet(DBQuery('SELECT COUNT(1) AS REC_EXISTS FROM login_authentication WHERE USER_ID=' . UserStaffID() . ' AND PROFILE_ID NOT IN (3,4) '));
            if ($check_rec[1]['REC_EXISTS'] == 0)
                $sql_staff_prf = 'INSERT INTO login_authentication (PROFILE_ID,USER_ID) VALUES (\'' . $update_staff['HANIIMS_PROFILE'] . '\',\'' . UserStaffID() . '\') ';
            else
                $sql_staff_prf = 'Update login_authentication SET  PROFILE_ID=\'' . $update_staff['HANIIMS_PROFILE'] . '\' WHERE PROFILE_ID NOT IN (3,4) AND USER_ID=' . UserStaffID();
        }

        DBQuery($sql_staff);
        if ($sql_staff_pwd != '')
            DBQuery($sql_staff_pwd);

        if ($update_staff['HANIIMS_PROFILE'] != '')
            DBQuery($sql_staff_prf);

        if ($_REQUEST['USERNAME'] != '') {
            $usernameExists = DBGet(DBQuery('SELECT * FROM login_authentication WHERE USERNAME=\'' . $_REQUEST['USERNAME'] . '\''));
            if ($staff_prof_id == '') {
                $staff_info_sql = "SELECT PROFILE_ID FROM staff WHERE STAFF_ID=" . $_REQUEST['staff_id'];
                $staff_info = DBGet(DBQuery($staff_info_sql));
                $staff_prof_id = $staff_info[1]['PROFILE_ID'];
            }
            $sql_staff_username = "UPDATE login_authentication l,staff s, staff_institute_info ssi SET
                                l.username = '" . $_REQUEST['USERNAME'] . "'
                                WHERE s.staff_id = ssi.staff_id AND l.user_id=s.staff_id AND l.profile_id NOT IN (3,4) AND s.staff_id = " . UserStaffID();
            if(count($usernameExists) == 0){
                DBQuery($sql_staff_username);
            } else {
                if($usernameExists[1]['USER_ID'] != $_REQUEST['staff_id'] || $usernameExists[1]['PROFILE_ID'] != $staff_prof_id){
                    echo '<font color=red><b>Username already exists.</b></font>';
                }
            }
        }
        if ((!$staff['USERNAME']) && (!$staff['PASSWORD']) && $_REQUEST['USERNAME'] != '' && $_REQUEST['PASSWORD'] != '') {

            $new_password_hash = GenerateNewHash($_REQUEST['PASSWORD']);
            $sql_staff_algo = "UPDATE login_authentication l,staff s, staff_institute_info ssi SET
                                l.username = '" . $_REQUEST['USERNAME'] . "',
                               l.password ='" . $new_password_hash . "' 
                                WHERE s.staff_id = ssi.staff_id AND l.user_id=s.staff_id AND l.profile_id NOT IN (3,4) AND s.staff_id = " . UserStaffID();



            DBQuery($sql_staff_algo);
        }
        if ($update_staff['HANIIMS_PROFILE'] == '1') {

            $institute_id3 = DBGet(DBQuery("SELECT ID FROM institutes WHERE ID NOT IN (SELECT institute_id FROM staff_institute_relationship WHERE
                                      STAFF_ID='" . $_REQUEST['staff_id'] . "' AND SYEAR='" . UserSyear() . "')"));
            foreach ($institute_id3 as $index => $val) {

                $sql_up = 'INSERT INTO staff_institute_relationship(staff_id,syear,institute_id';
                $sql_up .= ')VALUES(\'' . $_REQUEST['staff_id'] . '\',\'' . UserSyear() . '\',\'' . $val['ID'] . '\'';


                $sql_up .= ')';
            }
        }
    } elseif ($_REQUEST['values']['INSTITUTE']['HANIIMS_ACCESS'] == 'N') {
        if ($STAFF_INSTITUTE_COUNT == 0) {
            $sch_err = "<div class=\"alert bg-danger alert-styled-left\">" . _pleaseSelectAtleastOneInstitute . "</div>";
        }

        $sql = "UPDATE staff_institute_info  SET ";

        foreach ($_REQUEST['values']['INSTITUTE'] as $column => $value) {
            //                                                 if(stripos($_SERVER['SERVER_SOFTWARE'], 'linux')){
            //                                                        $sql .= "$column='".str_replace("'","\'",str_replace("`","''",$value))."',";
            //                                                        }else
            $sql .= "$column='" . singleQuoteReplace('', '', $value) . "',";
        }
        $sql = substr($sql, 0, -1) . " WHERE STAFF_ID='" . UserStaffID() . "'";
        DBQuery($sql);

        if (isset($_REQUEST['values']['INSTITUTE']['HANIIMS_PROFILE']) && $_REQUEST['values']['INSTITUTE']['HANIIMS_PROFILE'] != '') {

            $update_staff_RET = DBGet(DBQuery("SELECT  * FROM staff_institute_info where STAFF_ID='" . UserStaffID() . "'"));
            $update_staff = $update_staff_RET[1];
            $staff_CHECK = DBGet(DBQuery("SELECT  *  FROM staff where STAFF_ID='" . UserStaffID() . "'"));
            $staff = $staff_CHECK[1];

            if ($update_staff['HANIIMS_PROFILE'] != '') {
                $profile_det = DBGet(DBQuery('SELECT * FROM user_profiles WHERE ID=' . $update_staff['HANIIMS_PROFILE']));

                $sql_staff = "UPDATE staff SET ";
                $sql_staff .= "PROFILE_ID='" . $update_staff['HANIIMS_PROFILE'] . "',PROFILE='" . $profile_det[1]['PROFILE'] . "' ";
            } else {
                $sql_staff = "UPDATE staff SET ";
                $sql_staff .= "PROFILE_ID='" . $update_staff['HANIIMS_PROFILE'] . "',";
            }
            $sql_staff = substr($sql_staff, 0, -1) . " WHERE STAFF_ID='" . UserStaffID() . "'";
            DBQuery($sql_staff);


            if ($update_staff['HANIIMS_PROFILE'] != '') {
                $check_rec = DBGet(DBQuery('SELECT COUNT(1) AS REC_EXISTS FROM login_authentication WHERE USER_ID=' . UserStaffID() . ' AND PROFILE_ID NOT IN (3,4) '));
                if ($check_rec[1]['REC_EXISTS'] == 0)
                    $sql_staff_prf = 'INSERT INTO login_authentication (PROFILE_ID,USER_ID) VALUES (\'' . $update_staff['HANIIMS_PROFILE'] . '\',\'' . UserStaffID() . '\') ';
                else
                    $sql_staff_prf = 'Update login_authentication SET  PROFILE_ID=\'' . $update_staff['HANIIMS_PROFILE'] . '\' WHERE PROFILE_ID NOT IN (3,4) AND USER_ID=' . UserStaffID();
            }


            if ($update_staff['HANIIMS_PROFILE'] != '')
                DBQuery($sql_staff_prf);

            if ($update_staff['HANIIMS_PROFILE'] == '1') {

                $institute_id3 = DBGet(DBQuery("SELECT ID FROM institutes WHERE ID NOT IN (SELECT institute_id FROM staff_institute_relationship WHERE
                                      STAFF_ID='" . $_REQUEST['staff_id'] . "' AND SYEAR='" . UserSyear() . "')"));
                foreach ($institute_id3 as $index => $val) {

                    $sql_up = 'INSERT INTO staff_institute_relationship(staff_id,syear,institute_id';
                    $sql_up .= ')VALUES(\'' . $_REQUEST['staff_id'] . '\',\'' . UserSyear() . '\',\'' . $val['ID'] . '\'';


                    $sql_up .= ')';

                    DBQuery($sql_up);
                }
            }
        }

        unset($_REQUEST['values']['INSTITUTE']['INSTITUTE_ACCESS']);
        unset($_REQUEST['values']['INSTITUTE']['HANIIMS_PROFILE']);
    }
}
if ($sch_err != '') {
    echo $sch_err;
    unset($sch_err);
}
if (!$_REQUEST['modfunc']) {
    # FIX: If in any case the profile_id from the `staff` table is missing,
    # but present in the `staff_institute_info` table.
    $get_staff_profile_info = DBGet(DBQuery('SELECT st.STAFF_ID, st.PROFILE, st.PROFILE_ID, sci.HANIIMS_PROFILE FROM staff st LEFT JOIN staff_institute_info sci ON st.STAFF_ID = sci.STAFF_ID WHERE st.STAFF_ID = ' . UserStaffID()));
    if (!empty($get_staff_profile_info)) {
        if ($get_staff_profile_info[1]['PROFILE_ID'] == '' && trim($get_staff_profile_info[1]['HANIIMS_PROFILE']) != '') {
            $potential_profile = substr($get_staff_profile_info[1]['HANIIMS_PROFILE'], 0, 1);
            DBQuery('UPDATE staff SET PROFILE_ID = \'' . $potential_profile . '\' WHERE STAFF_ID = ' . UserStaffID());
        }
    }

    $this_institute_RET = DBGet(DBQuery("SELECT * FROM staff_institute_info   WHERE   STAFF_ID=" . UserStaffID()));
    $this_institute = $this_institute_RET[1];

    $this_institute_RET_mod = DBGet(DBQuery("SELECT s.*,l.* FROM staff s,login_authentication l  WHERE l.USER_ID=s.STAFF_ID AND l.PROFILE_ID NOT IN (3,4) AND s.STAFF_ID=" . UserStaffID()));

    $this_institute_mod = $this_institute_RET_mod[1];


    if (User('PROFILE') == 'admin')
        $profiles_options = DBGet(DBQuery("SELECT PROFILE ,TITLE, ID FROM user_profiles WHERE ID <> 3 AND PROFILE <> 'parent' AND ID<>0 ORDER BY ID"));

    $prof_check = DBGet(DBQuery('SELECT PROFILE_ID FROM staff WHERE STAFF_ID=' . UserStaffID()));
    if (User('PROFILE_ID') == 0 && $prof_check[1]['PROFILE_ID'] == 0)
        $profiles_options = DBGet(DBQuery("SELECT PROFILE ,TITLE, ID FROM user_profiles WHERE ID <> 3  AND PROFILE <> 'parent' ORDER BY ID"));
    if (User('PROFILE_ID') == 0 && $prof_check[1]['PROFILE_ID'] != 0)
        $profiles_options = DBGet(DBQuery("SELECT PROFILE ,TITLE, ID FROM user_profiles WHERE ID <> 0  AND PROFILE <> 'parent' AND ID<>'4' ORDER BY ID"));

    if (User('PROFILE_ID') == 2)
        $profiles_options = DBGet(DBQuery("SELECT PROFILE ,TITLE, ID FROM user_profiles WHERE  PROFILE ='teacher' ORDER BY ID"));
    $i = 1;
    foreach ($profiles_options as $options) {
        if ($options['PROFILE'] != 'student')
            $option[$options['ID']] = $options['TITLE'];
        $i++;
    }
    if (is_countable($option) && count($option) == 0 && User('PROFILE') != 'admin') {
        $profiles_options = DBGet(DBQuery('SELECT TITLE, ID FROM user_profiles WHERE ID=' . User('PROFILE_ID')));
        $option[$profiles_options[1]['ID']] = $profiles_options[1]['TITLE'];
    }
    $_REQUEST['category_id'] = 3;
    $_REQUEST['custom'] = 'staff';
    include('modules/users/includes/OtherInfoInc.inc.php');


    $style = '';


    if (isset($_REQUEST['institute_info_id'])) {
        $get_end_date = DBGet(DBQuery('SELECT MAX(END_DATE) AS END_DATE FROM institute_years WHERE  SYEAR=' . UserSyear()));
        $get_end_date = $get_end_date[1]['END_DATE'];


        echo "<INPUT type=hidden name=institute_info_id value=$_REQUEST[institute_info_id]>";

        if ($_REQUEST['institute_info_id'] != '0' && $_REQUEST['institute_info_id'] !== 'old') {

            echo '<h5 class="text-primary">' . _officialInformation . '</h5>';

            echo '<div class="row">';
            echo '<div class="col-md-6">';
            if (User('PROFILE_ID') == 0 && $prof_check[1]['PROFILE_ID'] == 0 && User('STAFF_ID') == UserStaffID())
                echo '<div class="form-group"><label class="control-label text-right col-lg-4">' . _category . ' <span class=text-danger>*</span></label><div class="col-lg-8">' . SelectInput($this_institute['CATEGORY'], 'values[INSTITUTE][CATEGORY]', '', array(
                    'Super Administrator' => _superAdministrator,
                    'Administrator' => _administrator,
                    'Teacher' => _teacher,
                    'Non Teaching Staff' => _nonTeachingStaff,
                    'Custodian' => _custodian,
                    'Principal' => _principal,
                    'Clerk' => _clerk,
                ), false) . '</div></div>';
            else
                echo '<div class="form-group"><label class="control-label text-right col-lg-4">' . _category . ' <span class=text-danger>*</span></label><div class="col-lg-8">' . SelectInput($this_institute['CATEGORY'], 'values[INSTITUTE][CATEGORY]', '', array(
                    'Administrator' => _administrator,
                    'Teacher' => _teacher,
                    'Non Teaching Staff' => _nonTeachingStaff,
                    'Custodian' => _custodian,
                    'Principal' => _principal,
                    'Clerk' => _clerk,
                ), false) . '</div></div>';
            echo '</div><div class="col-md-6">';
            echo '<div class="form-group">' . TextInput($this_institute['JOB_TITLE'], 'values[INSTITUTE][JOB_TITLE]', _jobTitle, 'class=cell_medium') . '</div>';
            echo '</div>'; //.col-md-6
            echo '</div>'; //.row

            echo '<div class="row">';
            echo '<div class="col-md-6">';
            echo '<div class="form-group"><label class="control-label text-right col-lg-4">' . _joiningDate . ' <span class=text-danger>*</span></label><div class="col-lg-8">' . DateInputAY(isset($this_institute['JOINING_DATE']) && $this_institute['JOINING_DATE'] != "" ? $this_institute['JOINING_DATE'] : "", 'values[JOINING_DATE]', 1, '') . '</div></div>';
            echo '<input type=hidden id=end_date_institute value="' . $get_end_date . '" >';
            echo '</div><div class="col-md-6">';
            echo '<div class="form-group"><label class="control-label text-right col-lg-4">' . _endDate . '</label><div class="col-lg-8">' . DateInputAY($this_institute['END_DATE'] != "" ? $this_institute['END_DATE'] : "", 'values[ENDING_DATE]', 2, '') . '</div></div>';
            echo "<INPUT type=hidden name=values[INSTITUTE][HOME_INSTITUTE] value=" . UserInstitute() . ">";
            echo '</div>'; //.col-md-6
            echo '</div>'; //.row

            $staff_profile = DBGet(DBQuery("SELECT PROFILE_ID FROM staff WHERE STAFF_ID='" . UserStaffID() . "'"));
            echo '<div class="row">';
            echo '<div class="col-lg-6">';
            echo '<div class="form-group"><label class="control-label text-right col-lg-4">' . _profile . '</label><div class="col-lg-8">' . SelectInput($this_institute['HANIIMS_PROFILE'], 'values[INSTITUTE][HANIIMS_PROFILE]', '', $option, false, 'id=values[INSTITUTE][HANIIMS_PROFILE]') . '</div></div>';
            echo '</div>'; //.col-lg-6            
            echo '</div>'; //.row

            echo '';

            if ($this_institute_mod['USERNAME'] && (!$this_institute['HANIIMS_ACCESS'] == 'Y')) {
                echo '<div class="row">';
                echo '<div class="col-md-12">';
                echo '<h5 class="text-primary inline-block">' . _haniAccessInformation . '</h5><div class="inline-block p-l-15"><label class="radio-inline p-t-0"><input type="radio" id="noaccs" name="values[INSTITUTE][HANIIMS_ACCESS]" value="N" onClick="hidediv();">' . _noAccess . '</label><label class="radio-inline p-t-0"><input type="radio" id="r4" name="values[INSTITUTE][HANIIMS_ACCESS]" value="Y" onClick="showdiv();" checked>' . _access . '</label></div>';
                echo '</div>'; //.col-md-6
                echo '</div>'; //.row
                echo '<div id="hideShow" class="mt-15">';
            } elseif ($this_institute_mod['USERNAME'] && $this_institute_mod['PASSWORD'] && $this_institute['HANIIMS_ACCESS']) {
                if ($this_institute['HANIIMS_ACCESS'] == 'N') {
                    echo '<div class="row">';
                    echo '<div class="col-md-12">';
                    echo '<h5 class="text-primary inline-block">' . _haniAccessInformation . '</h5><div class="inline-block p-l-15"><label class="radio-inline p-t-0"><input type="radio" id="noaccs" name="values[INSTITUTE][HANIIMS_ACCESS]" value="N" checked>' . _noAccess . '</label><label class="radio-inline p-t-0"><input type="radio" id="r4" name="values[INSTITUTE][HANIIMS_ACCESS]" value="Y" >' . _access . '</label></div>';
                    echo '</div>'; //.col-md-6
                    echo '</div>'; //.row
                } elseif ($this_institute['HANIIMS_ACCESS'] == 'Y') {
                    echo '<div class="row">';
                    echo '<div class="col-md-12">';
                    echo '<h5 class="text-primary inline-block">' . _haniAccessInformation . '</h5><div class="inline-block p-l-15"><label class="radio-inline p-t-0"><input type="radio" id="noaccs" name="values[INSTITUTE][HANIIMS_ACCESS]" value="N">' . _noAccess . '</label><label class="radio-inline p-t-0"><input type="radio" id="r4" name="values[INSTITUTE][HANIIMS_ACCESS]" value="Y"  checked>&nbsp;' . _access . '</label></div>';
                    echo '</div>'; //.col-md-6
                    echo '</div>'; //.row
                }
                echo '<div id="hideShow" class="mt-15">';
            } elseif (!$this_institute_mod['USERNAME'] || $this_institute['HANIIMS_ACCESS'] == 'N') {
                echo '<div class="row">';
                echo '<div class="col-md-12">';
                echo '<h5 class="text-primary inline-block">' . _haniAccessInformation . '</h5><div class="inline-block p-l-15"><label class="radio-inline p-t-0"><input type="radio" id="noaccs" name="values[INSTITUTE][HANIIMS_ACCESS]" value="N" onClick="hidediv();" checked>' . _noAccess . '</label><label class="radio-inline p-t-0"><input type="radio" id="r4" name="values[INSTITUTE][HANIIMS_ACCESS]" value="Y" onClick="showdiv();">&nbsp;' . _access . '</label></div>';
                echo '</div>'; //.col-md-6
                echo '</div>'; //.row
                echo '<div id="hideShow" class="mt-15" style="display:none">';
            }


            //            $staff_profile = DBGet(DBQuery("SELECT PROFILE_ID FROM staff WHERE STAFF_ID='" . UserStaffID() . "'"));
            //            echo '<div class="row">';
            //            echo '<div class="col-lg-6">';
            //            echo '<div class="form-group"><label class="control-label text-right col-lg-4">'._profile.'</label><div class="col-lg-8">' . SelectInput($this_institute['HANIIMS_PROFILE'], 'values[INSTITUTE][HANIIMS_PROFILE]', '', $option, false, 'id=values[INSTITUTE][HANIIMS_PROFILE]') . '</div></div>';
            //            echo '</div>'; //.col-lg-6            
            //            echo '</div>'; //.row

            echo '<div class="row">';
            echo '<div class="col-lg-6">';
            echo '<div class="form-group"><label class="control-label text-right col-lg-4">' . _username . ' <span class=text-danger>*</span></label><div class="col-lg-8">';
            if (!$this_institute_mod['USERNAME']) {
                echo TextInput('', 'USERNAME', '', 'id=USERNAME size=20 maxlength=50 onkeyup="usercheck_init_staff(this, \'' . $this_institute_mod['STAFF_ID'] . '\', \'' . $this_institute_mod['PROFILE_ID'] . '\')" onblur="usercheck_init_staff(this, \'' . $this_institute_mod['STAFF_ID'] . '\', \'' . $this_institute_mod['PROFILE_ID'] . '\')"');
                echo '<span id="ajax_output_st"></span><input type=hidden id=usr_err_check value=0>';
            } else {
                echo '<input id="USERNAME" type="text" name="USERNAME" value="'.$this_institute_mod['USERNAME'].'" onkeyup="usercheck_init_staff(this, ' . $this_institute_mod['STAFF_ID'] . ', ' . $this_institute_mod['PROFILE_ID'] . ')" onblur="usercheck_init_staff(this, ' . $this_institute_mod['STAFF_ID'] . ', ' . $this_institute_mod['PROFILE_ID'] . ')" class="form-control">';
                echo '<span id="ajax_output_st"></span><input type=hidden id=usr_err_check value=0>';
            }
            echo '</div></div>';
            echo '</div>'; //.col-lg-6
            echo '<div class="col-lg-6">';
            echo '<div class="form-group"><label class="control-label text-right col-lg-4">' . _password . ' <span class=text-danger>*</span></label><div class="col-lg-8">';
            if (!$this_institute_mod['PASSWORD']) {
                echo TextInputModHidden('', 'PASSWORD', '', 'size=20 maxlength=100 AUTOCOMPLETE = off onblur=passwordStrength(this.value);validate_password_staff(this.value);');

                echo '<span id="ajax_output_st"></span>';
            } else {
                echo TextInputModHidden(array($this_institute_mod['PASSWORD'], str_repeat('*', strlen($this_institute_mod['PASSWORD']))), 'staff_institute[PASSWORD]', '', 'size=20 maxlength=100 AUTOCOMPLETE = off onkeyup=passwordStrength(this.value);validate_password(this.value);');
            }
            echo "<span id='passwordStrength'></span></div></div>";
            echo '</div>'; //.col-lg-6
            echo '</div>'; //.row

            if ($this_institute_mod['USERNAME'] && $this_institute_mod['USERNAME'] != '') {
                echo '<input id="staff_username_flag" type="hidden" value="1">';
            } else {
                echo '<input id="staff_username_flag" type="hidden" value="0">';
            }

            echo '<div class="row">';
            echo '<div class="col-md-6">';
            echo '<div class="form-group"><label class="control-label text-right col-lg-4">' . _disableUser . '</label><div class="col-lg-8">';
            if ($this_institute_mod['IS_DISABLE'] == 'Y')
                $dis_val = 'Y';
            else
                $dis_val = 'N';
            echo CheckboxInput_No($dis_val, 'staff_institute[IS_DISABLE]', '', 'CHECKED', $new, '<i class="icon-checkbox-checked"></i>', '<i class="icon-checkbox-unchecked"></i>');
            echo '</div></div>';
            echo '</div>'; //.col-md-6
            echo '</div>'; //.row

            echo '</div>'; //#hideShow

            if ($this_institute['INSTITUTE_ACCESS']) {

                $pieces = explode(",", $this_institute['INSTITUTE_ACCESS']);
            }


            $profile_return = DBGet(DBQuery("SELECT PROFILE_ID FROM staff WHERE STAFF_ID='" . UserStaffID() . "'"));
            if ($profile_return[1]['PROFILE_ID'] != '') {
                echo '<h5 class="text-primary">' . _instituteInformation . '</h5>';
                echo '<hr class="m-b-0" />';
                $functions = array('START_DATE' => '_makeStartInputDate', 'PROFILE' => '_makeUserProfile', 'END_DATE' => '_makeEndInputDate','INSTITUTE_ID' => '_makeCheckBoxInput_gen', 'ID' => '_makeStatus');

                $sql = 'SELECT s.ID,ssr.INSTITUTE_ID as SCH_ID,ssr.INSTITUTE_ID,s.TITLE,ssr.START_DATE,ssr.END_DATE,st.PROFILE FROM institutes s,staff st INNER JOIN staff_institute_relationship ssr USING(staff_id) WHERE s.id=ssr.institute_id  AND st.staff_id=\'' . User('STAFF_ID') . '\' AND ssr.SYEAR=\'' . UserSyear() . '\' GROUP BY ssr.INSTITUTE_ID';
                $institute_admin = DBGet(DBQuery($sql), $functions);
                //print_r($institute_admin);
                //                $columns = array('INSTITUTE_ID' => '<a><INPUT type=checkbox value=Y name=controller onclick="checkAll(this.form,this.form.controller.checked,\'unused\');" /></a>', 'TITLE' => 'Institute', 'PROFILE' => 'Profile', 'START_DATE' => 'Start Date', 'END_DATE' => 'Drop Date', 'ID' => 'Status');

                $columns = array(
                    'INSTITUTE_ID' => '<a><INPUT type=checkbox value=Y name=controller onclick="checkAllDtMod(this,\'values[INSTITUTES]\');" /></a>',
                    'TITLE' => _institute,
                    'PROFILE' => _profile,
                    'START_DATE' => _startDate,
                    'END_DATE' => _dropDate,
                    'ID' => _status,
                );
                
                $institute_ids_for_hidden = array();
                echo '<div id="hidden_checkboxes">';
                foreach ($institute_admin as $sai => $sad) {
                    //                    echo '<pre>';
                    //                    print_r($sad);
                    $institute_ids_for_hidden[] = $sad['SCH_ID'];
                    if (strip_tags($sad['ID']) == 'Active')
                        echo '<input type=hidden name="values[INSTITUTES][' . $sad['SCH_ID'] . ']" value="Y" data-checkbox-hidden-id="' . $sad['SCH_ID'] . '" />';
                }
                echo '</div>';
                $institute_ids_for_hidden = implode(',', $institute_ids_for_hidden);
                echo '<input type=hidden id=institute_ids_hidden value="' . $institute_ids_for_hidden . '" />';

                $check_all_arr = array();
                foreach ($institute_admin as $xy) {

                    $check_all_arr[] = $xy['SCH_ID'];
                }
                $check_all_stu_list = implode(',', $check_all_arr);
                echo '<input type=hidden name=res_length id=res_length value=\'' . count($check_all_arr) . '\'>';
                echo '<input type=hidden name=res_len id=res_len value=\'' . $check_all_stu_list . '\'>';

                ListOutputStaffPrintInstituteInfo($institute_admin, $columns, _instituteRecord, _instituteRecords, array(), array(), array('search' => false, 'sort' => false));
            }
        }
    } else
        echo '';
    $separator = '<HR>';
}

function CheckboxInput_No($value, $name, $title = '', $checked = '', $new = false, $yes = 'yes', $no = 'no', $div = true, $extra = '')
{
    // $checked has been deprecated -- it remains only as a placeholder
    if (Preferences('HIDDEN') != 'Y')
        $div = false;

    if ($div == false || $new == true) {
        if ($value && $value != 'N')
            $checked = 'CHECKED';
        else
            $checked = '';
    }

    if (AllowEdit() && !$_REQUEST['HaniIMS_PDF']) {
        if ($new || $div == false) {
            return "<INPUT type=checkbox name=$name value=Y  $extra>" . ($title != '' ? '<BR><small>' . (strpos(strtolower($title), '<font ') === false ? '<FONT color=' . Preferences('TITLES') . '>' : '') . $title . (strpos(strtolower($title), '<font ') === false ? '</FONT>' : '') . '</small>' : '');
        } else {
            if ($value == '' || $value == 'N')
                return "<DIV id='div$name' class=\"form-control\" readonly=\"readonly\"><INPUT type=checkbox name=$name " . (($value == 'Y') ? 'checked' : '') . " value=Y " . str_replace('"', '\"', $extra) . "></DIV>";
            else
                return "<DIV id='div$name' class=\"form-control\" readonly=\"readonly\"><div onclick='javascript:addHTML(\"<INPUT type=hidden name=$name value=\\\"N\\\"><INPUT type=checkbox name=$name " . (($value == 'Y') ? 'checked' : '') . " value=Y " . str_replace('"', '\"', $extra) . ">" . ($title != '' ? '<BR><small>' . str_replace("'", '&#39;', (strpos(strtolower($title), '<font ') === false ? '<FONT color=' . Preferences('TITLES') . '>' : '') . $title . (strpos(strtolower($title), '<font ') === false ? '</FONT>' : '')) . '</small>' : '') . "\",\"div$name\",true)'>" . (($value != 'N') ? $yes : $no) . ($title != '' ? "<BR><small>" . str_replace("'", '&#39;', (strpos(strtolower($title), '<font ') === false ? '<FONT color=' . Preferences('TITLES') . '>' : '') . $title . (strpos(strtolower($title), '<font ') === false ? '</FONT>' : '')) . "</small>" : '') . "</div></DIV>";
        }
    } else
        return (($value != 'N') ? $yes : $no) . ($title != '' ? '<BR><small>' . (strpos(strtolower($title), '<font ') === false ? '<FONT color=' . Preferences('TITLES') . '>' : '') . $title . (strpos(strtolower($title), '<font ') === false ? '</FONT>' : '') . '</small>' : '');
}

function _makeStartInputDate($value, $column)
{
    global $THIS_RET;
    
    if ($_REQUEST['staff_id'] == 'new') {
        $date_value = '';
    } else {
        $sql = 'SELECT ssr.START_DATE FROM staff s,staff_institute_relationship ssr  WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.INSTITUTE_ID=' . $THIS_RET['INSTITUTE_ID'] . ' AND ssr.STAFF_ID=' . $_SESSION['staff_selected'] . ' AND ssr.SYEAR=' . UserSyear();
        $user_exist_institute = DBGet(DBQuery($sql));
        if ($user_exist_institute[1]['START_DATE'] == '0000-00-00' || $user_exist_institute[1]['START_DATE'] == '')
            $date_value = '';
        else
            $date_value = $user_exist_institute[1]['START_DATE'];
    }
    
    return '<TABLE class=LO_field><TR>' . '<TD nowrap="nowrap">' . DateInputAY($date_value != '' ? $date_value : $date_value, 'values[START_DATE][' . $THIS_RET['ID'] . ']', '1' . $THIS_RET['ID']) . '</TD></TR></TABLE>';
}

function _makeUserProfile($value, $column)
{
    global $THIS_RET;
    if ($_REQUEST['staff_id'] == 'new') {
        $profile_value = '';
    } else {
        $sql = 'SELECT up.TITLE FROM staff s,staff_institute_relationship ssr,user_profiles up  WHERE ssr.STAFF_ID=s.STAFF_ID AND up.ID=s.PROFILE_ID AND ssr.INSTITUTE_ID=' . $THIS_RET['INSTITUTE_ID'] . ' AND ssr.STAFF_ID=' . $_SESSION['staff_selected'] . ' AND ssr.SYEAR=   (SELECT MAX(SYEAR) FROM  staff_institute_relationship WHERE INSTITUTE_ID=' . $THIS_RET['INSTITUTE_ID'] . ' AND STAFF_ID=' . $_SESSION['staff_selected'] . ')';
        $user_profile = DBGet(DBQuery($sql));
        $profile_value = $user_profile[1]['TITLE'];
    }
    return '<TABLE class=LO_field><TR>' . '<TD>' . $profile_value . '</TD></TR></TABLE>';
}

function _makeEndInputDate($value, $column)
{
    global $THIS_RET;
    if ($_REQUEST['staff_id'] == 'new') {
        $date_value = '';
    } else {

        $sql = 'SELECT ssr.END_DATE FROM staff s,staff_institute_relationship ssr  WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.INSTITUTE_ID=' . $THIS_RET['INSTITUTE_ID'] . ' AND ssr.STAFF_ID=' . $_SESSION['staff_selected'] . ' AND ssr.SYEAR=   (SELECT MAX(SYEAR) FROM  staff_institute_relationship WHERE INSTITUTE_ID=' . $THIS_RET['INSTITUTE_ID'] . ' AND STAFF_ID=' . $_SESSION['staff_selected'] . ')';
        $user_exist_institute = DBGet(DBQuery($sql));
        if ($user_exist_institute[1]['END_DATE'] == '0000-00-00' || $user_exist_institute[1]['END_DATE'] == '')
            $date_value = '';
        else
            $date_value = $user_exist_institute[1]['END_DATE'];
    }
    if (SelectedUserProfile('PROFILE_ID') == 0)
        return '<TABLE class=LO_field><TR>' . '<TD nowrap="nowrap">' . ProperDateAY($date_value) . '</TD></TR></TABLE>';
    else
        return '<TABLE class=LO_field><TR>' . '<TD nowrap="nowrap">' . DateInputAY($date_value, 'values[END_DATE][' . $THIS_RET['ID'] . ']', '2' . $THIS_RET['ID']) . '</TD></TR></TABLE>';
}

function _makeCheckBoxInput_gen($value, $column)
{
    global $THIS_RET;

    $_SESSION['staff_institute_chkbox_id']++;
    $staff_institute_chkbox_id = $_SESSION['staff_institute_chkbox_id'];
    if ($_REQUEST['staff_id'] == 'new') {
        return '<TABLE class=LO_field><TR>' . '<TD>' . "<input name=unused[$THIS_RET[ID]]  type='checkbox' id=$staff_institute_chkbox_id onClick='setHiddenCheckbox(\"values[INSTITUTES][$THIS_RET[ID]]\",this,$THIS_RET[ID]);' />" . '</TD></TR></TABLE>';
    } else {
        $sql = '';
        $staff_infor_qr = DBGet(DBQuery('select * from staff_institute_relationship where STAFF_ID=\'' . $_SESSION['staff_selected'] . '\' AND SYEAR=' . UserSyear()));
        if (count($staff_infor_qr) > 0) {
            $i = 0;
            foreach ($staff_infor_qr as $skey => $sval) {
                $sch_li[$i] = $sval['INSTITUTE_ID'];
                $i++;
            }
        }
        //$sch_li = explode(',', trim($staff_infor_qr[1]['INSTITUTE_ACCESS']));
        $dates = DBGet(DBQuery("SELECT ssr.START_DATE,ssr.END_DATE FROM staff s,staff_institute_relationship ssr WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.INSTITUTE_ID='" . $THIS_RET['INSTITUTE_ID'] . "' AND ssr.STAFF_ID='" . $_SESSION['staff_selected'] . "' AND ssr.SYEAR=(SELECT MAX(SYEAR) FROM  staff_institute_relationship WHERE INSTITUTE_ID='" . $THIS_RET['INSTITUTE_ID'] . "' AND STAFF_ID='" . $_SESSION['staff_selected'] . "')"));
        if ($dates[1]['START_DATE'] == '0000-00-00' && $dates[1]['END_DATE'] == '0000-00-00' && in_array($THIS_RET['INSTITUTE_ID'], $sch_li)) {
            $sql = 'SELECT INSTITUTE_ID FROM staff s,staff_institute_relationship ssr WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.INSTITUTE_ID=' . $THIS_RET['INSTITUTE_ID'] . ' AND ssr.STAFF_ID=' . $_SESSION['staff_selected'] . ' AND ssr.SYEAR=(SELECT MAX(SYEAR) FROM  staff_institute_relationship WHERE INSTITUTE_ID=' . $THIS_RET['INSTITUTE_ID'] . ' AND STAFF_ID=' . $_SESSION['staff_selected'] . ')';
        }
        if ($dates[1]['START_DATE'] == '0000-00-00' && $dates[1]['END_DATE'] != '0000-00-00' && in_array($THIS_RET['INSTITUTE_ID'], $sch_li)) {
            $sql = 'SELECT INSTITUTE_ID FROM staff s,staff_institute_relationship ssr WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.INSTITUTE_ID=' . $THIS_RET['INSTITUTE_ID'] . ' AND ssr.STAFF_ID=' . $_SESSION['staff_selected'] . ' AND ssr.SYEAR=(SELECT MAX(SYEAR) FROM  staff_institute_relationship WHERE INSTITUTE_ID=' . $THIS_RET['INSTITUTE_ID'] . ' AND STAFF_ID=' . $_SESSION['staff_selected'] . ') AND (ssr.END_DATE>=CURDATE() OR ssr.END_DATE<\'0000-01-01\' OR ssr.END_DATE IS NULL)';
        }
        if ($dates[1]['START_DATE'] != '0000-00-00' && in_array($THIS_RET['INSTITUTE_ID'], $sch_li)) {
            $sql = 'SELECT INSTITUTE_ID FROM staff s,staff_institute_relationship ssr WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.INSTITUTE_ID=' . $THIS_RET['INSTITUTE_ID'] . ' AND ssr.STAFF_ID=' . $_SESSION['staff_selected'] . ' AND ssr.SYEAR=(SELECT MAX(SYEAR) FROM  staff_institute_relationship WHERE INSTITUTE_ID=' . $THIS_RET['INSTITUTE_ID'] . ' AND STAFF_ID=' . $_SESSION['staff_selected'] . ')  AND (ssr.START_DATE>=ssr.END_DATE OR ssr.START_DATE<\'0000-01-01\' OR ssr.END_DATE>=CURDATE() OR ssr.END_DATE IS NULL)';
        }
        if ($sql != '')
            $user_exist_institute = DBGet(DBQuery($sql));
        else
            $user_exist_institute = array();
        if (!empty($user_exist_institute)) {
            if (SelectedUserProfile('PROFILE_ID') == 0)
                return '<TABLE class=LO_field><TR>' . '<TD>' . "<input checked name=unused[$THIS_RET[ID]] type='checkbox'  id=$THIS_RET[ID] onClick='setHiddenCheckbox(\"values[INSTITUTES][$THIS_RET[ID]]\",this,$THIS_RET[ID]);'  />" . '</TD></TR></TABLE>';
            else
                return '<TABLE class=LO_field><TR>' . '<TD>' . "<input checked name=unused[$THIS_RET[ID]]  type='checkbox' id=$THIS_RET[ID] onClick='setHiddenCheckbox(\"values[INSTITUTES][$THIS_RET[ID]]\",this,$THIS_RET[ID]);' />" . '</TD></TR></TABLE>';
        } else {
            if (SelectedUserProfile('PROFILE_ID') == 0)
                return '<TABLE class=LO_field><TR>' . '<TD>' . "<input name=unused[$THIS_RET[ID]]  type='checkbox' id=$THIS_RET[ID] onClick='setHiddenCheckbox(\"values[INSTITUTES][$THIS_RET[ID]]\",this,$THIS_RET[ID]);' />" . '</TD></TR></TABLE>';
            else
                return '<TABLE class=LO_field><TR>' . '<TD>' . "<input name=unused[$THIS_RET[ID]]  type='checkbox' id=$THIS_RET[ID] onClick='setHiddenCheckbox(\"values[INSTITUTES][$THIS_RET[ID]]\",this,$THIS_RET[ID]);' />" . '</TD></TR></TABLE>';
        }
    }
}

function _makeStatus($value, $column)
{
    global $THIS_RET;
    if ($_REQUEST['staff_id'] == 'new')
        $status_value = '';
    else {

        $dates = DBGet(DBQuery("SELECT ssr.START_DATE,ssr.END_DATE FROM staff s,staff_institute_relationship ssr WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.INSTITUTE_ID='" . $THIS_RET['INSTITUTE_ID'] . "' AND ssr.STAFF_ID='" . $_SESSION['staff_selected'] . "' AND ssr.SYEAR=(SELECT MAX(SYEAR) FROM  staff_institute_relationship WHERE INSTITUTE_ID='" . $THIS_RET['INSTITUTE_ID'] . "' AND STAFF_ID='" . $_SESSION['staff_selected'] . "')"));
        if ($dates[1]['START_DATE'] == '0000-00-00' && $dates[1]['END_DATE'] == '0000-00-00') {
            $sql = 'SELECT INSTITUTE_ID FROM staff s,staff_institute_relationship ssr WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.INSTITUTE_ID=' . $THIS_RET['INSTITUTE_ID'] . ' AND ssr.STAFF_ID=' . $_SESSION['staff_selected'] . ' AND ssr.SYEAR=(SELECT MAX(SYEAR) FROM  staff_institute_relationship WHERE INSTITUTE_ID=' . $THIS_RET['INSTITUTE_ID'] . ' AND STAFF_ID=' . $_SESSION['staff_selected'] . ')';
        }

        if ($dates[1]['START_DATE'] == '0000-00-00' && $dates[1]['END_DATE'] != '0000-00-00') {
            $sql = 'SELECT INSTITUTE_ID FROM staff s,staff_institute_relationship ssr WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.INSTITUTE_ID=' . $THIS_RET['INSTITUTE_ID'] . ' AND ssr.STAFF_ID=' . $_SESSION['staff_selected'] . ' AND ssr.SYEAR=(SELECT MAX(SYEAR) FROM  staff_institute_relationship WHERE INSTITUTE_ID=' . $THIS_RET['INSTITUTE_ID'] . ' AND STAFF_ID=' . $_SESSION['staff_selected'] . ') AND (ssr.END_DATE>=CURDATE() OR ssr.END_DATE<\'0000-01-01\' OR ssr.END_DATE IS NULL)';
        }
        if ($dates[1]['START_DATE'] != '0000-00-00' && $dates[1]['END_DATE'] == '0000-00-00') {
            $sql = 'SELECT INSTITUTE_ID FROM staff s,staff_institute_relationship ssr WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.INSTITUTE_ID=' . $THIS_RET['INSTITUTE_ID'] . ' AND ssr.STAFF_ID=' . $_SESSION['staff_selected'] . ' AND ssr.SYEAR=(SELECT MAX(SYEAR) FROM  staff_institute_relationship WHERE INSTITUTE_ID=' . $THIS_RET['INSTITUTE_ID'] . ' AND STAFF_ID=' . $_SESSION['staff_selected'] . ') ';
        }
        if ($dates[1]['START_DATE'] != '0000-00-00' && $dates[1]['END_DATE'] != '0000-00-00') {
            $sql = 'SELECT INSTITUTE_ID FROM staff s,staff_institute_relationship ssr WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.INSTITUTE_ID=' . $THIS_RET['INSTITUTE_ID'] . ' AND ssr.STAFF_ID=' . $_SESSION['staff_selected'] . ' AND ssr.SYEAR=(SELECT MAX(SYEAR) FROM  staff_institute_relationship WHERE INSTITUTE_ID=' . $THIS_RET['INSTITUTE_ID'] . ' AND STAFF_ID=' . $_SESSION['staff_selected'] . ')  AND ssr.END_DATE>=\'' . date('Y-m-d') . '\' ';
        }
        if ($dates[1]['START_DATE'] != '0000-00-00') {
            $sql = 'SELECT INSTITUTE_ID FROM staff s,staff_institute_relationship ssr WHERE ssr.STAFF_ID=s.STAFF_ID AND ssr.INSTITUTE_ID=' . $THIS_RET['INSTITUTE_ID'] . ' AND ssr.STAFF_ID=' . $_SESSION['staff_selected'] . ' AND ssr.SYEAR=(SELECT MAX(SYEAR) FROM  staff_institute_relationship WHERE INSTITUTE_ID=' . $THIS_RET['INSTITUTE_ID'] . ' AND STAFF_ID=' . $_SESSION['staff_selected'] . ')  AND (ssr.END_DATE>=\'' . date('Y-m-d') . '\' OR ssr.END_DATE IS NULL OR ssr.END_DATE<\'0000-01-01\')';
        }
        $user_exist_institute = DBGet(DBQuery($sql));
        if (!empty($user_exist_institute))
            $status_value = 'Active';
        else {
            if ($dates[1]['START_DATE'] != '0000-00-00' && $dates[1]['END_DATE'] != '0000-00-00')
                $status_value = 'Inactive';
            else
                $status_value = '';
        }
    }
    return '<TABLE class=LO_field><TR>' . '<TD>' . $status_value . '</TD></TR></TABLE>';
}

?>
