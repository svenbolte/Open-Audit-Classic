<?php

include_once("include_config.php");
include_once("include_functions.php");
include_once("include_lang.php");
include_once("include_dia_config.php");

//$time_start = microtime_float();


//MySQL-Connect
$db=GetOpenAuditDbConnection() or die('Could not connect: ' . mysqli_error($db));
mysqli_select_db($db,$mysqli_database);

//Some Config fcr Layout


//MySQL-Connect
$db=GetOpenAuditDbConnection() or die('Could not connect: ' . mysqli_error($db));
mysqli_select_db($db,$mysqli_database);

//Include the view-definition
if(isset($_REQUEST["view"]) AND $_REQUEST["view"]!=""){
    $include_filename = "list_viewdef_".$_REQUEST["view"].".php";
}else{
    $include_filename = "list_viewdef_all_systems.php";
}
if(is_file($include_filename)){
    include_once($include_filename);
    $viewdef_array=$query_array;
}else{
    die("FATAL: Could not find view $include_filename");
}

    //Executing the Qeuery
    $sql=urldecode($_REQUEST["sql"]);
    $result = mysqli_query($db,$sql);
    if(!$result) {die( "<br>".__("Fatal Error").":<br><br>".$sql."<br><br>".mysqli_error($db)."<br><br>" );};
    $this_page_count = mysqli_num_rows($result);
    
$dia_x_offset = 0;
$dia_y_offset = 0;

$dia_current_object_id = 0 ;
header("Content-Type: application/vnd.dia-win-remote");
header("Content-Disposition: inline; filename=\"Open-Audit_".$_REQUEST["view"]."_network_diagram.dia\"");

//
// Setup the format of the .dia page. This is VERY crude, we should create functions to allow proper control of all elements on the page,
// and a setup page to allow contorl over this ... OOPS (AJH)
//
$dia_page_setup_1 = '<?xml version="1.0" encoding="UTF-8"?>
<dia:diagram xmlns:dia="http://www.lysator.liu.se/~alla/dia/">
  <dia:diagramdata>
    <dia:attribute name="'.$dia_background_name.'">
      <dia:color val="'.$dia_background_colour.'"/>
    </dia:attribute>
    <dia:attribute name="pagebreak">
      <dia:color val="'.$dia_pagebreak_colour.'"/>
    </dia:attribute>
    <dia:attribute name="paper">
      <dia:composite type="'.$dia_paper.'">
        <dia:attribute name="name">
          <dia:string>'.$dia_paper_name.'</dia:string>
        </dia:attribute>
        <dia:attribute name="tmargin">
          <dia:real val="'.$dia_tmargin.'"/>
        </dia:attribute>
        <dia:attribute name="bmargin">
          <dia:real val="'.$dia_bmargin.'"/>
        </dia:attribute>
        <dia:attribute name="lmargin">
          <dia:real val="'.$dia_lmargin.'"/>
        </dia:attribute>
        <dia:attribute name="rmargin">
          <dia:real val="'.$dia_lmargin.'"/>
        </dia:attribute>
        <dia:attribute name="is_portrait">
          <dia:boolean val="'.$dia_is_portrait.'"/>
        </dia:attribute>
        <dia:attribute name="scaling">
          <dia:real val="'.$dia_scaling.'"/>
        </dia:attribute>
        <dia:attribute name="fitto">
          <dia:boolean val="'.$dia_fitto.'"/>
        </dia:attribute>
      </dia:composite>
    </dia:attribute>
    <dia:attribute name="'.$dia_grid.'">
      <dia:composite type="'.$dia_grid_type.'">
        <dia:attribute name="width_x">
          <dia:real val="'.$dia_grid_width_x.'"/>
        </dia:attribute>
        <dia:attribute name="width_y">
          <dia:real val="'.$dia_grid_width_y.'"/>
        </dia:attribute>
        <dia:attribute name="visible_x">
          <dia:int val="'.$dia_grid_visible_x.'"/>
        </dia:attribute>
        <dia:attribute name="visible_y">
          <dia:int val="'.$dia_grid_visible_x.'"/>
        </dia:attribute>
        <dia:composite type="color"/>
      </dia:composite>
    </dia:attribute>
    <dia:attribute name="color">
      <dia:color val="'.$dia_grid_lines_colour.'"/>
    </dia:attribute>
    <dia:attribute name="guides">
      <dia:composite type="'.$dia_guides.'">
        <dia:attribute name="'.$dia_guides_hguides.'"/>
        <dia:attribute name="'.$dia_guides_vguides.'"/>
      </dia:composite>
    </dia:attribute>
  </dia:diagramdata>
  <dia:layer name="'.$dia_background_name.'" visible="'.$dia_background_visible.'">
  ';
  echo $dia_page_setup_1;

//Create Objects


//Table body. This section creates a list of network objects and distributes them across the page
// The exact number across each page depends on the page size and layout.
//

// First we start at the top left of the page,
//
$dia_current_object_x = $dia_object_start_x;
$dia_current_object_y = $dia_object_start_y;
// Set the first object ID. 
$dia_current_object_id = $dia_object_start_id;
//
// Now we create our list from the list on the page that brought us here.
// This is why we need to expand the list 
if ($myrow = mysqli_fetch_array($result)){
    do{
        foreach($query_array["fields"] as $field){
            if($field["show"]!="n"){
            //
            
            //
            if (($field["head"]=="Hostname") or ($field["head"]=="Hostname") or ($field["head"]=="Rechnername")or ($field["head"]=="Network Name")){

            if (!isset($dia_image_folder) ){
            $dia_image_folder = ".\\";
            } else{}
            //
            if ($field["head"]=="Hostname" or ($field["head"]=="Rechnername")) {
            //
            $dia_image_icon = determine_dia_img($myrow["system_os_name"],$myrow[$field["name"]]);
            //
            $dia_this_image = $dia_image_folder.$dia_image_icon;
                       //
            $dia_current_obj_text="  ".$myrow[$field["name"]]."\n\n";
           // $dia_current_obj_text="  ".$myrow[$field["name"]]."\n\n"."ip: ".$myrow["net_ip_address"]."\n"."\n"."User: ".$myrow["net_user_name"]."\n"."Domain: ".$myrow["net_domain"]."\n"."Vendor: ".$myrow["system_vendor"]."\n"."Model: ".$myrow["system_model"]."\n"."Memory: ".$myrow["system_memory"]." Mb";
           
           if ($dia_show_system_net_ip_address == "y"){
           $dia_current_obj_text=$dia_current_obj_text."ip: ".$myrow["net_ip_address"]."\n";
            } else {}
            
            if ($dia_show_system_net_user_name== "y"){
           $dia_current_obj_text=$dia_current_obj_text."User: ".$myrow["net_user_name"]."\n";
            } else {}
            
            if ($dia_show_system_net_domain == "y"){
           $dia_current_obj_text=$dia_current_obj_text."Domain: ".$myrow["net_domain"]."\n";
            } else {}
            
            if ($dia_show_system_system_vendor == "y"){
           $dia_current_obj_text=$dia_current_obj_text."Vendor: ".$myrow["system_vendor"]."\n";
            } else {}
            
            if ($dia_show_system_system_model == "y"){
           $dia_current_obj_text=$dia_current_obj_text."Model: ".$myrow["system_model"]."\n";
            } else {}
            
            if ($dia_show_system_system_id_number == "y"){
           $dia_current_obj_text=$dia_current_obj_text."Serial #: ".$myrow["system_id_number"]."\n";
            } else {}
            
            if ($dia_show_system_system_memory == "y"){
           $dia_current_obj_text=$dia_current_obj_text."Memory: ".$myrow["system_memory"]." Mb \n";
            } else {}
            
            
            }
            else 
            {
            //         
            $dia_image_icon = determine_dia_img($myrow["other_type"],$myrow["other_type"]);
            //
            $dia_this_image = $dia_image_folder.$dia_image_icon;
            // 
            //."\n".$myrow["other_ip_address"]."\n".$myrow["other_description"];
            $dia_current_obj_text=$myrow[$field["name"]]."\n\n";
            //
            
            if ($dia_show_other_network_name== "y"){
           $dia_current_obj_text=$dia_current_obj_text."Name: ".$myrow["other_network_name"]."\n";
            } else {}

            if ($dia_show_system_net_ip_address == "y"){
           $dia_current_obj_text=$dia_current_obj_text."ip: ".$myrow["other_ip_address"]."\n";
            } else {}
            
            if ($dia_show_other_mac_address== "y"){
           $dia_current_obj_text=$dia_current_obj_text."MAC: ".$myrow["other_mac_address"]."\n";
            } else {}
            
            if ($dia_show_other_description== "y"){
           $dia_current_obj_text=$dia_current_obj_text."Description: ".$myrow["other_description"]."\n";
            } else {}
            
            if ($dia_show_other_location== "y"){
           $dia_current_obj_text=$dia_current_obj_text."Location: ".$myrow["other_location"]."\n";
            } else {}
            
            if ($dia_show_other_serial== "y"){
           $dia_current_obj_text=$dia_current_obj_text."Serial: ".$myrow["other_serial"]."\n";
            } else {}
            
            if ($dia_show_other_model== "y"){
           $dia_current_obj_text=$dia_current_obj_text."Model: ".$myrow["other_model"]."\n";
            } else {}
            
            if ($dia_show_other_type== "y"){
           $dia_current_obj_text=$dia_current_obj_text."Type: ".$myrow["other_type"]."\n";
            } else {}
            
// If its a printer or print server, show the port and share info
            
            if ( ($myrow["other_type"] == "printer") or ($myrow["other_type"] == "print server")) {
            
                 if ($dia_show_other_p_port_name== "y"){
                $dia_current_obj_text=$dia_current_obj_text."Printer Port Name: ".$myrow["other_p_port_name"]."\n";
                    } else {}
            
                 if ($dia_show_other_p_share_name== "y"){
                $dia_current_obj_text=$dia_current_obj_text."Printer Share Name: ".$myrow["other_p_share_name"]."\n";
                    } else {}
            } else {}
            
            
            
            //$dia_current_obj_text=eval($dia_text_other_object_text);
            
            }
            $dia_current_image_object_id = $dia_current_object_id;        
            echo '          <dia:group>
            <dia:object type="'.$dia_obj_image_0_type.'" version="'.$dia_obj_image_0_version.'" id="O'.$dia_current_object_id.'">
        <dia:attribute name="obj_pos">
          <dia:point val="'.$dia_obj_image_0_pos_x.','.$dia_obj_image_0_pos_y.'"/>
        </dia:attribute>
        <dia:attribute name="obj_bb">
          <dia:rectangle val="'.$dia_obj_image_0_bb_x1.','.$dia_obj_image_0_bb_y1.';'.$dia_obj_image_0_bb_x2.','.$dia_obj_image_0_bb_y2.'"/>
        </dia:attribute>
        <dia:attribute name="elem_corner">
        <dia:point val="'.$dia_current_object_x.','.$dia_current_object_y.'"/>
        </dia:attribute>
        <dia:attribute name="elem_width">
          <dia:real val="'.$dia_obj_image_0_elem_width.'"/>
        </dia:attribute>
        <dia:attribute name="elem_height">
          <dia:real val="'.$dia_obj_image_0_elem_height.'"/>
        </dia:attribute>
        <dia:attribute name="draw_border">
          <dia:boolean val="'.$dia_obj_image_0_draw_border.'"/>
        </dia:attribute>
        <dia:attribute name="keep_aspect">
          <dia:boolean val="'.$dia_obj_image_0_keep_aspect.'"/>
        </dia:attribute>
        <dia:attribute name="file"> 
        <dia:string>#'.$dia_this_image.'#</dia:string>
      </dia:attribute>
    </dia:object>';
// Next Object  
 $dia_current_object_id += 1;
//    
            echo '   <dia:object type="'.$dia_obj_text_0_type.'" version="'.$dia_obj_text_0_version.'" id="O'.$dia_current_object_id .'">
      <dia:attribute name="obj_pos">
        <dia:point val="'.($dia_current_object_x + $dia_obj_text_0_pos_x_offset).','.($dia_current_object_y + $dia_obj_text_0_pos_y_offset).'"/>
        </dia:attribute>
        <dia:attribute name="obj_bb">
          <dia:rectangle val="'.$dia_obj_text_0_bb_x1.','.$dia_obj_text_0_bb_y1.';'.$dia_obj_text_0_bb_x2.','.$dia_obj_text_0_bb_y2.'"/>
        </dia:attribute>
        <dia:attribute name="'.$dia_obj_text_0_text.'">
          <dia:composite type="text">
            <dia:attribute name="string">
            <dia:string>#'.$dia_current_obj_text.'#</dia:string>
          </dia:attribute>
            <dia:attribute name="'.$dia_obj_text_0_font.'">
              <dia:font family="'.$dia_obj_text_0_font_family.'" style="'.$dia_obj_text_0_font_style.'" name="'.$dia_obj_text_0_font_name.'"/>
            </dia:attribute>
            <dia:attribute name="height">
              <dia:real val="'.$dia_obj_text_0_font_height.'"/>
            </dia:attribute>
            <dia:attribute name="'.$dia_obj_text_0_font_pos.'">
            <dia:point val="'.$dia_current_object_x.','.($dia_current_object_y + 2.0).'"/>
          </dia:attribute>
            <dia:attribute name="color">
              <dia:color val="'.$dia_obj_text_0_font_colour.'"/>
            </dia:attribute>
            <dia:attribute name="alignment">
              <dia:enum val="'.$dia_obj_text_0_font_alignment.'"/>
            </dia:attribute>
          </dia:composite>
        </dia:attribute>
        <dia:attribute name="valign">
          <dia:enum val="'.$dia_obj_text_0_font_valign.'"/>
        </dia:attribute>
      </dia:object>
    </dia:group>';
// Next Object  
 $dia_current_object_id += 1;
//    
            echo '    <dia:object type="'.$dia_obj_line_0_type.'" version="'.$dia_obj_line_0_version.'" id="O'. $dia_current_object_id .'">
      <dia:attribute name="obj_pos">
        <dia:point val="'.($dia_current_object_x + $dia_obj_image_0_elem_width).','.($dia_current_object_y + ($dia_obj_image_0_elem_height/2)).'"/>
      </dia:attribute>
        <dia:attribute name="obj_bb">
        <dia:rectangle val="'.$dia_obj_line_0_bb_x1.','.$dia_obj_line_0_bb_y1.';'.$dia_obj_line_0_bb_x2.','.$dia_obj_line_0_bb_y2.'"/>
      </dia:attribute>
      <dia:attribute name="orth_points">
        <dia:point val="'.($dia_current_object_x + $dia_obj_line_0_orth_points_x1).','.($dia_current_object_y + $dia_obj_line_0_orth_points_y1).'"/>
        <dia:point val="'.($dia_current_object_x + $dia_obj_line_0_orth_points_x2).','.($dia_current_object_y + $dia_obj_line_0_orth_points_y2).'"/>
        <dia:point val="'.($dia_current_object_x + $dia_obj_line_0_orth_points_x3).','.($dia_current_object_y + $dia_obj_line_0_orth_points_y3).'"/>
        <dia:point val="'.($dia_current_object_x + $dia_obj_line_0_orth_points_x4).','.($dia_current_object_y + $dia_obj_line_0_orth_points_y4).'"/>
        <dia:point val="'.($dia_current_object_x + $dia_obj_line_0_orth_points_x5).','.($dia_current_object_y + $dia_obj_line_0_orth_points_y5).'"/>
        <dia:point val="'.($dia_current_object_x + $dia_obj_line_0_orth_points_x6).','.($dia_current_object_y + $dia_obj_line_0_orth_points_y6).'"/>
      </dia:attribute>
      <dia:attribute name="orth_orient">
        <dia:enum val="'.$dia_obj_line_0_orth_orient_1.'"/>
        <dia:enum val="'.$dia_obj_line_0_orth_orient_2.'"/>
        <dia:enum val="'.$dia_obj_line_0_orth_orient_3.'"/>
        <dia:enum val="'.$dia_obj_line_0_orth_orient_4.'"/>
        <dia:enum val="'.$dia_obj_line_0_orth_orient_5.'"/>
      </dia:attribute>
      <dia:attribute name="autorouting">
        <dia:boolean val="'.$dia_obj_line_0_autorouting.'"/>
      </dia:attribute>
      <dia:attribute name="line_width">
        <dia:real val="'.$dia_obj_line_0_line_width.'"/>
      </dia:attribute>
      <dia:attribute name="line_style">
        <dia:enum val="'.$dia_obj_line_0_line_style.'"/>        
      </dia:attribute>
      <dia:attribute name="start_arrow">
        <dia:enum val="'.$dia_obj_line_0_start_arrow.'"/>
      </dia:attribute>
      <dia:attribute name="start_arrow_length">
        <dia:real val="'.$dia_obj_line_0_start_arrow_length.'"/>
      </dia:attribute>
      <dia:attribute name="start_arrow_width">
        <dia:real val="'.$dia_obj_line_0_start_arrow_width.'"/>
      </dia:attribute>
      <dia:attribute name="end_arrow">
        <dia:enum val="'.$dia_obj_line_0_end_arrow.'"/>
      </dia:attribute>
      <dia:attribute name="end_arrow_length">
        <dia:real val="'.$dia_obj_line_0_end_arrow_length.'"/>
      </dia:attribute>
      <dia:attribute name="end_arrow_width">
        <dia:real val="'.$dia_obj_line_0_end_arrow_width.'"/>
      </dia:attribute>
      <dia:attribute name="dashlength">
        <dia:real val="'.$dia_obj_line_0_dashlength.'"/>
      </dia:attribute>
      <dia:connections>
        <dia:connection handle="'.$dia_obj_line_0_connection_handle.'" to="O'.$dia_current_image_object_id.'" connection="'.$dia_obj_line_0_connection_handle_connection.'"/>
      </dia:connections>
    </dia:object>
';
 $dia_current_object_id += 1;
           
                }                                                                       
//           $dia_current_object_id += 4.0;
            }
        }
        // Space out the objects

        //
        $dia_x_offset = (($dia_current_object_id / $dia_grouped_objects) % $dia_num_across_page ) ;
        if ($dia_x_offset == 0 )  {
        $dia_y_offset = $dia_y_offset + 1;
        }
        
        /*
        //        Test code block to output a few vars
      echo $dia_current_object_id.','  ;
      echo $dia_x_offset.','.$dia_y_offset;
        //
        */        
                

          $dia_current_object_x = $dia_object_start_x + ($dia_x_offset * $dia_object_spacing_x); 
          $dia_current_object_y = $dia_object_start_y + ($dia_y_offset * $dia_object_spacing_y);
        //
        //  $dia_current_object_x += $dia_object_spacing_x; 
        //  $dia_current_object_y += $dia_object_spacing_y;
        //
    }while ($myrow = mysqli_fetch_array($result));

}

/*
// Work in progress
//Looks to the image folder returns the files, no subdirectories. Creates an object per file.

$dh = opendir($dia_image_folder);
while (false !== ($file = readdir($dh))) {
//Don't list subdirectories
if (!is_dir("$dirpath/$file")) {
//Create a Text string to add to the object (truncate the file extension and capitalize the first letter)
$dia_object_text=  htmlspecialchars(ucfirst(preg_replace('/\..*$/', '', $file)));
}


*/

// Close Layer and Document
echo '  </dia:layer>
</dia:diagram>';
// Thats all folks


?>
