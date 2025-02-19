<?php

include('../../RedirectModulesInc.php');

$menu['students']['admin'] = array(
						'students/Student.php'=>_studentInfo,
						'students/Student.php&include=GeneralInfoInc&student_id=new'=>_addAStudent,
						'students/AssignOtherInfo.php'=>_groupAssignStudentInfo,
						
                                                'students/StudentReenroll.php'=>_studentReEnroll,
						1=>_reports,
						'students/AdvancedReport.php'=>_advancedReport,
						'students/AddDrop.php'=>_addDropReport,
						'students/Letters.php'=>_printLetters,
						'students/MailingLabels.php'=>_printMailingLabels,
						'students/StudentLabels.php'=>_printStudentLabels,
						'students/PrintStudentInfo.php'=>_printStudentInfo,
                        'students/PrintStudentContactInfo.php'=>_printStudentContactInfo,
                        'students/GoalReport.php'=>_printGoalsProgresses,
                        'students/EnrollmentReport.php'=>_studentEnrollmentReport,
						2=>_setup,
						'students/StudentFields.php'=>_studentFields,
						'students/EnrollmentCodes.php'=>_enrollmentCodes,
						
						3=>_fees,
						'students/ViewFees.php' => _viewfees,
						'students/transport.php'=>_transport,
						'students/AddFees.php' => _addfees,
						'students/PayFees.php' => _payfees,
						'students/Receipt.php' => _receipt,
					);

$menu['students']['teacher'] = array(
						'students/Student.php'=>_studentInfo,
						'students/AddUsers.php'=>_associatedParents,
						1=>_reports,
						'students/AdvancedReport.php'=>_advancedReport,
						'students/StudentLabels.php'=>_printStudentLabels,
					);

$menu['students']['parent'] = array(
						'students/Student.php'=>_studentInfo,
						'students/ChangePassword.php'=>_changePassword,
						'students/receipt.php'=>_receipt,
						'students/viewStuentParent.php' => _viewfees,
						'students/payfees.php' =>_payfees,

					);

$exceptions['students'] = array(
						'students/Student.php?include=GeneralInfoInc?student_id=new'=>true,
						'students/AssignOtherInfo.php'=>true,
						'students/receipt.php'=>true,
					
					);
?>
