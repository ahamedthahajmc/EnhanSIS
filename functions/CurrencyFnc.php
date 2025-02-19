<?php


function Currency($num='', $sign='', $red = false) {

    $original = $num;
    if ($sign == 'before' && $num < 0) {
        $negative = true;
        $num *= -1;
    } elseif ($sign == 'CR' && $num < 0) {
        $cr = true;
        $num *= -1;
    }
    $current_RET = DBGet(DBQuery('SELECT TITLE,VALUE,PROGRAM FROM program_config WHERE PROGRAM=\'Currency\' AND SYEAR =\'' . UserSyear() . '\' AND INSTITUTE_ID =\'' . UserInstitute() . '\' '));
    $val = $current_RET[1]['VALUE'];

    switch ($val) {
        case '1':
            $sign = '$';
            break;
        case '2':
            $sign = '£';
            break;
        case '3':
            $sign = '€';
            break;
        case'4':
            $sign = 'C$';
            break;
        case '5':
            $sign = '$';
            break;
        case '6':
            $sign = 'R$';
            break;
        case '7':
            $sign = '¥';
            break;
        case '8':
            $sign = 'kr ';
            break;
        case '9':
            $sign = '¥ ';
            break;
        case '10':
            $sign = 'Rs';
            break;
        case '11':
            $sign = 'Rp';
            break;
        case '12':
            $sign = '₩';
            break;
        case '13' :
            $sign = 'RM';
            break;
        case '14':
            $sign = '$';
            break;
        case '15':
            $sign = '$';
            break;
        case '16':
            $sign = 'Kr';
            break;
        case '17':
            $sign = 'Rs';
            break;
        case '18':
            $sign = 'Php';
            break;
        case '19':
            $sign = 'Rs';
            break;
        case '20':
            $sign = 'SR';
            break;
        case '21':
            $sign = 'R';
            break;
        case '22':
            $sign = 'SR';
            break;
        case '23':
            $sign = 'S₣';
            break;
        case '24':
            $sign = '฿';
            break;
        case '25':
            $sign = '฿';
            break;
        case '26':
            $sign = '฿';
            break;
    }


    if ($sign == '') {
        $sign = '$';
    }
    $num = $sign . number_format($num, 2, '.', ',');
    if ($negative) {
        $num = '-' . $num;
    } elseif ($cr) {
        $num = $num . 'CR';
    }
    if ($red && $original < 0) {
        $num = '<span class="text-danger">' . $num . '</span>';
    }
    /*if (strpos($num, '-') == true) {
        $num = str_replace('-', '', $num);
        $num = '-' . $num;
    }*/
    return $num;
}

?>