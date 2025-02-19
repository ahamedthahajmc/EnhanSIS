<?php

function MailingLabel($address_id)
{	global $THIS_RET,$_HaniIMS;
	$student_id = $THIS_RET['STUDENT_ID'];
	if($address_id && !$_HaniIMS['MailingLabel'][$address_id][$student_id])
	{

            
            $p_sql='SELECT sa.ID AS ADDRESS_ID,p.STAFF_ID AS PERSON_ID,
                    coalesce((SELECT STREET_ADDRESS_1 FROM student_address WHERE STUDENT_ID=sa.STUDENT_ID AND TYPE =\'MAIL\'),sa.STREET_ADDRESS_1) AS ADDRESS,
                    coalesce((SELECT CITY FROM student_address WHERE STUDENT_ID=sa.STUDENT_ID AND TYPE =\'MAIL\'),sa.CITY) AS CITY,
                    coalesce((SELECT STATE FROM student_address WHERE STUDENT_ID=sa.STUDENT_ID AND TYPE =\'MAIL\'),sa.STATE) AS STATE,
                    coalesce((SELECT ZIPCODE FROM student_address WHERE STUDENT_ID=sa.STUDENT_ID AND TYPE =\'MAIL\'),sa.ZIPCODE) AS ZIPCODE,
                    s.PHONE,p.LAST_NAME,p.FIRST_NAME,p.MIDDLE_NAME
                    FROM student_address sa,people p,students s
                    WHERE p.STAFF_ID=sa.PEOPLE_ID AND s.STUDENT_ID=sa.STUDENT_ID AND sa.STUDENT_ID=\''.$student_id.'\' AND p.CUSTODY=\'Y\'';
            $people_RET = DBGet(DBQuery($p_sql),array(),array('LAST_NAME'));

		if(count($people_RET))
		{
			foreach($people_RET as $last_name=>$people)
			{
				for($i=1;$i<count($people);$i++)
					$return .= $people[$i]['FIRST_NAME'].' &amp; ';
				$return .= $people[$i]['FIRST_NAME'].' '.$people[$i]['LAST_NAME'].'<BR>';
			}
			// mab - this is a bit of a kludge but insert an html comment so people and address can be split later
			$return .= '<!-- -->'.$people[$i]['ADDRESS'].'<BR>'.$people[$i]['CITY'].', '.$people[$i]['STATE'].' '.$people[$i]['ZIPCODE'];
		}


		$_HaniIMS['MailingLabel'][$address_id][$student_id] = $return;
	}

	return $_HaniIMS['MailingLabel'][$address_id][$student_id];
}
?>