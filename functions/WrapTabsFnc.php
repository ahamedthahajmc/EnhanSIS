<?php


function WrapTabs($tabs, $selected = '', $title = '', $use_blue = false, $type = '') {
    if ($color == '' || $color == '#FFFFFF')
        $color = "#FFFFCC";
    $row = 0;
    $characters = 0;
    if (count($tabs)) {
        $rows[$row] .= '<ul class="nav nav-tabs nav-tabs-bottom no-margin-bottom">';
        foreach ($tabs as $tab) {
            if (substr($tab['title'], 0, 1) != '<')
                $tab_len = strlen($tab['title']);
            else
                $tab_len = 0;

            if ($characters + $tab_len >= 1000) {
                $rows[$row] .= "";
                $row++;
                $rows[$row] .= "";
                $characters = 0;
            }

            if ($tab['link'] == PreparePHP_SELF() || $tab['link'] == $selected)
                $rows[$row] .= DrawTab($tab['title'], $tab['link'], '#333366', '#436477', $type);
            elseif ($use_blue !== true)
                $rows[$row] .= DrawinactiveTab($tab['title'], $tab['link'], '#DDDDDD', '#000000', $type);
            else
                $rows[$row] .= DrawinactiveTab($tab['title'], $tab['link'], '#333366', '#f2a30b', $type);

            $characters += $tab_len + 6;
        }
        $rows[$row] .= '</ul>';
    }

    $i = 0;
    $row_count = count($rows) - 1;

    for ($key = $row_count; $key >= 0; $key--) {
        if (!preg_match("<!--BOTTOM-->", $rows[$key])) {
            if ($key != 0 || $bottom)
                $table .= $rows[$key];
            else
                $table .= $rows[$key];
            $i++;
        } else
            $bottom = $key;
    }
    $table .= $rows[$bottom];

    if ($title != '')
        $table .= $title;

    return $table;
}

?>