<?php
 
include('RedirectRootInc.php');

if (!defined('CONFIG_INC')) {
    define('CONFIG_INC', 1);
    // IgnoreFiles should contain any names of files or folders
    // which should be ignored by the function inclusion system.
    $IgnoreFiles = array('.DS_Store', 'CVS', '.svn');
    $HaniIMSPath = dirname(__FILE__) . '/';
    if (file_exists($HaniIMSPath . "Data.php")) {
        include($HaniIMSPath . "Data.php");
    }
    include("DatabaseInc.php");
    include("UpgradeInc.php");
    include('functions/DbGetFnc.php');
    #  Set Build Date and Version Number here.

    $b_date_sql = "select value from app where name='date'";
    $b_date_res = DBQuery($b_date_sql);
    $b_date_row = DBGet($b_date_res);

    $version_sql = "select value from app where name='version'";
    $version_res = DBQuery($version_sql);
    $version_row = DBGet($version_res);
    $HaniIMSVersion = $version_row[1]['VALUE'];
    $builddate = $b_date_row[1]['VALUE'];
    $htmldocPath = "";
    $OutputType = "HTML"; //options are HTML or PDF
    $htmldocPath = '';
    $htmldocAssetsPath = '';        // way htmldoc accesses the assets/ directory, possibly different than user - empty string means no translation
    //    $StudentPicturesPath = 'assets/studentphotos/';
    //    $UserPicturesPath = 'assets/userphotos/';
    $HaniIMSTitle = "HaniIMS Instutite Management System";
    $HaniIMSAdmins = '1';            // can be list such as '1,23,50' - note, these should be id's in the DefaultSyear, otherwise they can't login anyway
    $HaniIMSNotifyAddress = '';
    $msgFlag = '';

    $HaniIMSModules = array(

        // 'dashboard' => true,
        'institutesetup' => true,
        'students' => true,
        'users' => true,
        'scheduling' => true,
        'grades' => true,
        'attendance' => true,
        'eligibility' => true,
        'Discipline' => true,
        'Billing' => true,
        'EasyCom' => true,
        'Library' => true,
        'messaging' => true,
        'tools' => true,
    );

    // If session isn't started, start it.
    if (!isset($SessionStart))
        $SessionStart = 1;
}
