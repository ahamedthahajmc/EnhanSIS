<?php

 

include('RedirectRootInc.php');
include 'Warehouse.php';
include 'Data.php';

session_start();

if ($_REQUEST['modfunc'] != 'remove') {
        
    $sql = 'SELECT FILTER_ID, FILTER_NAME,NULL AS REMOVE_LINK,NULL AS MODIFY_LINK FROM filters WHERE INSTITUTE_ID IN ('.UserInstitute().',0) AND SHOW_TO IN ('. UserID().',0)';
    $F = DBQuery($sql);

    $filters_RET = DBGet($F);
    
    $columns2 = array('REMOVE_LINK' => '','FILTER_NAME' => _filterName,'MODIFY_LINK' => '');

    $filter_counter=1;
    foreach($filters_RET as $key)
    {
        $filter_remove_link='<a class="btn btn-danger btn-icon btn-xs legitRipple" href="Modules.php?modname=students/StudentFilters.php&amp;modfunc=remove&amp;filter_id='.$key['FILTER_ID'].'" onclick="hide_filter_modal();"><i class="icon-cross2 "></i></a>';

        $filter_modify_link='<a class="btn btn-primary btn-xs display-inline-block" href="Modules.php?modname=students/StudentFilters.php&amp;modfunc=filter_edit&amp;filter_id='.$key['FILTER_ID'].'" onClick="hide_filter_modal()">'. _edit .'</a>';

        $filters_RET[$filter_counter]['REMOVE_LINK'] = $filter_remove_link; 
        $filters_RET[$filter_counter]['MODIFY_LINK'] = $filter_modify_link; 
        $filter_counter++;
    }

    ListOutput($filters_RET, $columns2, _filter, _filters, false, array(), array('search' => false, 'save' => false));
}

?>
