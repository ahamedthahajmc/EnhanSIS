<?php

include('../../RedirectModulesInc.php');
DrawBC(""._tools." > " . ProgramTitle());

if (isset($_REQUEST['del'])) {

    if ($_REQUEST['day_start'] && $_REQUEST['month_start'] && $_REQUEST['year_start']) {

        $start_date = $_REQUEST['year_start'] . '-' . $_REQUEST['month_start'] . '-' . $_REQUEST['day_start'];
        $start_date = ProperDateMAvr($start_date);

        $org_start_date = $_REQUEST['day_start'] . '-' . $_REQUEST['month_start'] . '-' . $_REQUEST['year_start'];

       // $conv_st_date = con_date($org_start_date);
        $delete_start_date=$_REQUEST['year_start'] . '-' . $_REQUEST['month_start'] . '-' . $_REQUEST['day_start'].' '.'00:00:00';
   $conv_st_date=$delete_start_date;
        }

    if ($_REQUEST['day_end'] && $_REQUEST['month_end'] && $_REQUEST['year_end']) {

        $end_date = $_REQUEST['year_end'] . '-' . $_REQUEST['month_end'] . '-' . $_REQUEST['day_end'];
        $end_date = ProperDateMAvr($end_date);
        $org_end_date = $_REQUEST['day_end'] . '-' . $_REQUEST['month_end'] . '-' . $_REQUEST['year_end'];

       // $conv_end_date = con_date_end($org_end_date);
        $delete_end_date=$_REQUEST['year_end'] . '-' . $_REQUEST['month_end'] . '-' . $_REQUEST['day_end'].' '.'23:59:59';
   $conv_end_date=$delete_end_date;
        }


    # ------------------------------- Deletion Of Log Records ----------------------------- #
    if (isset($conv_st_date) && isset($conv_end_date)) {
        
        $sql_del = DBQuery('DELETE FROM login_records WHERE LOGIN_TIME >=\'' . $conv_st_date . '\' AND LOGIN_TIME <=\'' . $conv_end_date . '\'');
        echo '<center><font color="red"><b>'._logDeletedSuccessfully.'</b></font></center>';
    }

    if (isset($conv_st_date) && !isset($conv_end_date)) {
        $sql_del = DBQuery('DELETE FROM login_records WHERE LOGIN_TIME >=\'' . $conv_st_date . '\'');
        echo '<center><font color="red"><b>'._logDeletedSuccessfully.'</b></font></center>';
    }

    if (!isset($conv_st_date) && isset($conv_end_date)) {
        $sql_del = DBQuery('DELETE FROM login_records WHERE LOGIN_TIME <=\'' . $conv_end_date . '\'');
        echo '<center><font color="red"><b>'._logDeletedSuccessfully.'</b></font></center>';
    }

    if (!isset($conv_st_date) && !isset($conv_end_date)) {
        echo '<center><font color="red"><b>'._youHaveToSelectAtleastOneDateFromTheDateRange.'</b></font></center>';
    }
    
   
    
    
    # ------------------------------------------------------------------------------------- #
}

echo '<div class="row">';
echo '<div class="col-md-8 col-md-offset-2">';
echo "<FORM class=\"form-horizontal\" name=del id=del action=Modules.php?modname=$_REQUEST[modname] method=POST>";
PopTable('header',  _deleteLog);

echo '<h5 class="text-center">'._pleaseSelectDateRange.'</h5>';

echo '<div class="row">';
echo '<div class="col-lg-6 col-lg-offset-3">';

echo '<div class="form-group">';
echo '<label class="col-md-2 control-label text-right">'._from.'</label><div class="col-md-10">';
echo DateInputAY($start_date, 'start', 1);
echo '</div>'; //.col-md-8
echo '</div>'; //.form-group

echo '</div>'; //.col-lg-4
echo '</div>'; //.row
echo '<div class="row">';
echo '<div class="col-lg-6 col-lg-offset-3">';

echo '<div class="form-group">';
echo '<label class="col-md-2 control-label text-right">'._to.'</label><div class="col-md-10">';
echo DateInputAY($end_date, 'end', 2);
echo '</div>'; //.col-md-8
echo '</div>'; //.form-group

echo '</div>'; //.col-lg-4
echo '</div>'; //.row

$btn = '<input type="submit" class="btn btn-primary" value="'._delete.'" name="del" onclick="self_disable(this);">';

PopTable('footer', $btn);
echo '</FORM>';
echo '</div>';
echo '</div>'; //.row

function con_date($date) {
   
    $mother_date = $date;
    $year = substr($mother_date, 7);
    $temp_month = substr($mother_date, 3, 3);

    if ($temp_month == 'JAN')
        $month = '01';
    elseif ($temp_month == 'FEB')
        $month = '02';
    elseif ($temp_month == 'MAR')
        $month = '03';
    elseif ($temp_month == 'APR')
        $month = '04';
    elseif ($temp_month == 'MAY')
        $month = '05';
    elseif ($temp_month == 'JUN')
        $month = '06';
    elseif ($temp_month == 'JUL')
        $month = '07';
    elseif ($temp_month == 'AUG')
        $month = '08';
    elseif ($temp_month == 'SEP')
        $month = '09';
    elseif ($temp_month == 'OCT')
        $month = '10';
    elseif ($temp_month == 'NOV')
        $month = '11';
    elseif ($temp_month == 'DEC')
        $month = '12';

    $day = substr($mother_date, 0, 2);

    $select_date = $year . '-' . $month . '-' . $day . ' ' . '00:00:00';
    return $select_date;
}

function con_date_end($date) {
    $mother_date = $date;
    $year = substr($mother_date, 7);
    $temp_month = substr($mother_date, 3, 3);

    if ($temp_month == 'JAN')
        $month = '01';
    elseif ($temp_month == 'FEB')
        $month = '02';
    elseif ($temp_month == 'MAR')
        $month = '03';
    elseif ($temp_month == 'APR')
        $month = '04';
    elseif ($temp_month == 'MAY')
        $month = '05';
    elseif ($temp_month == 'JUN')
        $month = '06';
    elseif ($temp_month == 'JUL')
        $month = '07';
    elseif ($temp_month == 'AUG')
        $month = '08';
    elseif ($temp_month == 'SEP')
        $month = '09';
    elseif ($temp_month == 'OCT')
        $month = '10';
    elseif ($temp_month == 'NOV')
        $month = '11';
    elseif ($temp_month == 'DEC')
        $month = '12';

    $day = substr($mother_date, 0, 2);

    $select_date = $year . '-' . $month . '-' . $day . ' ' . '23:59:59';
    return $select_date;
}

?>	
