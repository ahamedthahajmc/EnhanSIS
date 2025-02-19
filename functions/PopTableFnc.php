<?php


// DRAWS A TABLE WITH A BLUE TAB, SURROUNDING SHADOW
// REQUIRES A TITLE

function PopTable($action, $title = 'Search', $div_att = 'class="panel"', $cell_padding = '5')
{
    global $_HaniIMS;

                    echo "<script>
                    scrollToTop();
                function scrollToTop() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
                </script>";


    if ($action == 'header') {
        echo '<div ' . $div_att . '>';
        echo '<div class="tabbable">';

        if (is_array($title)) {
            echo WrapTabs($title, $_HaniIMS['selected_tab']);
        } else {
            echo '<ul class="nav nav-tabs nav-tabs-bottom no-margin-bottom">';
            echo DrawTab($title);
            echo '</ul>';
        }

        echo '<div class="panel-body">';
        echo '<div class="tab-content">';
        // Start content table.
    } elseif ($action == 'footer') {
        // End content table.
        echo '</div>'; //.tab-content
        echo '</div>'; //.panel-body
        if ($title != 'Search' && $title != '') {
            echo '<div class="panel-footer text-right p-r-20">';
            echo $title;
            echo '</div>'; //.panel-footer
        }
        echo '</div>'; //.tabbable
        echo '</div>'; //.panel
    }
}

function PopTable_wo_header($action, $title = '', $div_att = 'class="panel"', $header_content = '')
{
    global $_HaniIMS;
    echo "<script>
    scrollToTop();
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}
</script>";
echo "<script>
                    scrollToTop();
                function scrollToTop() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
                </script>";
    if ($action == 'header') {
        echo '<div ' . $div_att . '>';

        if ($title != '' || $header_content != '') {
            echo '<div class="panel-heading">';
            if ($title != '')
                echo '<h6 class="panel-title text-pink text-uppercase"><b>' . $title . '</b></h6>';
            if ($header_content != '')
                echo '<div class="heading-elements">' . $header_content . '</div>';
            echo '</div>';
        }

        // Start content table.
        echo '<div class="panel-body">';
        echo '<div class="tab-content">';
    } elseif ($action == 'footer') {
        // Close embeded table.
        echo '</div>'; //.tab-content
        echo '</div>'; //.panel-body
        echo '</div>'; //.panel
    }
}

function PopTable_wo_header_attn_code($action, $title = 'Search', $div_att = 'class="panel"', $cell_padding = '5')
{
    global $_HaniIMS;
    echo "<script>
    scrollToTop();
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}
</script>";
    if ($action == 'header') {
        echo '<div ' . $div_att . '>';
        echo '<div class="tabbable">';
        //echo '<ul class="nav nav-tabs nav-tabs-bottom no-margin-bottom">';
        if (is_array($title))
            echo WrapTabs($title, "Modules.php?modname=" . strip_tags(trim($_REQUEST[modname])) . "&table=" . strip_tags(trim($_REQUEST['table'])) . "");
        else
            echo '';
        //echo '</ul>';
        echo '<div class="panel-body">';
        echo '<div class="tab-content">';
    } elseif ($action == 'footer') {
        // Close embeded table.
        echo '</div>'; //.tab-content
        echo '</div>'; //.panel-body
        echo '</div>'; //.tabbable
        echo '</div>'; //.panel
    }
}

function PopTable_grade_header($action, $title = 'Search', $div_att = 'class="panel"', $cell_padding = '5')
{
    global $_HaniIMS;
    echo "<script>
    scrollToTop();
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}
</script>";
    if ($action == 'header') {
        echo '<div ' . $div_att . '>';

        if (is_array($title))
            echo '';
        else
            echo '';

        // Start content table.
        echo '<div class="panel-body">';
        echo '<div class="tab-content">';
    } elseif ($action == 'footer') {
        // Close embeded table.
        echo '</div>'; //.tab-content
        echo '</div>'; //.panel-body
        echo '</div>'; //.panel
    }
}

function PopTableMod($action, $title = 'Search', $table_att = '', $cell_padding = '0')
{
    global $_HaniIMS;
    echo "<script>
    scrollToTop();
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}
</script>";
    if ($action == 'header') {
        echo "
			<TABLE cellpadding=0 cellspacing=0 width=786px border=0 $table_att>";

        echo "<TR><TD width=786px>";

        echo "</TD></TR>
			<TR><TD>";

        // Start content table.
        echo "<TABLE cellpadding=" . $cell_padding . " cellspacing=0 ><tr><td >
		<div class=inside_block_top_closed></div>		
        <div class='content_block'>";
    } elseif ($action == 'footer') {
        // Close embeded table. 

        echo "</div><div class='content_bottom'></div>";
        echo "</td></tr></TABLE>";

        // 2nd cell is for shadow.....
        echo "</TD></TR></TABLE>";
    }
}

function PopTableWindow($action, $title = 'Search', $table_att = '', $cell_padding = '0')
{
    global $_HaniIMS;
    echo "<script>
    scrollToTop();
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}
</script>";
    if ($action == 'header') {
        echo "
			<TABLE align=left cellpadding=0 cellspacing=0 $table_att>";

        echo "<TR><TD >";
        if (is_array($title))
            echo WrapTabs($title, $_HaniIMS['selected_tab']);
        else
            echo DrawTab($title);
        echo "</TD></TR>
			<TR><TD>";

        // Start content table.
        echo "<TABLE cellpadding=" . $cell_padding . " cellspacing=0 ><tr><td width=10></td><td >
		<div class='inside_block_top'></div>
        <div class='content_block'>";
    } elseif ($action == 'footer') {
        // Close embeded table.

        echo "</div><div class='content_bottom'></div>";
        echo "</td></tr></TABLE>";

        // 2nd cell is for shadow.....
        echo "</TD></TR></TABLE>";
    }
}

function PopTableforWindow($action, $title = 'Search', $table_att = '', $cell_padding = '0')
{
    global $_HaniIMS;
    echo "<script>
    scrollToTop();
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}
</script>";
    if ($action == 'header') {
        echo "
			<TABLE align=left cellpadding=0 cellspacing=0 $table_att>";

        echo "<TR><TD >";
        if (is_array($title))
            echo WrapTabs($title, $_HaniIMS['selected_tab']);
        else
            echo "</TD></TR>
			<TR><TD>";

        // Start content table.
        echo "<TABLE cellpadding=" . $cell_padding . " cellspacing=0 ><tr><td width=10></td><td >
		<div class='inside_block_top'></div>
        <div class='content_block'>";
    } elseif ($action == 'footer') {
        // Close embeded table.

        echo "</div><div class='content_bottom'></div>";
        echo "</td></tr></TABLE>";

        // 2nd cell is for shadow.....
        echo "</TD></TR></TABLE>";
    }
}
