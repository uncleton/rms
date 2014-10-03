<?php

#http://rodomontano.altervista.org/engcaptcha.php

header('Content-Type: image/gif');                                                                                                                                                  
header('Cache-control: no-cache, no-store'); 

# Config


$height = 40;                                                                                                                                                                   
/* Image height (pixels). Width and number of frames are automatically set
*/
   
   
$delay = 8;
/* delay  = Time of visualization of each frame 1/100 sec.
    delay = 4 hundreth of second means 25 fps (frames per second) High values = slow animation. 
    Firefox can correctly visualize 25 fps. Internet Explorer cannot visualize more than 12 fps (delay = 8). 
     */

$font = 'ariali.ttf';
/* default font, You can change, bat it shoul be not correctly visulized
   */

$anim_mode = 1;
/* anim_mode 
  0 pulsing
  1 rolling balls
  2 sliding sum
  3 random animation 
   */

# Don't change anything below here unless you know what you're doing

############################################################

  if ($anim_mode == 3) {$anim_mode = rand(0,2);}

$Characters = "23456789abcdefghkmnpqrstuvwxyz";


//  // Set  font size
$fontsize = $height * 0.7;	
$tot_frames = $height;

srand((double)microtime()*1000000); 
$string = substr($Characters,mt_rand(0,strlen($Characters) - 1),1);//rand(1,9)*100; //Genero il primo numero
$string2=substr($Characters,mt_rand(0,strlen($Characters) - 1),1);//rand(1,9)*10; //Genero il secondo numero
$string4=substr($Characters,mt_rand(0,strlen($Characters) - 1),1);//rand(1,9); //Genero il terzonumero
$string5=substr($Characters,mt_rand(0,strlen($Characters) - 1),1);
$string6=substr($Characters,mt_rand(0,strlen($Characters) - 1),1);
$string7=substr($Characters,mt_rand(0,strlen($Characters) - 1),1);
$string3="$string $string2 $string4 $string5 $string6 $string7";
$somma = $string3;
$somma2 = "$string3 = $string3";
$pass="$string$string2$string4$string5$string6$string7";



// Set image width
$textbox = imagettfbbox($fontsize, 0, $font, $somma);
if ($anim_mode < 2)

 { $width = (abs($textbox[4] - $textbox[0]))*1.1;
    }
    else {
    $width = (abs($textbox[4] - $textbox[0]))*0.8;
    }

$x = $width*0.035;
$y = $height - $height/4;


$anim_len = 10;
$start_dummy = rand(0,10);                                                                                                                                                          
$end_dummy = rand($start_dummy+$anim_len,30); 



$files = array(); 

function get_gif_header($gif_data) {                                                                                                                                                
    $header = array();                                                                                                                                                              
    $header["signature"] = substr($gif_data,0,3);                                                                                                                                  
    $header["version"]   = substr($gif_data,3,3);                                                                                                                                  
    $header["logical_screen_width"]  = substr($gif_data,6,2);                                                                                                                      
    $header["logical_screen_height"] = substr($gif_data,8,2);                                                                                                                      
    $header["packed"] = substr($gif_data,10,1);                                                                                                                                    
    $header["background_color_index"] = substr($gif_data,11,1);                                                                                                                    
    $header["pixel_aspect_ratio"] = substr($gif_data,12,1);                                                                                                                        
    $packed = ord($header["packed"]);                                                                                                                                              
    if (($packed >> 7) & 0x1) {                                                                                                                                                    
        $gct = $packed & 3;                                                                                                                                                        
        $gct_size = 3 * pow(2,$gct+1);                                                                                                                                              
        $header["global_color_table"] = substr($gif_data,13,$gct_size);                                                                                                            
    }                                                                                                                                                                              
    return $header;                                                                                                                                                                
}        

function strip_gif_header($gif_data) {                                                                                                                                              
    $without_header = "";                                                                                                                                                          
    $header_len = 0;                                                                                                                                                                
    $header = get_gif_header($gif_data);                                                                                                                                            
    foreach ($header as $k=>$v)                                                                                                                                                    
        $header_len += strlen($v);                                                                                                                                                  
    return substr($gif_data,$header_len,strlen($gif_data)-$header_len);                                                                                                            
}        

function get_gif_image_data($gif_data) {                                                                                                                                            
    $no_header = strip_gif_header($gif_data);                                                                                                                                      
    $no_header = substr($no_header,0,strlen($no_header)-1);                                                                                                                        
    return $no_header;                                                                                                                                                              
}      
function get_gif_image_descriptor($image_data) {                                                                                                                                    
    $header = array();                                                                                                                                                              
    $header["image_separator"] = substr($image_data,0,1);                                                                                                                          
    $header["image_left_position"]  = substr($image_data,1,2);                                                                                                                      
    $header["image_top_position"] = substr($image_data,3,2);                                                                                                                        
    $header["image_width"]  = substr($image_data,5,2);                                                                                                                              
    $header["image_height"] = substr($image_data,7,2);                                                                                                                              
    $header["packed"] = substr($image_data,9,1);                                                                                                                                    
    $packed = ord($header["packed"]);                                                                                                                                              
    if (($packed >> 7) & 0x1) {                                                                                                                                                    
        $lct = $packed & 3;                                                                                                                                                        
        $lct_size = 3 * pow(2,$lct+1);                                                                                                                                              
        $header["local_color_table"] = substr($image_data,10,$lct_size);                                                                                                            
    }                                                                                                                                                                              
    return $header;                                                                                                                                                                
}    
function strip_gif_image_descriptor($imgdata) {                                                                                                                                    
    $descriptor = get_gif_image_descriptor($imgdata);                                                                                                                              
    $len = 0;                                                                                                                                                                      
    foreach ($descriptor as $k=>$v)                                                                                                                                                
        $len += strlen($v);                                                                                                                                                        
    return substr($imgdata,$len,strlen($imgdata)-$len);                                                                                                                            
}      
function make_gifanim($gifs) {
    global $delay;                                                                                                                                                  
    $head0 = get_gif_header($gifs[0]);                                                                                                                                              
    $head0["packed"] = chr( ord($head0["packed"]) & (7 << 4) );                                                                                                                    
    $head0["background_color_index"] = chr(0);                                                                                                                                      
    $head0["pixel_aspect_ratio"] = chr(0);                                                                                                                                          
    unset($head0["global_color_table"]);                                                                                                                                            
    $anim_gif = implode("",$head0);                                                                                                                                                
    $extra_info = array( chr(0x21), chr(0xff) ,chr(0x0B), "NETSCAPE2.0",chr(0x03), chr(0x01), chr(0x00).chr(0x00), chr(0x00) );                                                    
    $anim_gif .= implode("",$extra_info);                                                                                                                                          
    foreach ($gifs as $gif) {                                                                                                                                                      
        $header = get_gif_header($gif);                                                                                                                                            
        $imgdata = get_gif_image_data($gif);                                                                                                                                        
        $image_header = get_gif_image_descriptor($imgdata);                                                                                                                        
        $image_only = strip_gif_image_descriptor($imgdata);                                                                                                                        
        $control_block = array();                                                                                                                                                  
        $control_block["extension_introducer"] = chr(0x21);                                                                                                                        
        $control_block["graphic_control_label"]  = chr(0xF9);                                                                                                                      
        $control_block["block_size"] = chr(4);                                                                                                                                      
        $control_block["packed"] = chr(0);                                                                                                                                          
        $control_block["delay"] = chr($delay).chr(0);                                                                                                                                 
        $control_block["transparent_color_index"] = chr(0);                                                                                                                        
        $control_block["terminator"] = chr(0);                                                                                                                                      
        if (!isset($image_header["local_color_table"]) && isset($header["global_color_table"])) {                                                                                  
            $image_header["local_color_table"] = $header["global_color_table"];                                                                                                    
            $size_gct = (ord($header["packed"]) & 3);                                                                                                                              
            $image_header["packed"] = chr( ord($image_header["packed"]) | (0x1 << 7) | ($size_gct) );                                                                              
        }                                                                                                                                                                          
        $anim_gif .= implode("",$control_block).implode("",$image_header).$image_only;                                                                                              
    }                                                                                                                                                                              
    $anim_gif .= chr(0);                                                                                                                                                            
    return $anim_gif;                                                                                                                                                              
}  

$cur2_x = $tot_frames*2;

for ($f=0;$f<$tot_frames;$f++) {  
    $im = imagecreate($width, $height);  
        
    // Some colors
$white = imagecolorallocate($im, 255, 255, 255);
$gray = imagecolorallocate($im, 238, 238, 238);
$gray2 = imagecolorallocate($im, 200, 200, 200);
$black = imagecolorallocate($im, 0, 0, 0);
$red = imagecolorallocate($im, 255, 0, 0);
$green = imagecolorallocate($im, 0, 255, 0);
$blu = imagecolorallocate($im, 0, 0, 255);
$lightblu = imagecolorallocate($im, 221, 231, 244);

$custom = imagecolorallocate($im, 200, 200, 150);

   
// Backcgroud color - You can change it
$colore_sfondo =  $custom;

// Textcolor - You can change it
$colore_testo =  $black;

// Pulsing animation color - You can change it
$colore_anim1 =  $red;

// Rolling animation color - You can change it
$colore_anim2 =  $custom;                                                                                                                                 

    
    imagefill($im, 0, 0, $colore_sfondo);                                                                                                                                                                      
                                                                                                                                                                        
    if ($f>$start_dummy && $f<$end_dummy)                                                                                                                                          
    
    imagefill($im, 0, 0, $colore_sfondo);
   
     
     
     
     
     if ($anim_mode == 0) {
     imagettftext($im,$fontsize,0,$x,$y,$colore_testo,$font,$somma);
      if ($cur_x<$tot_frames){
   imagefilledrectangle($im,-2,0,$cur_x-2,$height,$colore_anim1);
    imagefilledrectangle($im,$cur_x+($width-$tot_frames+1),0,$width+2,$width,$colore_anim1);
    }
    else {
    imagefilledrectangle($im,-2,0,$cur2_x-2,$height,$colore_anim1);
    imagefilledrectangle($im,$cur2_x+($width-$tot_frames+1),0,$width+2,$width,$colore_anim1);
    }
    
     
    
    $cur_x = $cur_x + 2;                                                                                                                                                                              
    $cur2_x = $cur2_x - 2;
     
	 
} else {  if ($anim_mode == 1) {

imagettftext($im,$fontsize,0,$x,$y,$colore_testo,$font,$somma);
imagefilledellipse($im, $cur3_x, $height/2, $height, $height, $colore_anim2);
//  imagefilledrectangle($im, $cur3_x, 0, $height+40, $height, $colore_anim2);
//   imagefilledellipse($im, $width+$cur3_x, $height/2, $height, $height, $colore_anim2);
//  imagefilledrectangle($im, $cur3_x+0, 0, $height+40, $height, $colore_anim2);  
   
    $cur3_x = $cur3_x + ($width/$height); 


}	else {

imagettftext($im,$fontsize,0,$x-$cur_x,$y,$colore_testo,$font,$somma2);

        
    $cur_x = $cur_x + ($width/$height)/0.64;
}		 	 
}
      
    
    ob_start();                                                                                                                                                                    
    imagegif($im);                                                                                                                                                                  
    $files[] = ob_get_clean();                                                                                                                                                      
    imagedestroy($im);                                                                                                                                                              
}                                                                                                                                                                                  
                                                                                                                                                                                    
echo make_gifanim($files);  
session_start();
$_SESSION['code'] = $pass

?>
