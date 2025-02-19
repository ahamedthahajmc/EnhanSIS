<?php

function DrawBC($bc='')
{	global $_HaniIMS;
	echo "<script>
    scrollToTop();
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}
</script>";
	// print_r($bc);
	echo "<script type='text/javascript'>document.getElementById('header').innerHTML = '".str_replace('>', ' <i class="icon-arrow-right5"></i> ', $bc)."';</script>";
}
?>