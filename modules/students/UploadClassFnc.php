<?php

class upload{

var $target_path;
var $destination_path;
var $name;
var $fileExtension;
var $fileSize;
var $allowExtension=array("jpg","jpeg","png","gif","bmp");
var $wrongFormat=0;
var $wrongSize=0;
function deleteOldImage($id=''){
    if($id!='')
    {
        DBQuery('DELETE FROM user_file_upload WHERE ID='.$id);
}
//if(file_exists($this->target_path))
//	unlink($this->target_path);
}

function setFileExtension(){
$this->fileExtension=strtolower(substr($this->name,strrpos($this->name,".")+1));
}

function validateImageSize(){
if($this->fileSize > 10485760){
$this->wrongSize=1;
}
}

function validateImage(){
if(!in_array($this->fileExtension, $this->allowExtension)){
$this->wrongFormat=1;
}
}
function get_file_extension($file_name) {
return end(explode('.',$file_name));
}
}
?>