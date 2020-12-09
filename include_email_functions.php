<?php
/**********************************************************************************************************
Module: include_email_functions.php

Description:
    These functions utilize some PHP classes to send email using SMTP.
    
**********************************************************************************************************/

require "./lib/mimemessage/email_message.php";
require "./lib/mimemessage/smtp_message.php";
require "./lib/smtp/smtp.php";
require "./lib/sasl/sasl.php";
require_once "./include_functions.php";

/**********************************************************************************************************
Function Name:
  GetEmailObject
Description:
  Return an object reference to use for sending emails via SMTP
Arguments: None
Returns:
  [ObjectRef] A reference to a smtp_message_class object with the SMTP settings already defined
**********************************************************************************************************/
function &GetEmailObject() { 
  $email = new smtp_message_class;
  $smtp  = GetSmtpConnectionFromDb();

  if ( is_array($smtp) ){
    $email->smtp_host=$smtp["host"];
    $email->smtp_port=$smtp["port"];
    $email->smtp_ssl=$smtp["use_ssl"];
    $email->smtp_direct_delivery=0;
    $email->smtp_debug=0;
    $email->smtp_html_debug=0;
    $email->timeout=10;

    if ( $smtp["authentication"] ) {
      $email->smtp_start_tls=$smtp["start_tls"];
      $email->authentication_mechanism=$smtp["security"];
      $email->smtp_user=$smtp["user"];
      $email->smtp_password=$smtp["password"];
      $email->smtp_realm=$smtp["realm"];
    }

    preg_match("/^(.*?)@/",$smtp["from"],$name);
    $email->SetEncodedEmailHeader( 'From',$smtp["from"], $name[1] );
  }
  else {
    $email = null;
  }

  return $email;
}

/**********************************************************************************************************
Function Name:
  SendHtmlEmail
Description:
  Sends an HTML email.
Arguments:
  $subject     [IN] [String] The subject the email
  $html        [IN] [String] The HTML messsage for the email
  $to          [IN] [Array]  An array of emails to send it to
  $email       [IN] [Object] Email object, or null to have this function create one
  $attachments [IN] [Array] An array of file attachment arrays for the Mimemessage class
                      Ex: ('Name'=>'','Data'=>'','Content-Type'=>'','Disposition'=>'')
  $logo        [IN] [Sring] Path to a logo to use for the email. Ignored if null
Returns:    
  [Array] An array of email addresses that failed to send, if any
**********************************************************************************************************/
function SendHtmlEmail($subject,$html,$to,&$email,$attachments,$logo) { 
  if(is_null($email)){ $email =& GetEmailObject(); }
  $err_list  = array();
  $rel_parts = array();

  $text = "This is an HTML email. Enable HTML to view it.";

  $email->SetEncodedHeader('Subject',$subject);

  // Add a logo or image if one is specified
  if ( !is_null($logo) && file_exists($logo["Path"]) ) {
    $image=array(
      "FileName"     => $logo["Path"],
      "Content-Type" => "automatic/name",
      "Disposition"  => "inline",
      "Cache"        => 1
    );

    $email->CreateFilePart($image,$image_part);
    $cid = $email->GetPartContentID($image_part);

    // No way to predict/set the CID with this class, so set it after we get it...
    $html = ( preg_match("/{$logo["Variable"]}/",$html) ) ?
      preg_replace("/{$logo["Variable"]}/",$cid,$html) :
      $html;
  }

  // Create the parts to add first
  $email->CreateQuotedPrintableHTMLPart($html,'',$html_part);
  $email->CreateQuotedPrintableTextPart($email->WrapText($text),'',$text_part);

  // Create the alternative/multipart message, add it to related parts
  $alt_parts = array(
    $text_part,
    $html_part
  );

  $email->CreateAlternativeMultipart($alt_parts,$alt_part);
  array_push($rel_parts,$alt_part);
  // The image part needs to be added after the other parts...
  if ( isset($image_part) ) { array_push($rel_parts,$image_part); }


  $email->AddRelatedMultipart($rel_parts);

  // Attach files if they were passed
  if ( is_array($attachments) ) {
    foreach( $attachments as $attachment ) {
      $email->AddFilePart($attachment);
    }
  }

  // Loop through the email addresses, send them out individually
  foreach ( $to as $address ) {
    $email->SetEncodedEmailHeader('To',$address,'');
    $result = $email->Send();
    if(!empty($result)){ array_push($err_list,$address); }
  }

  return $err_list;
}

/**********************************************************************************************************
Function Name:
  ParseEmailTemplate
Description:
  Given the template varibles, replacements, and filename, return the HTML with the vars in place.
Arguments:
  $variables   [IN] [Array]  The variables to look for in the email and what to replace it with
  $template    [IN] [String] The name of the email template file
Returns:    
  [String] The email with variables replaced
**********************************************************************************************************/
function ParseEmailTemplate($variables,$template) {

  $html = file_get_contents($template);

  if( $html == false ) {
    return "Cannot read template file: $template <br>Check your Open-AudIT installation to make sure it exists.";
  }
  else {
    foreach($variables as $variable => $data) {
      if( preg_match("/$variable/",$html) ) {
        $html = preg_replace("/$variable/",$data,$html);
      }
    }
  }

  return $html;
}

?>
