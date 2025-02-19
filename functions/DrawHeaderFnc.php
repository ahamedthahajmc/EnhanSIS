<?php


function DrawHeader($left = '', $right = '', $center = '') {
    global $_HaniIMS;
    if (!isset($_HaniIMS['DrawHeader']))
        $_HaniIMS['DrawHeader'] = '';

    if ($_HaniIMS['DrawHeader'] == '') {
        $attribute = 'b';
        $font_color = '';
    } else {
        $attribute = '';
        $font_color = '';
    }
    if ($left != '' || $right != '' || $center != '') {
        echo '<div class="panel-heading clearfix">';
    }
    
    if ($left && $left!='')
        echo '<h6 class="panel-title">' . $left . (($right!='')?'':'').'</h6>';
    if ($center && $center!='')
        echo '<h6 class="panel-title">' . $center . (($right!='')?'':'').'</h6>';
    if ($right && $right!='')
        echo '<div class="heading-elements clearfix">' . $right . '</div>';

    if ($left != '' || $right != '' || $center != '') {
        echo '</div>';
    }

    if ($_HaniIMS['DrawHeaderHome'] == '' && !$_REQUEST['HaniIMS_PDF'])
        $_HaniIMS['DrawHeaderHome'] = ' style="border:0;border-style: none none none none;"';
    else
        $_HaniIMS['DrawHeaderHome'] = '';
}

?>