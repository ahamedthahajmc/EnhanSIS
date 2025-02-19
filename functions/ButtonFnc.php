<?php


function button($type, $text = '', $link = '', $width = '', $extra = '', $buttonType = 'btn-primary') {

    $button_icons = array(
        "add" => "<i class=\"icon-plus3 " . $extra . "\"></i>",
        "bus" => "<i class=\"fa fa-bus " . $extra . "\"></i>",
        "comment" => "<i class=\"fa fa-commenting " . $extra . "\"></i>",
        "compass_rose" => "<i class=\"icon-compass4 " . $extra . "\"></i>",
        "down_phone" => "<i class=\"icon-phone " . $extra . "\"></i>",
        "phone" => "<i class=\"icon-phone " . $extra . "\"></i>",
        "edit" => "<i class=\"icon-pencil4 " . $extra . "\"></i>",
        "emergency" => "<i class=\"fa fa-plus-square " . $extra . "\"></i>",
        "gravel" => "<i class=\"fa fa- " . $extra . "\"></i>",
        "house" => "<i class=\"icon-home5 " . $extra . "\"></i>",
        "info" => "<i class=\"fa fa-info-circle " . $extra . "\"></i>",
        "mailbox" => "<i class=\"fa fa-envelope " . $extra . "\"></i>",
        "remove" => "<i class=\"icon-cross2 " . $extra . "\"></i>",
        "warning" => "<i class=\"fa fa-warning " . $extra . "\"></i>",
        "white_add" => "<i class=\"icon-plus3 text-pink " . $extra . "\"></i>"
    );
    $button = '';
    if ($type == 'dot') {
        $button = '<TABLE border=0 cellpadding=0 cellspacing=0 height=' . $width . ' width=' . $width . ' bgcolor=#' . $text . '><TR><TD>';
        $button .= '<IMG SRC=assets/dot.gif height=' . $width . ' width=' . $width . ' border=0 vspace=0 hspace=0>';
        $button .= '</TD></TR></TABLE>';
    } else {
        $button = '';
        if ($text) {
            if ($link) {
                $button .= '';
            } else {
                $button .= '<div class="btn ' . $buttonType . ' btn-icon btn-xs" ' . $extra . '>';
            }
        }
        if ($link) {
            if (strpos($link, 'onclick') !== false) {
                $onclick = $link;
                $href = 'href="#" ';
            } else {
                $onclick = 'onclick="grabA(this); return false;"';
                $href = 'href="' . $link . '"';
            }
            $button .= '<A class="btn ' . $buttonType . ' btn-icon btn-xs" ' . $href . ' ' . $extra . ' ' . $onclick . '>';
        }
        $button .= $button_icons[$type];
        if ($text) {
            $button .= ' '.$text;
        }
        if ($link) {
            $button .= '</A>';
        }
        if ($text) {
            if ($link) {
                $button .= '';
            } else {
                $button .= "</div>";
            }
        }
    }


    return $button;
}

function button_missing_atn($type, $text = '', $link = '', $cur_cp_id = '', $width = '', $extra='') {
    if ($type == 'dot') {
        $button = '<TABLE border=0 cellpadding=0 cellspacing=0 height=' . $width . ' width=' . $width . ' bgcolor=#' . $text . '><TR><TD>';
        $button .= '<IMG SRC=assets/dot.gif height=' . $width . ' width=' . $width . ' border=0 vspace=0 hspace=0>';
        $button .= '</TD></TR></TABLE>';
    } else {
        if ($text)
            $button = '<TABLE border=0 cellpadding=0 cellspacing=0 height=10><TR><TD>';
        if ($link)
            $button .= "<A HREF=" . $link . " onclick='grabA(this); return false;' onclick=>";


        if ($_SESSION['take_mssn_attn']) {
            $button .="<b><span onclick=javascript:document.getElementById('" . $cur_cp_id . "').selected='selected';><i class=\"icon-clipboard2\"></i> " . 'Take Attendance' . "</span></b>";
        } else {
            $button .="<b><i class=icon-clipboard2></i> Take Attendance</b>";
        }
        if ($link)
            $button .= '</A>';

        if ($text) {
            $button .= "</TD><TD valign=middle>&nbsp;";
            $button .= "<b>";
            if ($link)
                $button .= "&nbsp;<A HREF=" . $link . " onclick='grabA(this); return false;'>";
            $button .= $text;
            if ($link)
                $button .= '</A>';
            $button .= "</b>";
            $button .= "</TD>";
            $button .= "</TR></TABLE>";
        }
    }

    return $button;
}

?>
