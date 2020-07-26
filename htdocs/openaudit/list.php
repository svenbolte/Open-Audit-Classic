<?php

$JQUERY_UI = array('core','dialog','tooltip');
include_once("include.php");
$time_start = microtime_float();
// set an initial 4 min extra timeout
set_time_limit(240000);

$count_system_max="10000";

// If you would like to have a new View, you have to modify 3 parts:
// -> include_menu_array.php: $menue_array
// -> list_viewdef_X.php: "Table and fields to select and show"
// -> option: include_functions.php: ConvertSpecialField()

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


    //ORDER, SORT and LIMIT
    if (isset($_REQUEST['sort']) AND $_REQUEST['sort']!="") {$sort = $_REQUEST['sort'];} else {$sort = $query_array["sort"];}
    if (isset($_REQUEST['dir']) AND $_REQUEST['dir']!="")  {$dir = $_REQUEST['dir'];} else {$dir = $query_array["dir"];}
    if (isset($dir) AND $dir=="ASC")  { $new_dir = "DESC"; }else{ $new_dir = "ASC";}
    if (!isset($_REQUEST["show_all"]))  { $show_all = "1"; }else{ $show_all = $_REQUEST["show_all"]; }
    if (!isset($_REQUEST["headline_addition"]))  { $headline_addition=" "; } else { $headline_addition = $_REQUEST["headline_addition"]; }

    if (isset($_REQUEST["page_count"])){ $page_count = $_REQUEST["page_count"]; } else { $page_count = 0;}
    $page_prev = $page_count - 1;
    if ($page_prev < 0){ $page_prev = 0; } else {}
    $page_next = $page_count + 1;
    $page_current = $page_count;
    $page_count = $page_count * $count_system;

$show_all=1;
$page_count=0;

    //Preparing the Qeuery
    $sql_query=$query_array["sql"];
    //SORT
    $sql_sort=" ORDER BY " . $sort . " " . $dir;
    //LIMIT
    if(isset($show_all) AND $show_all!=1){
        $sql_limit=" LIMIT " . $page_count . "," . $count_system;
    }else{
        $sql_limit="";
    }

    //Integrating Search-Values in the SQL-Query (WHERE)
    if(isset($_REQUEST["filter"]) AND $_REQUEST["filter"]){
        $sql_query=sql_insert_search($sql_query, $_REQUEST["filter"]);
    }

    //Executing the Qeuery
    $sql=$sql_query."\n".$sql_sort."\n".$sql_limit;
//    $result = mysqli_query($db,$sql);
//    if(!$result) {die( "<br>".__("Fatal Error").":<br><br>".$sql."<br><br>".mysqli_error($db)."<br><br>" );};
//    $this_page_count = mysqli_num_rows($result);

    //Getting the count of all available items
    $sql_all = $sql_query."\n".$sql_sort;
    $result_all = mysqli_query($db,$sql_all);
    // Add an additional 100ms to our time this should act as a watchdog timer, since we should never 
    // spend more than an additional 100ms per loop.. If we do... Crunch! We error.
    set_time_limit(100);
    if(!$result_all) {die( "<br>".__("Fatal Error").":<br><br>".$sql_all."<br><br>".mysqli_error($db)."<br><br>" );};
    $all_page_count = mysqli_num_rows($result_all);
    $result = $result_all;
    $this_page_count = ($page_current * $count_system) - $all_page_count;

    //Show Searchboxes, if search is used on the calling page
    $searchboxes_array[0]["image"]="images/searchbox_hide.png";
    $searchboxes_array[0]["alt"]=__("Discard Search");
    $searchboxes_array[1]["image"]="images/searchbox_show.png";
    $searchboxes_array[1]["alt"]=__("Search in this View");

    //Check for searchvalues
    if(isset($_REQUEST["filter"]) ){
        reset($_REQUEST["filter"]);
        while (list ($filter_var, $filter_val) = @each ($_REQUEST["filter"])) {
            if($filter_val!="")$show_filter=1;
        }
    }
    if(isset($show_filter) AND $show_filter==1){
        $style_searchboxes="display:inline;";
        $image_searchboxes=$searchboxes_array[0]["image"];
        $alt_searchboxes=$searchboxes_array[0]["alt"];
    }else{
        $style_searchboxes="display:none;";
        $image_searchboxes=$searchboxes_array[1]["image"];
        $alt_searchboxes=$searchboxes_array[1]["alt"];
    }

echo "<td valign=\"top\">\n";
echo "<div class=\"main_each\">";

//IIS doesn't set $_SERVER["REQUEST_URI"] so need to use script name and query string instead
$MY_REQUEST_URI = $_SERVER["SCRIPT_NAME"] . "?" .  $_SERVER["QUERY_STRING"];

echo "<form method=\"post\" id=\"form_nav\" action=\"".htmlentities($MY_REQUEST_URI)."\" style=\"margin:0px;\">\n";

  //Calculating the page-count-vars in headline
  if( ($page_count+$count_system)>$all_page_count OR (isset($show_all) AND $show_all==1)){
      $show_page_count_to=$all_page_count;
  }else{
      $show_page_count_to=$page_count+$count_system;
  }

  echo "<table width=\"100%\"><tr><td rowspan=\"2\" class=\"contenthead\">\n";

     //Is the headline a sql-query?
     if(isset($query_array["headline"]) AND is_array($query_array["headline"])){
         echo htmlspecialchars($query_array["headline"]["name"]);
         echo " - ";
         $result_headline=mysqli_query($db,$query_array["headline"]["sql"]);
         if ($myrow = mysqli_fetch_array($result_headline)){
             echo $myrow[0];
         }
     }else{
         echo htmlspecialchars($query_array["headline"])." ";
     }

     if(isset($_REQUEST["headline_addition"])) {echo htmlspecialchars(stripslashes($_REQUEST["headline_addition"]));}
     echo " (".($page_count+1)."-".$show_page_count_to."/".$all_page_count.")\n";
  echo "</td><td align=\"right\" >\n";

  //Navigation-buttons
  //Previous
  if($page_count!=0 AND (isset($show_all) AND $show_all!=1)){
      echo "<a href=\"#\" onclick=\"set_form_field('page_count', '".$page_prev."'); submit_form();\">";
        echo "<img src=\"images/go-prev.png\" alt=\"".__("Previous")."\" title=\"".__("Previous")."\" style=\"border:0px;\" width=\"16\" height=\"16\" />";
      echo "</a>\n";
  }else{
    echo "<img src=\"images/go-prev-disabled.png\" alt=\"".__("Disabled")."\" title=\"".__("Disabled")."\" style=\"border:0px;\" width=\"16\" height=\"16\" />\n";
  }

  //All
  //if($all_page_count>=$count_system OR $count_system==$count_system_max ){
  if($all_page_count>$count_system OR $count_system==$count_system_max ){
      if($show_all!=1){
          echo "<a href=\"#\" onclick=\"set_form_field('show_all', '1'); set_form_field('page_count', '0'); submit_form();\">";
            echo "<img src=\"images/go-all.png\" alt=\"\" title=\"".__("All")."\" style=\"border:0px;\" width=\"16\" height=\"16\" />";
          echo "</a>\n";
      }else{
          echo "<a href=\"#\" onclick=\"set_form_field('show_all', ''); set_form_field('page_count', '0'); submit_form();\">";
            echo "<img src=\"images/go-less.png\" alt=\"".__("By Page")."\" title=\"".__("By Page")."\" style=\"border:0px;\" width=\"16\" height=\"16\" />";
          echo "</a>\n";
      }
  }else{
      echo "<img src=\"images/go-all-disabled.png\" alt=\"".__("Disabled")."\" title=\"".__("Disabled")."\" style=\"border:0px;\" width=\"16\" height=\"16\" />\n";
  }
  //Next
  //if(($page_count+$count_system)<=$all_page_count AND (isset($show_all) AND $show_all!=1)){
  if(($page_count+$count_system)<$all_page_count AND (isset($show_all) AND $show_all!=1)){
      echo "<a href=\"#\" onclick=\"set_form_field('page_count', '".$page_next."'); submit_form();\">";
        echo "<img src=\"images/go-next.png\" alt=\"".__("Next")."\" title=\"".__("Next")."\" style=\"border:0px;\" width=\"16\" height=\"16\" />";
      echo "</a>\n";
  }else{
    echo "<img src=\"images/go-next-disabled.png\" alt=\"".__("Disabled")."\" title=\"".__("Disabled")."\" style=\"border:0px;\" width=\"16\" height=\"16\" />\n";
  }

  echo "</td></tr><tr><td align=\"right\">\n";

  //Direct jumping to pages
  //Don't show if there is only one-page or show_all==1
  //if( ($all_page_count>=$count_system OR $count_system==$count_system_max) AND (isset($show_all) AND $show_all!=1) ){
  //    for ($i = 0; $i <= $all_page_count; $i=$i+$count_system) {
  if( ($all_page_count>$count_system OR $count_system==$count_system_max) AND (isset($show_all) AND $show_all!=1) ){
      for ($i = 0; $i < $all_page_count; $i=$i+$count_system) {

          $last_goto_page=0;
          if( ($i<=($count_system*4)) OR ($i>=($all_page_count-($count_system*3))) ){
              if($i==$page_count){ $style_for_direct_jump="color:red;";}else{$style_for_direct_jump="";};
              $goto_page=($i/$count_system+1);
              echo "&nbsp;<a href=\"#\" onclick=\"set_form_field('page_count', '".($i/$count_system)."'); set_form_field('show_all', '0'); submit_form();\" style=\"$style_for_direct_jump\" title=\"".__("Go to Page")." ".$goto_page."\">";
              echo $goto_page;
              echo "</a>";
          }else{
              if(isset($dots_for_direct_jump_is_sown) AND $dots_for_direct_jump_is_sown!=1){
                  $dots_for_direct_jump_is_sown=1;
                  echo "...";
              }
          }
      }
      unset($style_for_direct_jump);
      echo "&nbsp;&nbsp;\n";
      echo "<input type=\"text\" name=\"page_count_tmp\" value=\"".($page_current+1)."\" style=\"width:16px;\" />\n";
      echo "<input type=\"button\" name=\"tmp_submit\" value=\">\" style=\"width:16px;\" onclick=\"set_form_field('page_count', (document.forms['form_nav'].elements['page_count_tmp'].value-1)); submit_form();\" />\n";
  }

  echo "</td></tr></table>\n";

//Table header
$headline_1=" ";
$headline_2=" ";
$count_searchboxes=0;
foreach($viewdef_array["fields"] as $field) {
    if($field["show"]=="y"){
        // Add an additional 100ms to our time this should act as a watchdog timer, since we should never 
        // spend more than an additional 100ms per loop.. If we do... Crunch! We error.
        set_time_limit(100);
        $field_width = "";
        $field_height = "";
        if ( isset($field["width"]) AND $field["width"] <> "") {$field_width = " style=\"width:".$field["width"].";\"";}
        if (isset($field["height"]) AND $field["height"] <> "") {$field_height = " height=\"".$field["height"]."\"";}
        $headline_1 .= "<td  class=\"views_tablehead\">";
        if(!isset($field["sort"]) OR (isset($field["sort"]) AND $field["sort"]!="n")){
            $headline_1 .= "<a href=\"#\" onclick=\"set_form_field('sort', '".$field["name"]."'); set_form_field('dir', '".$new_dir."'); set_form_field('page_count', '0'); submit_form();\" title=\"".__("Sort by").": ".$field["head"].", ".__("Direction").": ".__($new_dir)."\">";
        }
        $headline_1 .= $field["head"];
        if(!isset($field["sort"]) OR (isset($field["sort"]) AND $field["sort"]!="n")){
            $headline_1 .= "</a>\n";
        }
        if($sort==$field["name"]){
            $headline_1 .= "<img src=\"images/".strtolower($dir).".png\" height=\"4\" width=\"7\" style=\"padding-bottom:3px; border:0px\" alt=\"\" />";
        }
        $headline_1 .= "</td>\n";

        $headline_2 .= "<td class=\"searchboxes\">\n";

        if(!isset($field["search"])) $field["search"]="y";
         if($field["search"]!="n"){
             $count_searchboxes++;
             $headline_2 .= "<div id=\"searchboxes_".$count_searchboxes."\" style=\"$style_searchboxes\">";
             $headline_2 .= "<input type=\"text\" name=\"filter[".$field["name"]."]\" value=\"";
             if(isset($_POST["filter"][$field["name"]])) $headline_2 .= $_POST["filter"][$field["name"]];
             $headline_2 .= "\" style=\"width:90%;\" />\n";
             $headline_2 .= "</div>";
         }
        $headline_2 .= "</td>\n";
    }
}

 //Button to Show and Hide the searchboxes
 $headline_1 .= "<td style=\"width:20px; border-bottom: 1px solid #000000;\">";
 $headline_1 .= "<a href=\"#\" onclick=\"show_searchboxes();\" id=\"link_searchboxes\"><img src=\"".$image_searchboxes."\" id=\"arrows_searchboxes\" style=\"border:0px;\" width=\"20\" height=\"16\" alt=\"".$alt_searchboxes."\" title=\"".$alt_searchboxes."\" /></a>";
 $headline_1 .= "</td>";

 $count_searchboxes++;
 $headline_2 .= "<td class=\"searchboxes\" >\n";
 $headline_2 .= "<div id=\"searchboxes_".$count_searchboxes."\" style=\"$style_searchboxes\">";
 $headline_2 .= "<input type=\"submit\" name=\"filter_submit\" value=\">\" style=\"width:16px;\" title=\"".__("Execute search")."\" onclick=\"set_form_field('page_count', '0');\" />\n";
 $headline_2 .= "</div>";
 $headline_2 .= "</td>\n";

echo "<script type=\"text/javascript\">\n";
 echo "<!--\n";
  echo "function set_form_field(var_name, var_val){
        document.forms['form_nav'].elements[var_name].value = var_val;
        }\n";
  echo "function submit_form(var_name, var_val){
            document.forms['form_nav'].submit();
        }\n";
  echo "function show_searchboxes(){
            if(document.getElementById(\"searchboxes_1\").style.display == 'none'){
                action='inline';
                var img_src='".$searchboxes_array[0]["image"]."';
                var img_alt='".$searchboxes_array[0]["alt"]."';
                //Show Boxes
                for (var i = 1 ; i < ".($count_searchboxes+1)."; i++){
                    document.getElementById(\"searchboxes_\"+i).style.display = action;
                }
                //Show Image
                document.getElementById('arrows_searchboxes').src=img_src;
                document.getElementById('arrows_searchboxes').alt=img_alt;
                document.getElementById('arrows_searchboxes').title=img_alt;
            }else{
                document.getElementById(\"link_searchboxes\").href='".$MY_REQUEST_URI."';
            }
        }\n";
 echo "//-->\n";
echo "</script>\n";

 echo "<input type=\"hidden\" name=\"dir\" value=\"".$dir."\" />\n";
 echo "<input type=\"hidden\" name=\"sort\" value=\"".$sort."\" />\n";
 echo "<input type=\"hidden\" name=\"page_count\" value=\"".$page_count."\" />\n";
 echo "<input type=\"hidden\" name=\"show_all\" value=\"".$show_all."\" />\n";
 echo "<input type=\"hidden\" name=\"headline_addition\" value=\"".$headline_addition."\" />\n";
//echo "</form>\n";
//echo "<form method=\"post\" name=\"form_search\" action=\"".htmlentities($_SERVER["REQUEST_URI"])."\" style=\"margin:0px;\">\n";
echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";

echo "<tr>\n";
 echo $headline_1;
echo "</tr>\n";

  echo "<tr style=\"width:100%\">\n";
   echo $headline_2;
  echo "</tr>\n";

//echo "</form>\n";

//Table body
$rownumber=0;
if ($myrow = mysqli_fetch_array($result)){
  if(!$show_all) {
    mysqli_data_seek($result,$page_count);
    $myrow = mysqli_fetch_array($result);
  }
    do{
        $bgcolor = change_row_color($bgcolor,$bg1,$bg2);
        echo " <tr style=\"background-color:" . $bgcolor . ";\">\n";
        foreach($query_array["fields"] as $field){

            if($field["show"]!="n"){
               $a_misc = "";
			   
               //Convert the array-values to local variables
				foreach($myrow as $key => $val) {
                   $$key=$val;
               }

                //Generating the link
                //Does the field have its own link? Otherwise take the standard-link of the view
                if(isset($field["get"]["file"]) AND $field["get"]["file"]!=""){
                    $get_array=$field["get"];
                }else{
                    if(isset($query_array["get"])){
                        $get_array=$query_array["get"];
                    }
                }

                if(!isset($get_array["target"])) $get_array["target"]="_self";
                if(!isset($get_array["onclick"])) $get_array["onclick"]=" ";

                if(isset($get_array["file"])){
                    if(substr($get_array["file"],0,1)=="%"){
                        $value=substr($get_array["file"],1);
                        $link_file=$$value;
                    }else{
                        $link_file=$get_array["file"];
                    }
                }else{
                    $link_file=FALSE;
                }
                //Don't show the link if ther's no target-file
                if($link_file==FALSE){
                    $field["link"]="n";
                }

                if(isset($field["link"]) AND $field["link"]=="y")
								{
									unset($link_query);
									@reset ($get_array["var"]);
									while (list ($varname, $value) = @each ($get_array["var"])) 
									{
										$value = (isset($value)) ? $value : "";
										if(substr($value,0,1)=="%")
										{
											$value = substr($value,1);
											$value2 = (isset($$value)) ? $$value : "";
										}
										else {$value2 = $value;}

										if(!isset($link_query)) 
										{$link_query = $varname."=".urlencode($value2)."&amp;";}
										else{$link_query.= $varname."=".urlencode($value2)."&amp;";}

										//Don't show the link if one GET-variable is empty
										if(!isset($value2) or $value2==""){$field["link"]="n";}
									}
								}
                

                if(isset($link_query) AND $link_query!=""){
                    $url=parse_url($get_array["file"]);
                    if(isset($url["query"]) AND $url["query"]!=""){
                        $link_separator="&amp;";
                    }else{
                        $link_separator="?";
                    }
                    $link_uri=$link_file.$link_separator.$link_query;
                }else{
                    $link_uri=$link_file;
                }
                $field_align = "";
                echo "  <td ";
                 if (isset($field["align"])) { echo "align=\"".$field["align"]."\""; }
                 echo "style=\"padding-right:10px;\">";

                $show_value=" ";

                //Strip the table-name
                if(strpos($field["name"],".")){
                    $field["name"]=substr($field["name"],(strpos($field["name"],".")+1));
                }

                //Special field-converting

				if(isset($field["calculate"]) AND $field["calculate"]=="y"){
				// Special field calculations here, for example warranty days. 
				//
			   if ($field["name"]=="partition_used_space") {
					$show_value=($myrow["partition_size"] - $myrow["partition_free_space"]);	
			   }				
				
				
				if(isset($field["dell_warranty"]) AND $field["dell_warranty"]=="y"){
				// allow another 10 seconds for this bit to complete
				set_time_limit(240);
				$show_value = $myrow["system_id_number"];
				//
				$this_dell_warranty_remaining = get_dell_warranty_days( $show_value);
				// echo "..".$show_value."--" ;
				$myrow ["dell_warranty"] = $this_dell_warranty_remaining ;
				$show_value = $this_dell_warranty_remaining; 
					}
				}else{
				// If this is not a calculated value, just show it
               $show_value = ConvertSpecialField($myrow, $field, $db, "list");
			   if ($field["name"]=="software_comment") {
					$show_value = "<acronym title='".$software_comment."'>".substr($software_comment,0,35)."</acronym>";
				}
			    if ($field["name"]=="system_os_name" AND $system_os_name != "") { $software_name=$system_os_name; }
			    if (isset($ms_keys_name)) { if ($ms_keys_name != "") { $software_name=$ms_keys_name; } }

				if (isset($software_name)) {
					if (strpos($software_name," ")!=0) {
							$logobild = substr($software_name,0,strpos($software_name," "));
						}else{
							if (strpos($software_name,".")!=0) {
								$logobild = substr($software_name,0,strpos($software_name,"."));
							}else{
								$logobild=$software_name;
							}
						} 
					//if (strlen($logobild)>4) {$show_value = $logobild;}
				}	
				if (($field["name"]=="software_name" or $field["head"]=="Software" or $field["name"]=="system_os_name") and is_file("softwarelogos/".$logobild.".png")){
				   $show_value = "<img src=\"softwarelogos/".$logobild.".png\" style=\"border:0px;\" alt=\"\" /> ".$software_name;
				}
								
				}
				if(isset($field["link"]) AND $field["link"]=="y"){
                    if(!isset($get_array["title"])) $get_array["title"]=$show_value;
                    echo "<a href=\"".$link_uri."\" title=\"".$get_array["title"]."\" onclick=\"".$get_array["onclick"]." ; this.target='".$get_array["target"]."';\" $a_misc>";
                }
                if(isset($field["image"]) AND $field["image"]!=""){
                    echo "<img src=\"".$field["image"]."\" style=\"border:0px;\" alt=\"\" />";
                }else{
                    echo $show_value;
                }
                if(isset($field["link"]) AND $field["link"]=="y"){
                    echo "</a>\n";
                }
                //Is there a help entry?
                if(isset($field["help"]) AND $field["help"]!=""){
                    if(substr($field["help"],0,1)=="%"){
                        $value=substr($field["help"],1);
                        $help=$$value;
                    }else{
                        $help=$field["help"];
                    }
                    echo "&nbsp;<a href=\"#\" onclick=\"alert('".addslashes(str_replace("\"","",$help))."')\">?</a>";
                }
                echo "</td>\n";
            }
        }
        echo "<td>\n";
        echo "</td>\n";
        echo " </tr>\n";
    //}while ($myrow = mysqli_fetch_array($result));
      $rownumber ++;
    }while ($myrow = mysqli_fetch_array($result) and ($show_all or $rownumber < $count_system));
    echo "</table></form>\n";
    echo "<div>";
     echo "<table width=\"100%\"><tr>\n";

    // Export to CSV

    echo "<form method=\"post\" id=\"form_export\" action=\"list_export.php\">\n";
    echo "<input type=\"hidden\" name=\"sql\" value=\"".urlencode($sql)."\" />\n";
    echo "<input type=\"hidden\" name=\"view\" value=\"".$_REQUEST["view"]."\"/>\n";
    if(isset($_REQUEST["pc"])){
         echo "<input type=\"hidden\" name=\"pc\" value=\"".$_REQUEST["pc"]."\"/>\n";
     }
     if(isset($_REQUEST["other"])){
         echo "<input type=\"hidden\" name=\"other\" value=\"".$_REQUEST["other"]."\" />\n";
     }
     if(isset($_REQUEST["monitor"])){
         echo "<input type=\"hidden\" name=\"monitor\" value=\"".$_REQUEST["monitor"]."\" />\n";
     }
     echo "<br /><a href=\"http://www.libreoffice.org/\"><img src=\"images/x-office-spreadsheet.png\" alt=\"".__("CSV Spreadsheet")."\" title=\"".__("Click Here for the latest version of LibreOffice")."\" style=\"border:0px;\" width=\"28\" height=\"28\" /></a><a href=\"#\" class=\"get-view-csv\"> ".__("Export this List to CSV")."</a>\n";
    echo "</form>\n";
    echo " &nbsp; &nbsp; \n";
    // Export to DIA
    
    if (isset($_REQUEST["view"])) {
    // Check to be sure that we are looking at something which we can make a diagram of
    $pos= (strpos($_REQUEST["view"], "systems") or strpos($_REQUEST["view"], "laptops") or strpos($_REQUEST["view"], "servers") or strpos($_REQUEST["view"], "workstations") or strpos($_REQUEST["view"], "for_gateway") or strpos($_REQUEST["view"], "networked") or strpos($_REQUEST["view"], "hosts") or strpos($_REQUEST["view"], "printers") or strpos($_REQUEST["view"], "port") or strpos($_REQUEST["view"], "_all"));
    if ($pos === true) {
    echo "<form method=\"post\" id=\"form_export_dia\" action=\"list_export_dia.php\">\n";
    echo "<input type=\"hidden\" name=\"sql\" value=\"".urlencode($sql)."\" />\n";
    echo "<input type=\"hidden\" name=\"view\" value=\"".$_REQUEST["view"]."\"/>\n";
    if(isset($_REQUEST["pc"])){
         echo "<input type=\"hidden\" name=\"pc\" value=\"".$_REQUEST["pc"]."\"/>\n";
     }
     if(isset($_REQUEST["other"])){
         echo "<input type=\"hidden\" name=\"other\" value=\"".$_REQUEST["other"]."\" />\n";
     } 
     if(isset($_REQUEST["monitor"])){
         echo "<input type=\"hidden\" name=\"monitor\" value=\"".$_REQUEST["monitor"]."\" />\n";
     }
     echo " <a href=\"http://live.gnome.org/Dia\" <img src=\"images/gnome-application-x-dia-diagram.png\" alt=\"".__("Dia Diagram")."\" title=\"".__("Click here for the latest version of DIA")."\" style=\"border:0px;\" width=\"28\" height=\"28\" /></a><a href=\"#\" onclick=\"document.forms['form_export_dia'].submit();\"> ".__("Create DIA Network Diagram From List")."</a>\n";
    echo "</form>\n";
    echo " &nbsp; &nbsp; \n";
    // Export to Inkscape
    echo "<form method=\"post\" id=\"form_export_inkscape\" action=\"list_export_inkscape.php\">\n";
    echo "<input type=\"hidden\" name=\"sql\" value=\"".urlencode($sql)."\" />\n";
    echo "<input type=\"hidden\" name=\"view\" value=\"".$_REQUEST["view"]."\"/>\n";
    if(isset($_REQUEST["pc"])){
         echo "<input type=\"hidden\" name=\"pc\" value=\"".$_REQUEST["pc"]."\"/>\n";
     }
     if(isset($_REQUEST["other"])){
         echo "<input type=\"hidden\" name=\"other\" value=\"".$_REQUEST["other"]."\" />\n";
     }
     if(isset($_REQUEST["monitor"])){
         echo "<input type=\"hidden\" name=\"monitor\" value=\"".$_REQUEST["monitor"]."\" />\n";
     }
     echo " <a href=\"http://www.inkscape.org/\" <img src=\"images/inkscape.png\" alt=\"".__("Inkscape Drawing")."\" title=\"".__("Click here for the latest version of Inkscape")."\" style=\"border:0px;\" width=\"28\" height=\"28\" /></a><a href=\"#\" onclick=\"document.forms['form_export_inkscape'].submit();\"> ".__("Create Inkscape (SVG) Picture From List")."</a>\n";
    echo "</form>\n";
                } else{}
        } else{}
} else {

  echo "<tr><td colspan=\"4\">".__("No Results")."</td></tr>\n";
  echo "</table>\n";
  echo "</form>\n";
}

echo "</div></td>\n";
// // include "include_right_column.php";
include "include_export_modal.php"; 
echo "</body>\n";
echo "</html>\n";
?>
