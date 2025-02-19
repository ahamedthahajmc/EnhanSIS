<?php

// config variables for include/AddressInc.php
// set this to false to disable auto-pull-downs for the contact info Description field
include('../../RedirectModulesInc.php');
$info_apd = true;
// set this to false to disable mailing address display
$use_mailing = true;
// set this to false to disable bus pickoff/dropoff defaulting checked
$use_bus = true;
// set this to false to disable legacy contact info
$use_contact = true;
// these are the static items for the dynamic select lists in the format

$city_options = array('Kokomo'=>'Kokomo');
$state_options = array('IN'=>'IN');
$zip_options = array('46901'=>'46901','46902'=>'46902');

$relation_options = array('Father'=>_father,
'Mother'=>_mother,
'Step Mother'=>_stepMother,
'Step Father'=>_stepFather,
'Grandmother'=>_grandmother,
'Grandfather'=>_grandfather,
'Legal Guardian'=>_legalGuardian,
'Other Family Member'=>_otherFamilyMember,
);
if($info_apd)
	$info_options_x = array('Phone'=>_phone,
	'Cell Phone'=>_cellPhone,
	'Work Phone'=>_workPhone,
	'Employer'=>_employer,
);

?>