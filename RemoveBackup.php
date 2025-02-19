<?php 
 

function ListFiles($dir) {
    if($dh = opendir($dir)) {

        $files = Array();
        $inner_files = Array();
        while($file = readdir($dh)) {
            if($file != "." && $file != ".." && $file[0] != '.') {
                if(is_dir($dir . "/" . $file)) {
                    $inner_files = ListFiles($dir . "/" . $file);
                    if(is_array($inner_files)) $files = array_merge($files, $inner_files); 
                } else {
                    array_push($files, $dir . "/" . $file);
                }
            }
        }

        closedir($dh);
        return $files;
    }
}

foreach (ListFiles('.') as $key=>$file)
{
	$list=explode('/',$file);
	$count=count($list);
	$target=$list[$count-1];
	if(strpos($target,'.bak')==true)
	 {
	 	unlink($file);
	 }
    
	
}  
?>