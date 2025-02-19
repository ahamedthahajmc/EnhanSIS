<?php

function DrawPNG($src,$extra='')
{
	if(strpos($_SERVER['HTTP_USER_AGENT'],"MSIE 6") || strpos($_SERVER['HTTP_USER_AGENT'], "MSIE 5.5")) 
		$img .= "<img src=\"assets/pixel_trans.gif\" $extra style=\"filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='assets/".$src."');\" >";
	else
		$img .= '<img src="assets/'.$src.'" '.$extra.'>';
	
	return $img;
}
function DrawLogo()
{	                        
    
                        $sch_img_info= DBGet(DBQuery('SELECT * FROM user_file_upload WHERE INSTITUTE_ID='. UserInstitute().' AND FILE_INFO=\'schlogo\''));
                        if(!$_REQUEST['new_institute'] && count($sch_img_info)>0){
                            $image="<img src='data:image/jpeg;base64,".base64_encode($sch_img_info[1]['CONTENT'])."' width='60' style='float: left;' class='m-r-15 img-responsive' alt='Logo'/>";
//                        $logo_ret = DBGet(DBQuery('SELECT VALUE FROM program_config WHERE institute_id=\''.  UserInstitute().'\' AND program=\'InstituteLogo\''));    
//                        if($logo_ret && file_exists($logo_ret[1]['VALUE'])){
                            //$logo=$logo_ret[1]['VALUE'];
                            // $size = getimagesize($logo);
                            // $width=$size[0];
                            // $height=$size[1];
                            // $image='<img src="data:image/jpeg;base64,'.base64_encode($sch_img_info[1]['CONTENT']).'" width="60" style="float: left;" class="m-r-15" alt="Logo" />';
                        }
                         else {
                             $image= '<img src="assets/logo.png" width="60" style="float: left;" class="m-r-15" alt="Logo" />';
                        }

	return $image;
}
function DrawLogoReport()
{	                        
    
                        $sch_img_info= DBGet(DBQuery('SELECT * FROM user_file_upload WHERE INSTITUTE_ID='. UserInstitute().' AND FILE_INFO=\'schlogo\''));
                        if(!$_REQUEST['new_institute'] && count($sch_img_info)>0){
//                            $image="<img src='data:image/jpeg;base64,".base64_encode($sch_img_info[1]['CONTENT'])."' class=img-responsive />";
//                        $logo_ret = DBGet(DBQuery('SELECT VALUE FROM program_config WHERE institute_id=\''.  UserInstitute().'\' AND program=\'InstituteLogo\''));    
//                        if($logo_ret && file_exists($logo_ret[1]['VALUE'])){
                            $logo=$logo_ret[1]['VALUE'];
                            $size = getimagesize($logo);
                            $width=$size[0];
                            $height=$size[1];
                            $image='<img src="data:image/jpeg;base64,'.base64_encode($sch_img_info[1]['CONTENT']).'" width="60" style="float: left;" class="m-r-15" alt="Logo" />';
                        }
                         else {
                             $image= '<img src="assets/logo.png" width="60" style="float: left;" class="m-r-15" alt="Logo" />';
                        }

	return $image;
}
function DrawLogoParam($param='')
{	                        $logo_ret = DBGet(DBQuery('SELECT VALUE FROM program_config WHERE institute_id=\''.($param==''?UserInstitute():$param).'\' AND program=\'InstituteLogo\''));    
                        if($logo_ret && file_exists($logo_ret[1]['VALUE'])){
                            $logo=$logo_ret[1]['VALUE'];
                            $size = getimagesize($logo);
                            $width=$size[0];
                            $height=$size[1];
                            $image='<img src="'.$logo.'" '.($width>100 && $height>100?($width>$height?($height>100 || $width>100?' width=100':''):($height>100 || $width>100?' height=100':'')):'').' alt="Logo" />';
                        }
                         else {
                             $image= '<img src="assets/logo.png" alt="Logo" />';
                        }

	return $image;
}
?>