<?php


include('lang/language.php');
if ($_REQUEST['modfunc'] == 'update') {

    if ($_REQUEST['activity']) {
        $TOTAL_COUNT = DBGet(DBQuery('SELECT COUNT(ACTIVITY_DAYS) AS TOTAL_COUNT FROM system_preference_misc'));
        $TOTAL_COUNT = $TOTAL_COUNT[1]['TOTAL_COUNT'];
        if ($TOTAL_COUNT == 0 && $_REQUEST['activity']['ACTIVITY_DAYS']) {
            DBQuery('INSERT INTO system_preference_misc (ACTIVITY_DAYS) VALUES(' . $_REQUEST['activity']['ACTIVITY_DAYS'] . ')');
        } else if ($TOTAL_COUNT == 1) {
            $sql = 'UPDATE system_preference_misc SET ';
            foreach ($_REQUEST['activity'] as $column_name => $value) {
                $sql .= '' . $column_name . '=\'' . str_replace("\'", "''", str_replace("`", "''", $value)) . '\',';
            }
            $sql = substr($sql, 0, -1) . ' WHERE 1=1';
            DBQuery($sql);
        }
    }
    unset($_REQUEST['activity']);
}
$activity_RET = DBGet(DBQuery("SELECT ACTIVITY_DAYS FROM system_preference_misc LIMIT 1"));
$activity = $activity_RET[1];


echo "<FORM name=activity class=no-margin id=activity action=Modules.php?modname=" . strip_tags(trim($_REQUEST[modname])) . "&modfunc=update&page_display=INACTIVITY method=POST>";

echo '<div class="form-group"><label class="control-label text-uppercase"><b>'._maximumInactiveDaysAllowedBeforeAccountIsDisabled.'</b></label>' . TextInput($activity['ACTIVITY_DAYS'], 'activity[ACTIVITY_DAYS]', '', 'class=cell_floating') . '</div>';
//if ($_REQUEST['page_display']) {
//    echo "<a href=Modules.php?modname=" . strip_tags(trim($_REQUEST[modname])) . " class=\"btn btn-default\"><i class=\"fa fa-arrow-left\"></i>&nbsp; Back to System Preference</a>";
//}
echo SubmitButton(_save, '', 'class="btn btn-primary pull-right" onclick="self_disable(this);"');

echo '</FORM>';
