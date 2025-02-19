<?php
 

include 'RedirectRootInc.php';
include 'Warehouse.php';
include 'Data.php';

if (isset($_REQUEST['down_id']))
    $_REQUEST['down_id'] = sqlSecurityFilter($_REQUEST['down_id']);
if (isset($_REQUEST['filename']))
    $_REQUEST['filename'] = sqlSecurityFilter($_REQUEST['filename']);

if(isset($_REQUEST['down_id']) && $_REQUEST['down_id']!='')
{
    if ((isset($_REQUEST['studentfile']) && $_REQUEST['studentfile'] == 'Y') || (isset($_REQUEST['userfile']) && $_REQUEST['userfile'] == 'Y'))
        $downfile_info = DBGet(DBQuery('SELECT * FROM user_file_upload WHERE id=\'' . $_REQUEST['down_id'] . '\''));
    else
        $downfile_info = DBGet(DBQuery('SELECT * FROM user_file_upload WHERE download_id=\'' . $_REQUEST['down_id'] . '\''));
    header("Cache-Control: public");
    header("Pragma: ");
    header("Expires: 0"); 
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
    header("Cache-Control: private",false); // required for certain browsers 
    header("Content-Length: ".$downfile_info[1]['SIZE']."");
    header("Content-Type: ".$downfile_info[1]['TYPE']."");
    // header("Content-Disposition: attachment; filename=\"".str_replace(' ','_',$downfile_info[1]['NAME'])."\";");
    // header("Content-Disposition: attachment; filename=\"".$downfile_info[1]['NAME']."\";");
    header("Content-Disposition: attachment; filename=\"".str_replace("HaniIMS_space_here", " ", str_replace($downfile_info[1]['USER_ID']."-","",$downfile_info[1]['NAME']))."\";");
    header("Content-Transfer-Encoding: binary");
    ob_clean();
    flush();

    if(isset($_REQUEST['studentfile']) && $_REQUEST['studentfile']=='Y')
    {
        $filedata = @file_get_contents('assets/studentfiles/'.$downfile_info[1]['NAME']);
        echo $filedata;
    }
    else if(isset($_REQUEST['userfile']) && $_REQUEST['userfile']=='Y')
    {
        $filedata = @file_get_contents('assets/stafffiles/'.$downfile_info[1]['NAME']);
        echo $filedata;
    }
    else
    {
        echo $downfile_info[1]['CONTENT'];
    }
    
    exit;
}
else
{
    header('Content-Disposition: attachment; filename="'.urldecode($_REQUEST['name']).'" ');
    readfile('assets/'.urldecode($_REQUEST['filename']));
}
?>
