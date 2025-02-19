<?php

include('../../RedirectModulesInc.php');
include('lang/language.php');

unset($_SESSION['_REQUEST_vars']['values']);
unset($_SESSION['_REQUEST_vars']['modfunc']);
DrawBC(""._instituteSetup." > " . ProgramTitle());
// --------------------------------------------------------------- Test SQL ------------------------------------------------------------------ //
// --------------------------------------------------------------- Tset SQL ------------------------------------------------------------------ //

if (clean_param($_REQUEST['modfunc'], PARAM_ALPHAMOD) == 'update' && (clean_param($_REQUEST['button'], PARAM_ALPHAMOD) == _save || clean_param($_REQUEST['button'], PARAM_ALPHAMOD) == _update || clean_param($_REQUEST['button'], PARAM_ALPHAMOD) == '')) {
    if (clean_param($_REQUEST['values'], PARAM_NOTAGS) && $_POST['values'] && User('PROFILE') == 'admin') {
        if ($_REQUEST['new_institute'] != 'true') {

            $sql = 'UPDATE institutes SET ';


            foreach ($_REQUEST as $col => $val) {
                $dt_ex = explode("_", $col);
                if ($dt_ex[0] == 'month') {
                    if ($_REQUEST['day_' . $dt_ex[1]]['CUSTOM_' . $dt_ex[1]] != '' && $_REQUEST['month_' . $dt_ex[1]]['CUSTOM_' . $dt_ex[1]] != '' && $_REQUEST['year_' . $dt_ex[1]]['CUSTOM_' . $dt_ex[1]] != '') {
                        // $_REQUEST['values']['CUSTOM_' . $dt_ex[1]] = $_REQUEST['year_' . $dt_ex[1]]['CUSTOM_' . $dt_ex[1]] . "-" . MonthFormatter($_REQUEST['month_' . $dt_ex[1]]['CUSTOM_' . $dt_ex[1]]) . '-' . $_REQUEST['day_' . $dt_ex[1]]['CUSTOM_' . $dt_ex[1]];
                        $_REQUEST['values']['CUSTOM_' . $dt_ex[1]] = $_REQUEST['year_' . $dt_ex[1]]['CUSTOM_' . $dt_ex[1]] . "-" . $_REQUEST['month_' . $dt_ex[1]]['CUSTOM_' . $dt_ex[1]] . '-' . $_REQUEST['day_' . $dt_ex[1]]['CUSTOM_' . $dt_ex[1]];
                    }
                }
            }

            foreach ($_REQUEST['values'] as $column => $value) {
                if (substr($column, 0, 6) == 'CUSTOM') {
                    $custom_id = str_replace("CUSTOM_", "", $column);
                    $custom_RET = DBGet(DBQuery("SELECT TITLE,TYPE,REQUIRED FROM institute_custom_fields WHERE ID=" . $custom_id));

                    $custom = DBGet(DBQuery("SHOW COLUMNS FROM institutes WHERE FIELD='" . $column . "'"));
                    $custom = $custom[1];

                    if ($custom_RET[1]['TYPE'] == 'multiple') {
                        $valueSize = count($value);
                        if($valueSize == 0) {
                            $valueSize = '';
                        }
                    } else {
                        $valueSize = trim($value);
                    }

                    if ($custom['NULL'] == 'NO' && trim($valueSize) == '' && $custom['DEFAULT']) {
                        $value = $custom['DEFAULT'];
                    } else if ($custom['NULL'] == 'NO' && $valueSize == '' && $custom_RET[1]['REQUIRED'] == 'Y') {
                        $custom_TITLE = $custom_RET[1]['TITLE'];
                        echo "<div class='alert alert-danger'>". ucfirst(_unableToSaveDataBecause) ." " . $custom_TITLE . ' '._isRequired.'</div>';
                        $error = true;
                        break;
                    } else if ($custom_RET[1]['TYPE'] == 'numeric' && (!is_numeric($value) && $value != '')) {
                        $custom_TITLE = $custom_RET[1]['TITLE'];
                        echo "<div class='alert alert-danger'>". ucfirst(_unableToSaveDataBecause) ." " . $custom_TITLE . ' '. $isNumericType.'</div>';
                        $error = true;
                    } else {
                        $m_custom_RET = DBGet(DBQuery("SELECT ID,TITLE,TYPE FROM institute_custom_fields WHERE ID='" . $custom_id . "' AND TYPE='multiple'"));

                        if ($m_custom_RET) {
                            $str = "";
                            foreach ($value as $m_custom_val) {
                                if ($m_custom_val)
                                    $str.="||" . $m_custom_val;
                            }
                            if ($str)
                                $value = $str . "||";
                            else {
                                $value = '';
                            }
                        }
                    }
                }  ###Custom Ends#####
                if ($column != 'WWW_ADDRESS')
                $value = paramlib_validation($column, trim($value));
                // ',\''.singleQuoteReplace('','',trim($value)).'\''
                if (stripos($_SERVER['SERVER_SOFTWARE'], 'linux')) {
                    $sql .= $column . '=\'' . singleQuoteReplace('', '', trim($value)) . '\',';
                } else {
                    $sql .= $column . '=\'' . singleQuoteReplace('', '', trim($value)) . '\',';
                }
            }
            $sql = substr($sql, 0, -1) . ' WHERE ID=\'' . UserInstitute() . '\'';
           
            if ($error != 1)
                DBQuery($sql);
            // echo '<script language=JavaScript>parent.side.location="' . $_SESSION['Side_PHP_SELF'] . '?modcat="+parent.side.document.forms[0].modcat.value;</script>';
            $note[] = _thisInstituteHasBeenModified; //This institute has been modified.
            $_REQUEST['modfunc'] = '';
        }
        else {
            $fields = $values = '';

            foreach ($_REQUEST['values'] as $column => $value)
                if ($column != 'ID' && $value) {
                    if ($column != 'WWW_ADDRESS')
                    $value = paramlib_validation($column, trim($value));
                    $fields .= ',' . $column;
                    $values .= ',\'' . singleQuoteReplace('', '', trim($value)) . '\'';
                }

            if ($fields && $values) {


                // $id = DBGet(DBQuery('SHOW TABLE STATUS LIKE \'institutes\''));
                // $id = $id[1]['AUTO_INCREMENT'];

                
                $start_date=$_REQUEST['year__min'].'-'.$_REQUEST['month__min'].'-'.$_REQUEST['day__min'];
                $end_date=$_REQUEST['year__max'].'-'.$_REQUEST['month__max'].'-'.$_REQUEST['day__max'];
                $syear=$_REQUEST['year__min'];
                $sql = 'INSERT INTO institutes (SYEAR' . $fields . ') values(' . $syear . '' . $values . ')';
                DBQuery($sql);
                $id = mysqli_insert_id($connection);
                
                DBQuery('INSERT INTO  staff_institute_relationship(staff_id,institute_id,syear,start_date) VALUES (' . UserID() . ',' . $id . ',' . $syear. ',"'.date('Y-m-d').'")');
                $other_admin_details=DBGet(DBQuery('SELECT * FROM login_authentication WHERE PROFILE_ID=0 AND USER_ID!=' . UserID() . ''));
                if(!empty($other_admin_details))
                {
                foreach($other_admin_details as $institute_data)
                {
                DBQuery('INSERT INTO  staff_institute_relationship(staff_id,institute_id,syear,start_date) VALUES (' . $institute_data['USER_ID'] . ',' . $id . ',' . $syear. ',"'.date('Y-m-d').'")');    
                }
                }
                if (User('PROFILE_ID') != 0) {
                    $super_id = DBGet(DBQuery('SELECT STAFF_ID FROM staff WHERE PROFILE_ID=0 AND PROFILE=\'admin\''));
                    $staff_exists=DBGet(DBQuery('SELECT * FROM staff_institute_relationship WHERE STAFF_ID='.$super_id[1]['STAFF_ID'] . ' AND INSTITUTE_ID='. $id . ' AND SYEAR='.$syear));
                    if(count($staff_exists)==0)
                        DBQuery('INSERT INTO  staff_institute_relationship(staff_id,institute_id,syear,start_date) VALUES (' . $super_id[1]['STAFF_ID'] . ',' . $id . ',' . $syear . ',"'.date('Y-m-d').'")');
                }
                // DBQuery('INSERT INTO institute_years (MARKING_PERIOD_ID,SYEAR,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,ROLLOVER_ID) SELECT fn_marking_period_seq(),SYEAR,\'' . $id . '\' AS INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,POST_START_DATE,POST_END_DATE,DOES_GRADES,DOES_EXAM,DOES_COMMENTS,MARKING_PERIOD_ID FROM institute_years WHERE SYEAR=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\' ORDER BY MARKING_PERIOD_ID');
                DBQuery('INSERT INTO institute_years (MARKING_PERIOD_ID,SYEAR,INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,START_DATE,END_DATE,ROLLOVER_ID) SELECT fn_marking_period_seq(),\''.$syear.'\' as SYEAR,\'' . $id . '\' AS INSTITUTE_ID,TITLE,SHORT_NAME,SORT_ORDER,\''.$start_date.'\' as START_DATE,\''.$end_date.'\' as  END_DATE,MARKING_PERIOD_ID FROM institute_years WHERE SYEAR=\'' . UserSyear() . '\' AND INSTITUTE_ID=\'' . UserInstitute() . '\' ORDER BY MARKING_PERIOD_ID');
                DBQuery('INSERT INTO system_preference(institute_id, full_day_minute, half_day_minute) VALUES (' . $id . ', NULL, NULL)');

                DBQuery('INSERT INTO program_config (INSTITUTE_ID,SYEAR,PROGRAM,TITLE,VALUE) VALUES(\'' . $id . '\',\'' . $syear. '\',\'MissingAttendance\',\'LAST_UPDATE\',\'' . date('Y-m-d') . '\')');
                DBQuery('INSERT INTO program_config(INSTITUTE_ID,SYEAR,PROGRAM,TITLE,VALUE) VALUES(\'' . $id . '\',\'' . $syear . '\',\'UPDATENOTIFY\',\'display_institute\',"Y")');
                $_SESSION['UserInstitute'] = $id;

                $chk_stu_enrollment_codes_exist = DBGet(DBQuery('SELECT COUNT(*) AS STU_ENR_COUNT FROM `student_enrollment_codes` WHERE `syear` = \''.$syear.'\''));
                if($chk_stu_enrollment_codes_exist[1]['STU_ENR_COUNT'] == 0)
                {
                    DBQuery('INSERT INTO `student_enrollment_codes` (`syear`, `title`, `short_name`, `type`) VALUES (\''.$syear.'\', \'Transferred out\', \'TRAN\', \'TrnD\')');
                    DBQuery('INSERT INTO `student_enrollment_codes` (`syear`, `title`, `short_name`, `type`) VALUES (\''.$syear.'\', \'Transferred in\', \'TRAN\', \'TrnE\')');
                    DBQuery('INSERT INTO `student_enrollment_codes` (`syear`, `title`, `short_name`, `type`) VALUES (\''.$syear.'\', \'Rolled over\', \'ROLL\', \'Roll\')');
                    DBQuery('INSERT INTO `student_enrollment_codes` (`syear`, `title`, `short_name`, `type`) VALUES (\''.$syear.'\', \'Dropped Out\', \'DROP\', \'Drop\')');
                    DBQuery('INSERT INTO `student_enrollment_codes` (`syear`, `title`, `short_name`, `type`) VALUES (\''.$syear.'\', \'New\', \'NEW\', \'Add\')');
                }

                unset($_REQUEST['new_institute']);
            }
            echo '<FORM action=Modules.php?modname='.strip_tags(trim($_REQUEST['modname'])).' method=POST>';
	        // echo '<script language=JavaScript>parent.side.location="'.$_SESSION['Side_PHP_SELF'].'?modcat="+parent.side.document.forms[0].modcat.value;</script>';
	
            echo '<div class="panel panel-default">';
            echo '<div class="panel-body text-center">';
            echo '<div class="new-institute-created  p-30">';
            echo '<div class="icon-institute">';
            echo '<span></span>';
            echo '</div>';
            echo '<h5 class="p-20">A new institute called <b class="text-success">'.GetInstitute(UserInstitute()).'</b> has been created. To finish the operation, click the button below.</h5>';
            echo '<div class="text-right p-r-20"><INPUT type="submit" value="Finish Setup" class="btn btn-primary btn-lg"></div>';
            echo '</div>'; //.new-institute-created
            echo '</div>'; //.panel-body
            echo '</div>'; //.panel
        
	        // DrawHeaderHome('<IMG SRC=assets/check.gif> &nbsp; A new institute called <strong>'.  GetInstitute(UserInstitute()).'</strong> has been created. To finish the operation, click OK button.','<INPUT  type=submit value="._ok." class="btn_medium">');
            echo '<input type="hidden" name="copy" value="done"/>';
	        echo '</FORM>';
        }
    } else {
        $_REQUEST['modfunc'] = '';
    }


    unset($_SESSION['_REQUEST_vars']['values']);
    unset($_SESSION['_REQUEST_vars']['modfunc']);
}

if (clean_param($_REQUEST['modfunc'], PARAM_ALPHAMOD) == 'update' && clean_param($_REQUEST['button'], PARAM_ALPHAMOD) == 'Delete' && User('PROFILE') == 'admin') {
    if (DeletePrompt('institute')) {
        if (BlockDelete('institute')) {
            DBQuery('DELETE FROM institutes WHERE ID=\'' . UserInstitute() . '\'');
            DBQuery('DELETE FROM institute_gradelevels WHERE INSTITUTE_ID=\'' . UserInstitute() . '\'');
            DBQuery('DELETE FROM attendance_calendar WHERE INSTITUTE_ID=\'' . UserInstitute() . '\'');
            DBQuery('DELETE FROM institute_periods WHERE INSTITUTE_ID=\'' . UserInstitute() . '\'');
            DBQuery('DELETE FROM institute_years WHERE INSTITUTE_ID=\'' . UserInstitute() . '\'');
            DBQuery('DELETE FROM institute_semesters WHERE INSTITUTE_ID=\'' . UserInstitute() . '\'');
            DBQuery('DELETE FROM institute_quarters WHERE INSTITUTE_ID=\'' . UserInstitute() . '\'');
            DBQuery('DELETE FROM institute_progress_periods WHERE INSTITUTE_ID=\'' . UserInstitute() . '\'');
            DBQuery('UPDATE staff SET CURRENT_INSTITUTE_ID=NULL WHERE CURRENT_INSTITUTE_ID=\'' . UserInstitute() . '\'');
            DBQuery('UPDATE staff SET INSTITUTES=replace(INSTITUTES,\',' . UserInstitute() . ',\',\',\')');

            unset($_SESSION['UserInstitute']);
            //echo '<script language=JavaScript>parent.side.location="' . $_SESSION['Side_PHP_SELF'] . '?modcat="+parent.side.document.forms[0].modcat.value;</script>';
            unset($_REQUEST);
            $_REQUEST['modname'] = "institutesetup/Institutes.php?new_institute=true";
            $_REQUEST['new_institute'] = true;
            unset($_REQUEST['modfunc']);
            echo '
				<SCRIPT language="JavaScript">
				window.location="Side.php?institute_id=new&modcat=' . strip_tags(trim($_REQUEST['modcat'])) . '";
				</SCRIPT>
				';
        }
    }
}
if (clean_param($_REQUEST['copy'], PARAM_ALPHAMOD) == 'done') {
    echo '<div class="alert alert-success alert-styled-left">' . _instituteHasBeenCreatedSuccessfully . '</div>';
    echo'<script type="text/javascript">
    window.setTimeout(function() {
        window.location.href="Modules.php?modname=miscellaneous/portal.php";
    }, 2000);
    </script>';
} else {
    if (!$_REQUEST['modfunc']) {
        if (!$_REQUEST['new_institute']) {
            $institutedata = DBGet(DBQuery('SELECT * FROM institutes WHERE ID=\'' . UserInstitute() . '\''));
            $institutedata = $institutedata[1];
            $institute_name = GetInstitute(UserInstitute());
        } 
        else
            $institute_name = 'Add a Institute';
        if (!$_REQUEST['new_institute'])
            $_REQUEST['new_institute'] = false;
        //echo "<FORM name=institute  id=institute class=\"form-horizontal\"  enctype='multipart/form-data'  METHOD='POST' ACTION='Modules.php?modname=" . strip_tags(trim($_REQUEST['modname'])) . "&modfunc=update&btn=" . $_REQUEST['button'] . "&new_institute=$_REQUEST[new_institute]'>";
        echo "<FORM name=institute  id=institute class=\"form-horizontal\"  enctype='multipart/form-data'  METHOD='POST' ACTION='Modules.php?modname=" . strip_tags(trim($_REQUEST['modname'])) . "&modfunc=update'>";

        PopTable('header',  _instituteInformation);

        echo '<div class="row">';
        echo '<div class="col-lg-6">';
        echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">"._instituteName."<span class=\"text-danger\">*</span></label><div class=\"col-md-8\">" . TextInput($institutedata['TITLE'], 'values[TITLE]', '', ' size=24 onKeyUp=checkDuplicateName(1,this,' . $institutedata['ID'] . '); onBlur=checkDuplicateName(1,this,' . $institutedata['ID'] . ');') . "</div></div>";
        echo "<input type=hidden id=checkDuplicateNameTable1 value='institutes'/>";
        echo "<input type=hidden id=checkDuplicateNameField1 value='title'/>";
        echo "<input type=hidden id=checkDuplicateNameMsg1 value='institute name'/>";
        echo '</div>'; //.col-lg-6

        echo '<div class="col-lg-6">';
        echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">"._address."</label><div class=\"col-md-8\">" . TextInput($institutedata['ADDRESS'], 'values[ADDRESS]', '', 'class=cell_floating maxlength=100 size=24') . "</div></div>";
        echo '</div>'; //.col-lg-6
        echo '</div>'; //.row


        echo '<div class="row">';
        echo '<div class="col-lg-6">';
        echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">"._city."</label><div class=\"col-md-8\">" . TextInput($institutedata['CITY'], 'values[CITY]', '', 'maxlength=100, class=cell_floating size=24') . "</div></div>";
        echo '</div>'; //.col-lg-6

        echo '<div class="col-lg-6">';
        echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">"._state."</label><div class=\"col-md-8\">" . TextInput($institutedata['STATE'], 'values[STATE]', '', 'maxlength=100, class=cell_floating size=24') . "</div></div>";
        echo '</div>'; //.col-lg-6
        echo '</div>'; //.row

        //Zip/Postal Code
        echo '<div class="row">';
        echo '<div class="col-lg-6">';
        echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">"._zipPostalCode."</label><div class=\"col-md-8\">" . TextInput($institutedata['ZIPCODE'], 'values[ZIPCODE]', '', 'maxlength=10 class=cell_floating size=24') . "</div></div>";
        echo '</div>'; //.col-lg-6

        
        echo '<div class="col-lg-6">';
        echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">"._areaCode."</label><div class=\"col-md-8\">" . TextInput($institutedata['AREA_CODE'], 'values[AREA_CODE]', '', 'class=cell_floating size=24') . "</div></div>";
        echo '</div>'; //.col-lg-6
        echo '</div>'; //.row 
        
        
        echo '<div class="col-lg-6">';
        echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">"._telephone."</label><div class=\"col-md-8\">" . TextInput($institutedata['PHONE'], 'values[PHONE]', '', 'class=cell_floating size=24') . "</div></div>";
        echo '</div>'; //.col-lg-6
        echo '</div>'; //.row 


        echo '<div class="row">';
        echo '<div class="col-lg-6">';
        echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">"._principal."</label><div class=\"col-md-8\">" . TextInput($institutedata['PRINCIPAL'], 'values[PRINCIPAL]', '', 'class=cell_floating size=24') . "</div></div>";
        echo '</div>'; //.col-lg-6
        //Base Grading Scale
        echo '<div class="col-lg-6">';
        echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">"._baseGradingScale."<span class=\"text-danger\">*</span></label><div class=\"col-md-8\">" . TextInput($institutedata['REPORTING_GP_SCALE'], 'values[REPORTING_GP_SCALE]', '', 'class=cell_floating maxlength=10 size=24') . "</div></div>";
        echo '</div>'; //.col-lg-6
        echo '</div>'; //.row

         //E-Mail
        echo '<div class="row">';
        echo '<div class="col-md-6">';
        echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">"._email."</label><div class=\"col-md-8\">" . TextInput($institutedata['E_MAIL'], 'values[E_MAIL]', '', 'class=cell_floating maxlength=100 size=24') . "</div></div>";
        echo '</div>'; //.col-md-6

        echo '<div class="col-md-6">';
        
        if (AllowEdit() || !$institutedata['WWW_ADDRESS']) {
            //Website
            echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">"._website."</label><div class=\"col-md-8\">" . TextInput($institutedata['WWW_ADDRESS'], 'values[WWW_ADDRESS]', '', 'class=cell_floating size=24') . "</div></div>";
        } else {
            echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">"._website."</label><div class=\"col-md-8\"><A HREF=http://$institutedata[WWW_ADDRESS] target=_blank>$institutedata[WWW_ADDRESS]</A></div></div>";
        }
        echo '</div>';
        echo '</div>';
        
        echo '<div class="row">';
        if ($institute_name != 'Add a Institute')
            include('modules/institutesetup/includes/InstitutecustomfieldsInc.php');
        echo '</div>';

        echo '<div class="row">';
        echo '<div class="col-md-6">';

        // $uploaded_sql = DBGet(DBQuery("SELECT VALUE FROM program_config WHERE INSTITUTE_ID='" . UserInstitute() . "' AND SYEAR IS NULL AND TITLE='PATH'"));
        // $_SESSION['logo_path'] = $uploaded_sql[1]['VALUE'];
        // if (!$_REQUEST['new_institute'] && file_exists($uploaded_sql[1]['VALUE']))
        
        $sch_img_info= DBGet(DBQuery('SELECT * FROM user_file_upload WHERE INSTITUTE_ID='. UserInstitute().' AND FILE_INFO=\'schlogo\''));
    
        
        if(!$_REQUEST['new_institute'] && count($sch_img_info)>0)
            echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">Institute Logo</label><div class=\"col-md-8\">" . (AllowEdit() != false ? "<a href ='Modules.php?modname=institutesetup/UploadLogo.php&modfunc=edit'>" : '') . "<div class=\"image-holder\"><img src='data:image/jpeg;base64,".base64_encode($sch_img_info[1]['CONTENT'])."' class=img-responsive /></div>" . (AllowEdit() != false ? "</a>" : '') . (AllowEdit() != false ? "<a href='Modules.php?modname=institutesetup/UploadLogo.php&modfunc=edit' class=\"show text-center m-t-10 text-primary\"><i class=\"icon-upload position-left\"></i> Click here to change logo</a>" : '') . "</div></div>";
        else if (!$_REQUEST['new_institute'])
            echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">Institute Logo</label><div class=\"col-md-8\">" . (AllowEdit() != false ? "<a href ='Modules.php?modname=institutesetup/UploadLogo.php' class=\"form-control text-primary\" readonly=\"readonly\"><i class=\"icon-upload position-left\"></i> Click here to upload logo</a>" : '-') . "</div></div>";

        echo '</div>'; //.col-md-4
        echo '</div>'; //.row  

        if($_REQUEST['new_institute']=='true')
        {
            $get_this_institute_date=DBGet(DBQuery('SELECT * FROM institute_years where SYEAR='.UserSyear().' AND INSTITUTE_ID='.UserInstitute()));  
            
            echo '<div class="row">';
            echo '<div class="col-md-6">';
            echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">"._startDate."</label><div class=\"col-md-8\">" . DateInputAY($get_this_institute_date[1]['START_DATE'], '_min', 1). "</div></div>";
            echo '</div>'; //.col-md-6
            
            echo '<div class="col-md-6">';
            echo "<div class=\"form-group\"><label class=\"col-md-4 control-label text-right\">"._endDate."</label><div class=\"col-md-8\">" . DateInputAY($get_this_institute_date[1]['END_DATE'], '_max', 2). "</div></div>";
            echo '</div>'; //.col-md-6
            echo '</div>'; //.row  
        }

        if($_REQUEST['new_institute'] == 'true')
        {
            echo '<input id="h1" type="hidden" value="">';
        }
        else
        {
            echo '<input id="h1" type="hidden" value="'. UserInstitute() .'">';
        }

        $btns = '';
        if (User('PROFILE') == 'admin' && AllowEdit()) {
            //echo '<hr class="no-margin"/>';
            if ($_REQUEST['new_institute']) {
                $btns = "<div class=\"text-right\"><INPUT TYPE=submit name=button id=button class=\"btn btn-primary\" VALUE="._save." onclick='return formcheck_institute_setup_institute(this);'></div>";
            } else {

                $btns = "<div class=\"text-right\"><INPUT TYPE=submit name=button id=button class=\"btn btn-primary\" VALUE="._update." onclick='return formcheck_institute_setup_institute(this);'></div>";
            }
        }


        PopTable('footer',$btns);

        echo "</FORM>";
    }
}

?>
