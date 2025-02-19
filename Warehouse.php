<?php

 
if (!defined('WAREHOUSE_PHP')) {
    define("WAREHOUSE_PHP", 1);
    $staticpath = dirname(__FILE__) . '/';

    require_once($staticpath . "ConfigInc.php");
    require_once("DatabaseInc.php");

    //Create Default Year Picture Folder
    // if (!file_exists($StudentPicturesPath)) {
    //     mkdir($StudentPicturesPath);
    // }

    // Load functions.
    if ($handle = opendir("$HaniIMSPath/functions")) {
        if (!is_array($IgnoreFiles))
            $IgnoreFiles = Array();

        while (false !== ($file = readdir($handle))) {
            // if filename isn't '.' '..' or in the Ignore list... load it.
            if ($file != "." && $file != ".." && !in_array($file, $IgnoreFiles)) {
                if (file_exists("$HaniIMSPath/functions/$file"))
                    require_once("$HaniIMSPath/functions/$file");
            }
        }
    }

    // Start Session.
    session_start();

    if (!$_SESSION['STAFF_ID'] && !$_SESSION['STUDENT_ID'] && strpos($_SERVER['PHP_SELF'], 'index.php') === false) {
        header('Location: index.php');
        exit;
    }

    function Warehouse($mode, $extra = '') {
        global $__SHOW_FOOTER, $_HaniIMS;

        switch ($mode) {
            case 'header':
                echo "<!DOCTYPE html><html lang=\"en\" ".((langDirection()=='rtl')?'dir="rtl"':'dir="ltr')."><head><meta charset=\"utf-8\"><meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"><meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no\">";
                echo "<title>" . Config('TITLE') . "</title><link rel=\"shortcut icon\" href=\"assets/images/Plugin_icon-21.svg\">";
                //echo '<link href="assets/css/export_print.css" rel="stylesheet" type="text/css">';

                if (basename($_SERVER['PHP_SELF']) != 'index.php' && basename($_SERVER['PHP_SELF']) != 'Ajax.php')
                    echo "<noscript><meta http-equiv=REFRESH content='0;url=index.php?modfunc=logout&reason=javascript' /></noscript>";
                if (basename($_SERVER['PHP_SELF']) == 'index.php')
                    echo "<noscript><meta http-equiv=REFRESH content='0;url=EnableJavascript.php' /></noscript>";

                echo $extra;
                echo "<script language=\"JavaScript\" type=\"text/javascript\">";
                if (basename($_SERVER['PHP_SELF']) == 'index.php')
                    echo "if(parent.frames.length > 0){ parent.location.href = 'index.php?modfunc=logout'; }";
                echo "function newLoad(){ }


							var locked;						

							function putFocus()
							{
								if(document.forms.length > 0)
								{
									document.forms[0].elements[0].focus();
								}
							}

							function addHTML(html,id,replace)
							{
								if(locked!=false)
								{
									if(replace==true)
										document.getElementById(id).innerHTML = html;
									else
										document.getElementById(id).innerHTML = document.getElementById(id).innerHTML + html;
								}
							}

							function changeHTML(show,hide)
							{
								for(key in show)
									document.getElementById(key).innerHTML = document.getElementById(show[key]).innerHTML;
								for(i=0;i<hide.length;i++)
									document.getElementById(hide[i]).innerHTML = '';
							}

                                                        function checkAll(form,value,name_like)
							{
								if(value==true)
									checked = true;
								else
									checked = false;

								for(i=0;i<form.elements.length;i++)
								{
									if(form.elements[i].type=='checkbox' && form.elements[i].name!='controller' && form.elements[i].name.substr(0,name_like.length)==name_like)
										form.elements[i].checked = checked;
								}
							}
						</script>";

//					<link rel='stylesheet' type='text/css' href='styles/Help.css'>";
//                echo "<link rel=stylesheet type=text/css href=styles/Calendar.css>";
                echo "</head>";

                break;
            case "footer":
                echo '</td></tr></table>';

                for ($i = 1; $i <= $_HaniIMS['PrepareDate']; $i++) {
                    echo '<script type="text/javascript">
				Calendar.setup({
					monthField     :    "monthSelect' . $i . '",
					dayField       :    "daySelect' . $i . '",
					yearField      :    "yearSelect' . $i . '",
					ifFormat       :    "%d-%b-%y",
					button         :    "trigger' . $i . '",
					align          :    "Tl",
					singleClick    :    true
				});
			</script>';
                }
                echo '</body>';
                echo '</html>';
                break;
        }
    }

}

/*
							function checkAll(form,value,name_like)
							{
                                                       
								if(value==true)
									checked = true;
								else
									checked = false;
                                                                
                                                                var arr_len=document.getElementById('res_len').value;
                                                                
                                                                var count = document.getElementById('res_length').value;                                                      

                                                                var stu_list=document.getElementById('res_len').value;

                                                                var  res_list = stu_list.split(',');
                                                                
                                                                for(i=0;i<count;i++)
                                                                {
                                                               
                                                                    var check_id = res_list[i];
                               
                                                                    console.log(check_id);
                                                                    
                                                                    document.getElementById(check_id).checked = true;
                                                                }
                             
							} 

 */
