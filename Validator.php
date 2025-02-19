<?php
 
include 'RedirectRootInc.php';
include 'ConfigInc.php';
include 'Warehouse.php';
$flag = $_GET['u'];
$usr = substr($flag, -4);
$userid = $_GET['userid'];
$profileid = $_GET['profileid'];

// ------------------------ For Unique Checking ---------------------------------- //
$un = substr($flag, 0, -4);
$un = strtoupper($un);
// ------------------------ For Unique Checking ---------------------------------- //

switch ($_GET['validate'])
{
    case 'pass':
		$res_pass_chk = DBQuery("SELECT * FROM login_authentication WHERE password = '".md5($_GET['password'])."' AND user_id!='".$_GET['stfid']."' AND profile_id=0");
		$num_pass = $res_pass_chk->num_rows;
        if($num_pass==0)
        {
            echo 1;
            
        }
        break;
        
	case 'pass_o':
        $res_pass_chk = DBQuery("SELECT * FROM login_authentication WHERE password = '".md5($_GET['password'])."'  AND profile_id=0");
        $num_pass = $res_pass_chk->num_rows;
        if($num_pass==0)
        {
            echo '1_'.$_GET['opt'];
            
        }
        else
        {
            echo '0_'.$_GET['opt'];
        }
		break;
        
	default :    
		if($usr == 'user')
		{
			if (trim($userid) != '')
				$result = DBGet(DBQuery("SELECT username FROM login_authentication WHERE NOT(user_id = '" . $userid . "' AND profile_id = '".$profileid."')"));
			else
				$result = DBGet(DBQuery("SELECT username FROM login_authentication"));
			 
			$xyz = 0;
			foreach ($result as $k => $v) 
			{
			  	$unames[$xyz] = strtoupper($v['USERNAME']); // For Unique Checking.
			  	$xyz++;
			}
		
			if ($un != '') 
			{
				if (in_array ($un, $unames)) 
				{
					echo '0';
				} 
				else 
				{
					echo '1';
				}

				exit;
			}
		}
		else
		{
			if (trim($userid) != '')
				$result = DBGet(DBQuery("SELECT username FROM login_authentication WHERE NOT(user_id = '" . $userid . "' AND profile_id = '".$profileid."')"));
			else
				$result = DBGet(DBQuery("SELECT username FROM login_authentication"));
			
			$xyz = 0;
			foreach ($result as $k => $v) 
			{
			  	$unames[$xyz] = strtoupper($v['USERNAME']); // For Unique Checking.
			  	$xyz++;
			}
		
			if ($un != '') 
			{
				if(is_array($unames))
				{
					if (in_array ($un, $unames)) 
					{
						echo '0';
					} 
					else 
					{
						echo '1';
					}
				} else {
					echo '1';
				}

				exit;
			}
		}
		break;
}

?>
