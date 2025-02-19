<?php
 
include('../../RedirectModulesInc.php');
$menu['messaging']['admin'] = array(
						'messaging/Inbox.php'=>_inbox,
						'messaging/Compose.php'=>_compose,
						'messaging/SentMail.php'=>_sentMessage,
						'messaging/Trash.php'=>_trash,

												'messaging/Group.php'=>_groups,
											);

$menu['messaging']['teacher'] = array(
						'messaging/Inbox.php'=>_inbox,
						'messaging/Compose.php'=>_compose,
						'messaging/SentMail.php'=>_sentMessage,
						'messaging/Trash.php'=>_trash,

												'messaging/Group.php'=>_groups,
											);

$menu['messaging']['parent'] = array(
						'messaging/Inbox.php'=>_inbox,
						'messaging/Compose.php'=>_compose,
						'messaging/SentMail.php'=>_sentMessage,
						'messaging/Trash.php'=>_trash,

												'messaging/Group.php'=>_groups,
											);
$menu['messaging']['student'] = array(
						'messaging/Inbox.php'=>_inbox,
						'messaging/Compose.php'=>_compose,
						'messaging/SentMail.php'=>_sentMessage,
						'messaging/Trash.php'=>_trash,

												'messaging/Group.php'=>_groups,
											);


?>
