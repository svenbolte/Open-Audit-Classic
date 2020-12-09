<?php
        include "include_config.php";
        include "include_functions.php";
       
        if(isset($_GET["application"])) $application=$_GET["application"];
        if(isset($_GET["hostname"])) $hostname=ip_trans($_GET["hostname"]);
        if(isset($_GET["ext"])) $ext=$_GET["ext"];

        SWITCH($application){
            case "http":
            case "https":
            case "ftp":
                header("Location: ".$application."://".$hostname);
            break;
            default:
                //Reading the template
                $buffer=file("launch_filedef_".$application.".txt");
                $buffer=implode("",$buffer);
                //Replacing Hostname
                $buffer=str_replace ( "NAME", $hostname, $buffer );
                //Send to Browser
                header("Content-type: application/force-download");
                header("Content-Transfer-Encoding: Binary");
                $filename=$hostname.".".$ext;
                header("Content-disposition: attachment; filename=\"".$filename."\"");

                echo trim($buffer);
            break;

        }

?>
