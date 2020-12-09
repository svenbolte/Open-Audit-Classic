<?php
if(!isset($language) or $language=="") $GLOBALS["language"]="en";
$language_file="./lang/".$GLOBALS["language"].".inc";
if(is_file($language_file)){
    include($language_file);
}else{
    die("Language-File not found: ".$language_file);
}

function __($word){

    //Learning-Mode
    //Only for Developers !!!!
    $language_learning_mode=0;
    if($language_learning_mode==1)  {
        $language_file="./lang/".$GLOBALS["language"].".inc";
        include($language_file);
    }

    if(isset($GLOBALS["lang"][$word])){
        return $GLOBALS["lang"][$word];
    }else{
        //Learning-Mode
        if($language_learning_mode==1 AND isset($word) AND $word!="")  {
            if(is_writable($language_file)){

                //Deleting
                $buffer="";
                $handle = fopen($language_file, "r");
                while (!feof($handle)) {
                    $line = fgets($handle, 4096);
                    if(!ereg("\?>",$line)){
                        $buffer .= $line;
                    }
                }
                fclose ($handle);

                //Writing new Variables
                $handle = fopen($language_file, "w+");
                fwrite($handle, $buffer.""."\$GLOBALS[\"lang\"][\"$word\"]=\"$word\";\n?>");
                fclose($handle);
            }else{
                die("Language-Learning-Mode, but $language_file not writeable");
            }
        }
        return $word;
    }
}
?>
