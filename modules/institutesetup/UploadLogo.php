<?php


include('../../RedirectModulesInc.php');
include("modules/students/UploadClassFnc.php");

PopTable('header', _uploadInstituteLogo);

//$InstituteLogoPath = 'assets/institutelogo';
//if (!file_exists($InstituteLogoPath)) {
//    mkdir($InstituteLogoPath);
//}

if (clean_param($_REQUEST['modfunc'], PARAM_ALPHAMOD) == 'edit') {

//    if ($InstituteLogoPath && (($file = @fopen($picture_path = $_SESSION['logo_path'], 'r')) )) {
//        echo '<div align=center><IMG SRC="' . $picture_path . '" height=100 width=100 class=pic></div><div class=break></div>';
//    }
//    unset($_REQUEST['modfunc']);

    $sch_img_info = DBGet(DBQuery('SELECT * FROM user_file_upload WHERE INSTITUTE_ID=' . UserInstitute() . ' AND FILE_INFO=\'schlogo\''));
    if (count($sch_img_info) > 0) {
        echo '<div class="text-center m-b-20"><div align=center class="image-holder inline-block"><IMG SRC="data:image/jpeg;base64,' . base64_encode($sch_img_info[1]['CONTENT']) . '" style="max-width: 300px" class=pic></div></div></div>';
    }
    unset($_REQUEST['modfunc']);
}
if (UserInstitute()) {

    if (clean_param($_REQUEST['action'], PARAM_ALPHAMOD) == 'upload' && $_FILES['file']['name']) {


//        $upload = new upload();
//        $upload->name = $_FILES["file"]["name"];
//        $target_path = $InstituteLogoPath . '/' . substr($_FILES["file"]["name"], 0, strrpos($_FILES["file"]["name"], ".")) . UserInstitute() . '.' . $upload->get_file_extension($upload->name);
//
//        $destination_path = $InstituteLogoPath;
//        $upload->target_path = $target_path;
//
//        $upload->destination_path = $destination_path;
//
//        $upload->setFileExtension();
//
//        $upload->validateImage();
//        if ($upload->wrongFormat == 1) {
//            $_FILES["file"]["error"] = 1;
//        }
//
//        if ($_FILES["file"]["error"] > 0) {
//            $msg = "<font color=red><b>Cannot upload file. Only jpeg, jpg, png, gif files are allowed.</b></font>";
//            echo '
//	' . $msg . '
//	<form enctype="multipart/form-data" action="Modules.php?modname=institutesetup/UploadLogo.php&action=upload" method="POST">';
//            echo '<div align=center>Select Logo: <input name="file" type="file" /><br /><br>
//<input type="submit" value="'._upload.'" name="Submit" class="btn btn-primary" />&nbsp;<input type=button class="btn btn-default" value="'._cancel.'" onclick=\'load_link(Modules.php?modname=institutesetup/Institutes.php);\'></div>
//</form>';
//            PopTable('footer');
//        } 
        $fileName = $_FILES['file']['name'];
        $tmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];
        $upload = new upload();
        $upload->name = $_FILES["file"]["name"];
        $upload->fileSize = $fileSize;
//        $target_path=$InstituteLogoPath.'/'.substr($_FILES["file"]["name"],0,strrpos($_FILES["file"]["name"],".")).UserInstitute().'.'.$upload->get_file_extension($upload->name);
//	$destination_path = $InstituteLogoPath;	   
//	$upload->target_path=$target_path;
//
//	$upload->destination_path=$destination_path;
        $sch_img_info = DBGet(DBQuery('SELECT * FROM user_file_upload WHERE INSTITUTE_ID=' . UserInstitute() . ' AND FILE_INFO=\'schlogo\''));
        if (count($sch_img_info) > 0)
            $upload->deleteOldImage($sch_img_info[1]['ID']);
        $upload->setFileExtension();

        $upload->validateImage();
        if ($upload->wrongFormat == 1) {
            $_FILES["file"]["error"] = 1;
        }
        $upload->validateImageSize();
        if ($upload->wrongSize == 1) {
            $_FILES["file"]["error"] = 1;
        }
        if ($_FILES["file"]["error"] > 0 && $upload->wrongFormat == 1) {
            $msg = "<font color=red><b>Cannot upload file. Only jpeg, jpg, png, gif files are allowed.</b></font>";
            echo '
	' . $msg . '
	<form enctype="multipart/form-data" action="Modules.php?modname=institutesetup/UploadLogo.php&action=upload" method="POST">';
            echo '<div align=center>Select Logo: <input name="file" type="file" /><b><span >(Maximum upload file size 10 MB)</span></b><br /><br>
<input type="submit" value="'._upload.'" name="Submit" class=btn_medium />&nbsp;<input type=button class=btn_medium value="'._cancel.'" onclick=\'load_link("Modules.php?modname=institutesetup/Institutes.php");\'></div>
</form>';
            PopTable('footer');
        } else if ($_FILES["file"]["error"] > 0 && $upload->wrongSize == 1) {
            $msg = "<font color=red><b>File too large. Maximum upload file size limit 10 MB.</b></font>";
            echo '
	' . $msg . '
	<form enctype="multipart/form-data" action="Modules.php?modname=institutesetup/UploadLogo.php&action=upload" method="POST">';
            echo '<div align=center>Select Logo: <input name="file" type="file" /> <b><span >(Maximum upload file size 10 MB)</span></b><br /><br>
<input type="submit" value="'._upload.'" name="Submit" class=btn_medium />&nbsp;<input type=button class=btn_medium value="'._cancel.'" onclick=\'load_link("Modules.php?modname=institutesetup/Institutes.php");\'></div>
</form>';
            PopTable('footer');
        } else {


//            move_uploaded_file($_FILES["file"]["tmp_name"], $upload->target_path);
//            if ($_SESSION['logo_path']) {
//                $upload_edit_sql = DBQuery("UPDATE program_config SET VALUE='$upload->target_path' WHERE INSTITUTE_ID='" . UserInstitute() . "' AND PROGRAM='InstituteLogo' AND TITLE='PATH'");
//                if ($_SESSION['logo_path'] != $upload->target_path)
//                    unlink($_SESSION['logo_path']);
//                unset($_SESSION['logo_path']);
//            }
//            else {
//                $upload_sql = DBQuery('INSERT INTO program_config (INSTITUTE_ID,PROGRAM ,TITLE,VALUE) VALUES(\'' . UserInstitute() . '\',\'InstituteLogo\',\'PATH\',\'' . $upload->target_path . '\')');
//            }
//            @fopen($upload->target_path, 'r');
//            echo '<div align=center><IMG SRC="' . $upload->target_path . '" height=100 width=100 class=pic></div><div class=break></div>';
//            fclose($upload->target_path);
//
//            $filename = $upload->target_path;
//            echo '<div align=center><input type=button class="btn btn-primary" value=Done onclick=\'load_link(Modules.php?modname=institutesetup/Institutes.php);\'></div>';
//            PopTable('footer');

            $fp = fopen($tmpName, 'r');
            $content = fread($fp, filesize($tmpName));
            $content = addslashes($content);
            fclose($fp);

            // if (!get_magic_quotes_gpc()) {
            //     $fileName = addslashes($fileName);
            // }
            $fileName = addslashes($fileName);

            DBQuery('INSERT INTO user_file_upload (USER_ID,PROFILE_ID,INSTITUTE_ID,SYEAR,NAME, SIZE, TYPE, CONTENT,FILE_INFO) VALUES (' . UserID() . ',' . UserProfileID() . ',' . UserInstitute() . ',' . UserSyear() . ',\'' . $fileName . '\', \'' . $fileSize . '\', \'' . $fileType . '\', \'' . $content . '\',\'schlogo\')');
            $sch_img_info = DBGet(DBQuery('SELECT * FROM user_file_upload WHERE INSTITUTE_ID=' . UserInstitute() . ' AND FILE_INFO=\'schlogo\''));

            echo '<div align=center><IMG SRC="data:image/jpeg;base64,' . base64_encode($sch_img_info[1]['CONTENT']) . '" height=100 width=100 class=pic></div><div class=break></div>';
//fclose($upload->target_path);
//      $filename =  $upload->target_path;
            echo '<div align=center><input type=button class=btn_medium value=Done onclick=\'load_link("Modules.php?modname=institutesetup/Institutes.php");\'></div>';
            PopTable('footer');
        }
    } else {
        echo '
' . $msg . '
<form enctype="multipart/form-data" action="Modules.php?modname=institutesetup/UploadLogo.php&action=upload" method="POST">';
        echo '<div align=center>Select Logo: <input name="file" type="file" /><b><span >(Maximum upload file size 10 MB)</span></b><br /><br>
<input type="submit" name="Submit"  value="'._upload.'" class="btn btn-primary" />&nbsp;<input type=button class="btn btn-default" value="'._cancel.'" onclick=\'load_link("Modules.php?modname=institutesetup/Institutes.php");\'></div>
</form>';
        PopTable('footer');
    }
} else {
    echo 'Please select a institute first!';
    PopTable('footer');
}
?>