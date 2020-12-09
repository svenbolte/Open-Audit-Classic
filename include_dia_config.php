<?php
//
/**
*
* @version $Id: include_dia_config.php  17th July 2007
*
* @author The Open Audit Developer Team (Andrew Hull)
* @objective Configuration File for DIA Diagram Creator Page for Open Audit.
* @package open-audit (www.open-audit.org)
* @copyright Copyright (C) open-audit.org All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see ../gpl.txt
* Open-Audit is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See www.open-audit.org for further copyright notices and details.
*
*/ 
//

// Note  Some objects are specified as for example 4.0 rather than 4 in order to ensure that PHP uses the correct type 
// i.e. A Number rather than a text string. 

// $dia_image_folder
// Set this to a valid location for your *** Workstation*** and NOT the OA server as it will be the Workstation that open the diagram!
//
// Try $dia_image_folder=".\\images" then place a copy of the images folder in the same folder as the diagram, or 
//
// Try \\home\\myuser\\mydiagrams or similar on Linix
//
// or for multiple Windows Workstations set to a shared location for example 
//
//$dia_image_folder='W:\\htdocs\\openaudit\\images\\';
//
// For Xampp standard installation path, try 
//
$dia_image_folder='C:\\Program Files (x86)\\xampplite\\htdocs\\openaudit\\images\\';
//

// Start offset x/y for first object
$dia_object_start_x = 0.5;
$dia_object_start_y = 0.5;

// Start Object id best left as zero 
// settype($dia_object_start_id,int);
$dia_object_start_id = 0;

// Object Spacing incriments (set one or other of x/y to a positive value, to space out horizontaly or vertically)
$dia_object_spacing_x=4;
$dia_object_spacing_y=2.4;
//$dia_object_spacing_y=2;
// Config for starting point for first object
$dia_object_num_columns=1;
$dia_newline= "\n";
//
$dia_text_other_object_text= '"$myrow[$field[\"name\"]].\"\n\".$myrow[\"other_ip_address\"].\"\n\".$myrow[\"other_description\"]\"';



// Page Setup next... (FIXME this should probably be an array (AJH))
//
$dia_background_name="background";
$dia_background_colour="#FFFFFF";
// 
$dia_pagebreak_colour="#000099";
$dia_paper="paper";
$dia_paper_name="#A4#";
$dia_tmargin="2.8";
$dia_bmargin="2.8";
$dia_lmargin="2.8";
$dia_rmargin="2.8";
$dia_is_portrait="false";

//
// Set up page layout for spread of items across and down page.
if ($dia_is_portrait == "true") {
        $dia_num_across_page= 4.0;
        $dia_num_down_page= 6.0;
        } else {
        $dia_num_across_page= 6.0;
        $dia_num_down_page= 4.0;
        }
// 
$dia_grouped_objects= 3.0;


$dia_scaling="1";
$dia_fitto="false";
$dia_grid="grid";
$dia_grid_type="grid";
$dia_grid_width_x="1";
$dia_grid_width_y="1";
$dia_grid_visible_x="1";
$dia_grid_visible_y="1";
$dia_grid_lines_colour="#D8E5E5";
$dia_guides="guides";
$dia_guides_hguides="hguides";
$dia_guides_vguides="vguides";
$dia_background_name="Background";
$dia_background_visible="true";
// End of Page Setup Settings

//Config for Image 0 Equipmnet Objects (FIXME this should be an array (AJH))
//
$dia_obj_image_0_type="Standard - Image";
$dia_obj_image_0_version="0";
// Set an ID, this will be controlled programatically, as each element is created.
// *** Caution *** The Object ID Starts with a capital "O" and not a Zero so the following line is OHH ZERO 
// (I spent ages trying to track this foible down (AJH))
$dia_obj_image_0_id="O0";
//
$dia_obj_image_default_image="laptop_l.png";
// Position (this will also be controlled programatically, so these are just the defaults)
$dia_obj_image_0_pos_x=0.3;
$dia_obj_image_0_pos_y=1.3;
// Blob Size 
$dia_obj_image_0_bb_x1=0.9;
$dia_obj_image_0_bb_y1=1.05;
$dia_obj_image_0_bb_x2=3.0;
$dia_obj_image_0_bb_y2=2.9;
// Corners
$dia_obj_image_0_elem_corner_x=0.95;
$dia_obj_image_0_elem_corner_y=1.1;
// Width & Height
$dia_obj_image_0_elem_width=0.5;
$dia_obj_image_0_elem_height=0.5;
// Properties
$dia_obj_image_0_draw_border="false";
$dia_obj_image_0_keep_aspect="true";
// End of Image  0 Settings

//Config for Text Element 0 Label under device
//
$dia_obj_text_0_type="Standard - Text";
$dia_obj_text_0_version="1";
// *** Caution *** The Object ID Starts with a capital "O" (for Object presumably) and not a Zero so the following line is OHH ONE and not Zero One 
// (I spent ages trying to track this foible down (AJH))
$dia_obj_text_0_id="O1";
//
$dia_obj_text_0_pos_x=0.85;
$dia_obj_text_0_pos_y=3.85;
//
$dia_obj_text_0_pos_x_offset=0.2;
$dia_obj_text_0_pos_y_offset=0.8;
//
$dia_obj_text_0_bb_x1=0.85;
$dia_obj_text_0_bb_y1=3.20;
$dia_obj_text_0_bb_x2=4.89;
$dia_obj_text_0_bb_y2=4.29;
//
$dia_obj_text_0_text="text";
$dia_obj_text_0_string="#DEFAULT #";
$dia_obj_text_0_font="font";
$dia_obj_text_0_font_family="arial";
$dia_obj_text_0_font_style=0;
$dia_obj_text_0_font_name="Helvitica";
$dia_obj_text_0_font_height=0.15;
// 
$dia_obj_text_0_font_pos="pos";
$dia_obj_text_0_font_pos_x=0.85;
$dia_obj_text_0_font_pos_y=0.85;
//
$dia_obj_text_0_font_colour="#000000";
//
$dia_obj_text_0_font_alignment=1;
$dia_obj_text_0_font_valign="3";
// End Config Text Element 0

// What system text fields do we show, 
$dia_show_system_net_ip_address = "y";
$dia_show_system_net_user_name = "y";
$dia_show_system_net_domain = "y";
$dia_show_system_system_vendor = "y";
$dia_show_system_system_model = "y";
$dia_show_system_system_id_number = "y"; 
$dia_show_system_system_memory = "y";
// What other item  text fields do we show, 
$dia_show_other_network_name = "n";
$dia_show_other_ip_address = "y";
$dia_show_other_mac_address = "y";
$dia_show_other_description = "n";
$dia_show_other_serial = "n";
$dia_show_other_model = "y";
$dia_show_other_type = "n";
$dia_show_other_location = "y";
$dia_show_other_p_port_name = "y";
$dia_show_other_p_share_name = "y";


// Config for Line Element 0 Zig Zag Line Connector
//
$dia_obj_line_0_type="Standard - ZigZagLine";
$dia_obj_line_0_version="1";
//
// *** Caution *** Remember the Object ID Starts with a capital "O" for Object and not a Zero so the following line is OHH TWO 
// 
$dia_obj_line_0_id="O2";
// Start
$dia_obj_line_0_pos_x=3.05;
$dia_obj_line_0_pos_y=1.97;
// Blob (Box)
$dia_obj_line_0_bb_x1=3.0;
$dia_obj_line_0_bb_y1=1.475;
$dia_obj_line_0_bb_x2=6.95;
$dia_obj_line_0_bb_y2=3.45;
//
// Orth Points
$dia_obj_line_0_orth_points_x1=0.70;
$dia_obj_line_0_orth_points_y1=0.45;
$dia_obj_line_0_orth_points_x2=1.1;
$dia_obj_line_0_orth_points_y2=0.75;
$dia_obj_line_0_orth_points_x3=1.55;
$dia_obj_line_0_orth_points_y3=0.75;
$dia_obj_line_0_orth_points_x4=1.55;
$dia_obj_line_0_orth_points_y4=2.0;
$dia_obj_line_0_orth_points_x5=2.5;
$dia_obj_line_0_orth_points_y5=2.0;
$dia_obj_line_0_orth_points_x6=2.6;
$dia_obj_line_0_orth_points_y6=2.0;
//
// Orth Orientation
$dia_obj_line_0_orth_orient_1=1;
$dia_obj_line_0_orth_orient_2=0;
$dia_obj_line_0_orth_orient_3=1;
$dia_obj_line_0_orth_orient_4=0;
$dia_obj_line_0_orth_orient_5=1;

//
// Autorouting
$dia_obj_line_0_autorouting="true";
//
$dia_obj_line_0_line_width=0.00;
// Dot Dash line
$dia_obj_line_0_line_style=3;
// Start Arrow
$dia_obj_line_0_start_arrow=13;
$dia_obj_line_0_start_arrow_length=0.2;
$dia_obj_line_0_start_arrow_width=0.2;
//
// End Arrow
$dia_obj_line_0_end_arrow=13;
$dia_obj_line_0_end_arrow_length=0.2;
$dia_obj_line_0_end_arrow_width=0.2;
// Dot-dash line dash length
$dia_obj_line_0_dashlength=0.2;
//
// Connection properties
$dia_obj_line_0_connection_handle="0";
// Remember OHH Zero not Zero Zero (AJH)
$dia_obj_line_0_connection_handle_to="O0";
$dia_obj_line_0_connection_handle_connection="8";
//
// End of Line Element 0 config



//
?>
