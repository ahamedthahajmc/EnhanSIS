<?php

include('../../RedirectModulesInc.php');
if (clean_param($_REQUEST['modfunc'], PARAM_ALPHAMOD) == 'save') {

    if (count($_SESSION['st_arr'])) {
        $st_list = '\'' . implode('\',\'', $_SESSION['st_arr']) . '\'';

        $extra['WHERE'] = ' AND s.STAFF_ID IN (' . $st_list . ')';
        $extra['user_profile'] = 'parent';
        echo "<table width=100%  style=\" font-family:Arial; font-size:12px;\" >";
        echo "<tr><td width=105>" . DrawLogo() . "</td><td style=\"font-size:15px; font-weight:bold; padding-top:20px;\">" . GetInstitute(UserInstitute()) . "<div style=\"font-size:12px;\">"._userAdvancedReport."</div></td><td align=right style=\"padding-top:20px;\">" . ProperDate(DBDate()) . "<br />"._poweredByhani."</td></tr><tr><td colspan=3 style=\"border-top:1px solid #333;\">&nbsp;</td></tr></table>";
        echo "<table >";
        include('modules/miscellaneous/UserExport.php');
    }
}

if (clean_param($_REQUEST['modfunc'], PARAM_ALPHAMOD) == 'call') {
    $_SESSION['st_arr'] = $_REQUEST['st_arr'];

    echo "<FORM action=ForExport.php?modname=" . strip_tags(trim($_REQUEST[modname])) . "&head_html=Staff+Advanced+Report&modfunc=save&search_modfunc=list&HaniIMS_PDF=true&_dis_user=$_REQUEST[_dis_user]&_search_all_institutes=$_REQUEST[_search_all_institutes] method=POST target=_blank>";
    echo '<DIV id=fields_div></DIV>';

    echo '<div class="panel panel-default">';
    echo '<div class="panel-body">';
    include('modules/miscellaneous/UserExport.php');
    echo '</div>';
    echo '<div class="panel-footer text-right"><div class="heading-elements"><INPUT type=submit value="'._createReportForSelectedUsers.'" class="btn btn-primary"></div></div>';
    echo '</div>';
    echo "</FORM>";
}

if (!$_REQUEST['modfunc'] || $_REQUEST['modfunc'] == 'list') {
    DrawBC(""._users." > " . ProgramTitle());

    if ($_REQUEST['modfunc'] == 'list') {
        $extra['columns_after'] = array('LAST_LOGIN' =>_lastLogin);
        $extra['functions'] = array('LAST_LOGIN' => 'makeLogin');

        $extra['SELECT'] = ',LAST_LOGIN,CONCAT(\'<INPUT type=checkbox name=st_arr[] value=\',s.STAFF_ID,\' checked>\') AS CHECKBOX';
        $extra['columns_before'] = array('CHECKBOX' => '</A><INPUT type=checkbox value=Y name=controller checked onclick="checkAll(this.form,this.form.controller.checked,\'st_arr\');"><A>');
        $extra['options']['search'] = false;
        echo "<FORM action=Modules.php?modname=" . strip_tags(trim($_REQUEST[modname])) . "&modfunc=call method=POST>";
        echo '<DIV id=fields_div></DIV>';
        echo '<div class="panel panel-default">';
        
        if ($_REQUEST['_dis_user'])
            echo '<INPUT type=hidden name=_dis_user value=' . strip_tags(trim($_REQUEST['_dis_user'])) . '>';
        if ($_REQUEST['_search_all_institutes'])
            echo '<INPUT type=hidden name=_search_all_institutes value=' . strip_tags(trim($_REQUEST['_search_all_institutes'])) . '>';
        Search('staff_id', $extra);
        if ($_SESSION['count_stf'] != '0') {
            unset($_SESSION['count_stf']);
            echo '<div class="panel-footer text-right"><div class="heading-elements"><INPUT type=submit value="'._createReportForSelectedUsers.'" class="btn btn-primary" onclick="self_disable(this);"></div></div>';
        }
        echo '</div>'; //.panel
        echo "</FORM>";
    } else {
        unset($_SESSION['staff_id']);
        Search('staff_id', $extra);
    }
}
?>
