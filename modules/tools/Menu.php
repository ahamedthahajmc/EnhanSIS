<?php

include('../../RedirectModulesInc.php');
$menu['tools']['admin'] = array(
						
                               'tools/LogDetails.php'=>_accessLog,
			                         'tools/DeleteLog.php'=>_deleteLog,
                               'tools/Rollover.php'=>_rollover,
                               'tools/Backup.php'=>_backupDatabase,
                               'tools/DataImport.php'=>_dataImportUtility,
                               'tools/GenerateApi.php'=>_apiToken,
                                1=>_reports,
                                'tools/Reports.php?func=Basic'=>_atAGlance,
                               'tools/Reports.php?func=Ins_r'=>_instituteReports,
                               'tools/Reports.php?func=Ins_cf'=>_instituteCustomFieldReports,
    );
?>
