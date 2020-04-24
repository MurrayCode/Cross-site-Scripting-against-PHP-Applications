<?php
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko.
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//

include_once('config.inc.php');
include_once('includes/definitions.inc.php');
include 'includes/securimage/securimage.php';
session_name(GEKKO_SESSION_NAME);
$img = new securimage();
$img->session_name = GEKKO_SESSION_NAME;
$array_colors = array(new Securimage_Color(27,78,181), // blue
        new Securimage_Color(22,163,35), // green
        new Securimage_Color(214,36,7),
		new Securimage_Color('#FF4500'),
		new Securimage_Color('#8A2BE2')
		);  // red
$array_fonts = array('ddaft.ttf','shakethat.ttf','budhand.ttf');
$img->image_width = 200;
$img->image_height = 80;
$img->perturbation = 0;
$img->charset = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
$img->image_bg_color = new Securimage_Color("#FFFFFF");
$img->multi_text_color = array(new Securimage_Color("#3399ff"),
                               new Securimage_Color("#3300cc"),
                               new Securimage_Color("#3333cc"),
                               new Securimage_Color("#6666ff"),
                               new Securimage_Color("#99cccc")
                               );
$img->ttf_file = $img->securimage_path.'/'.$array_fonts[mt_rand (0,sizeof($array_fonts)-1)];							   
//$img->text_color = new Securimage_Color(array_rand ($array_colors));
$img->text_color  = $array_colors[mt_rand (0,sizeof($array_colors)-1)]	;						   
$img->use_multi_text = true;
$img->text_angle_minimum = -5;
$img->text_angle_maximum = 5;
$img->use_transparent_text = true;
$img->text_transparency_percentage = 10; // 100 = completely transparent
$img->num_lines = 2;
$img->line_color = new Securimage_Color("#eaeaea");
$img->use_transparent_text == true;
$img->use_wordlist = true; 
$img->image_type = securimage::SI_IMAGE_PNG;
//echo session_id();die;
$img->noise_level  = 0;
$img->show(/*SITE_PATH.'/images/default/captchabg.png'*/ ''); // alternate use:  $img->show('/path/to/background_image.jpg');
