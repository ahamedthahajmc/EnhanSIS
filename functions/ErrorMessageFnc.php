<?php

// If there are missing vals or similar, show them a msg.
//
// Pass in an array with error messages and this will display them
// in a standard fashion.
//
// in a program you may have:
/*
  if(!$sch)
  $error[]="Institute not provided.";
  if($count == 0)
  $error[]="Number of students is zero.";
  ErrorMessage($error);
 */

// (note that array[], the brackets with nothing in them makes
// PHP automatically use the next index.
// Why use this?  It will tell the user if they have multiple errors
// without them having to re-run the program each time finding new
// problems.  Also, the error display will be standardized.
// If a 2ND is sent, the list will not be treated as errors, but shown anyway
function ErrorMessage($errors, $code = 'error', $options = '') {
    $errors = is_array($errors) ? array_unique($errors) : [];
    if ($errors) {
        if (count($errors) == 1) {
            if ($code == 'error' || $code == 'fatal' || $code == 'note')
                $return .= '<div class="alert alert-warning no-border" '.$options.'>';
            else
                $return .= '<div class="alert alert-danger no-border">';
            $return .= ($errors[0] ? $errors[0] : $errors[1]);
        }
        else {
            if ($code == 'error' || $code == 'fatal' || $code == 'note')
                $return .= '<div class="alert alert-warning no-border">';
            else
                $return .= '<div class="alert alert-danger no-border">';
            $return .= '<ul>';
            foreach ($errors as $value)
                $return .= "<li>$value</li>\n";
            $return .= '</ul>';
        }
        $return .= "</div>";

        if ($code == 'fatal') {
            $return .= '</div>';
            $return .= '</div>';
            $return .= '</div>';
            $return .= '</div>';
            $return .= '</div>';
            $return .= '</div>';
            $css = getCSS();
            if (User('PROFILE') != 'teacher') {
                $return .= '<div class="navbar footer">';
                $return .= '<div class="navbar-collapse" id="footer">';
                $return .= '<div class="row">';
                $return .= '<div class="col-md-9">';
                $return .= '<div class="navbar-text">';
                $return .= _footerText;
                $return .= '</div>';
                $return .= '</div>';
                $return .= '<div class="col-md-3">';
                $return .= '<div class="version-info">';
                $return .= 'Version <b>' . $get_app_details[1][VALUE] . '</b>';
                $return .= '</div>';
                $return .= '</div>';
                $return .= '</div>';
                $return .= '</div>';
                $return .= '</div>';
                // footer end
                $return .= '</body>';
                $return .= '</html>';
            }
            if ($isajax == "")
                echo $return;
            if (!$_REQUEST['HaniIMS_PDF'])
                Warehouse('footer');
            exit;
        }


        return $return;
    }
}

function ErrorMessage1($errors, $code = 'error') {

    if ($errors) {
        if (count($errors) == 1) {
            if ($code == 'error' || $code == 'fatal')
                $return .= '<div class="alert alert-warning no-border">';
            else
                $return .= '<div class="alert alert-danger no-border">';
            $return .= ($errors[0] ? $errors[0] : $errors[1]);
        }
        else {
            if ($code == 'error' || $code == 'fatal')
                $return .= '<div class="alert alert-warning no-border">';
            else
                $return .= '<div class="alert alert-danger no-border">';
            $return .= '<ul>';
            foreach ($errors as $value)
                $return .= "<li>$value</li>\n";
            $return .= '</ul>';
        }
        $return .= "</div>";

        if ($code == 'fatal') {
            $return .= '</div>';
            $return .= '</div>';
            $return .= '</div>';
            $return .= '</div>';
            $return .= '</div>';
            $return .= '</div>';
            $css = getCSS();
            $return .= '<div class="navbar footer">';
            $return .= '<div class="navbar-collapse" id="footer">';
            $return .= '<div class="row">';
            $return .= '<div class="col-md-9">';
            $return .= '<div class="navbar-text">';
            $return .= _footerText;
            $return .= '</div>';
            $return .= '</div>';
            $return .= '<div class="col-md-3">';
            $return .= '<div class="version-info">';
            $return .= 'Version <b>' . $get_app_details[1]['VALUE'] . '</b>';
            $return .= '</div>';
            $return .= '</div>';
            $return .= '</div>';
            $return .= '</div>';
            $return .= '</div>';
            // footer end
            $return .= '</body>';
            $return .= '</html>';
            if ($isajax == "")
                if (!$_REQUEST['HaniIMS_PDF'])
                    Warehouse('footer');
            exit;
        }

        return $return;
    }
}

?>
