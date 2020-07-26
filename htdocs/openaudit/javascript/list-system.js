/**********************************************************************************************************
	This file is included by "list.php" and "system.php" for javascript needed for the export modal.
**********************************************************************************************************/

/**********************************************************************************************************
Function Name:
  GetDataString
Description:
  Put together the string to retrieve/send the exported file
Arguments:	None
Returns:	[String]  The GET request string
**********************************************************************************************************/
function GetDataString(){
  var data = new String('filename=' + $('#export-file-name').val());
  var uuid = $('#export_modal_info [name=pc]').val();
  var name = $('#export_modal_info [name=system-name]').val();
  var user = $('#export_modal_info [name=username]').val();

  if($('#export-select-method :selected').val() == 'email'){
    var emails   = $('#export-email-list').val().split(";");
    for(i=0;i<emails.length;i++){ data += '&email_list[]=' + emails[i]; }
  }

  data += (!$('.pdf-sidemenu-select').is(':visible')) ?
          '&' + $('#form_export').serialize() :
          '&view='+$('#export-select-report :selected').val();
  data += (uuid!='' && $('#export-page-form').val() != 'y') ? '&pc=' + uuid : ''; 
  data += (name!='') ? '&system-name=' + name : ''; 
  data += (user!='') ? '&username=' + user : ''; 
  return data;
}

/**********************************************************************************************************
Function Name:
  CloseExportDialog
Description:
  Close the export dialog, do some cleanup
Arguments:	None
Returns:	None
**********************************************************************************************************/
function CloseExportDialog(){
  $("#export-dialog").dialog('close');
  $('#export-select-method').val('download');
  $('#export-email-list').val('');
  $('#export-email-list').removeClass('email-list-invalid'); 
  $('#export-result').html('');
  $('#export-email').hide();
  $('#export-sending').hide();
}


/**********************************************************************************************************
Function Name:
  isValidEmail
Description:
  Determine if the supplied string is a valid email
Arguments:	[String] An email address
Returns:	[Boolean] True if it's a valid address, false if not
**********************************************************************************************************/
function isValidEmail(email){
  // Pulled from the Regular Expressions Cookbook
  var regex = /^[\w!#$%&'*+/=?`{|}~^.-]+(?:\.[!#$%&'*+/=?`{|}~^-]+)*@(?:[A-Z0-9.-]+\.)+[A-Z]{2,6}$/i;
  var result = (regex.test(email)) ? true : false;
  return result;
}

/**********************************************************************************************************
Function Name:
  ValidateEmailList
Description:
  Loop through the emails in an input list to find out if they are all valid or not
Arguments:	None
Returns:	[Boolean] True if they are all valid, false if not
**********************************************************************************************************/
function ValidateEmailList(){
  var emails = $('#export-email-list').val().split(";");
  var bCount = 0;
  for(i=0;i<emails.length;i++){
    if(emails[i]==''){continue;}
    if(!isValidEmail(emails[i])){ bCount++; } 
  }
  if(bCount>0){return false;}else{return true;} 
}

/**********************************************************************************************************
Function Name:
  ValidateExportForm
Description:
  Make sure the export form is filled in properly before doing anything
Arguments:	None
Returns:	[Boolean] True if the form is ok, false if not
**********************************************************************************************************/
function ValidateExportForm(){
  if($('#export-file-name').val() == ''){ return false; }
  if($('#export-select-method :selected').val() == 'email'){
    if($('#export-email-list').val() == ''){ return false; }
    if(!ValidateEmailList()){ return false; }
  }
  return true;
}

/**********************************************************************************************************
Function Name:
  ExportDownload
Description:
  Redirect to the crafted URL to start a download
Arguments:	None
Returns:	None
**********************************************************************************************************/
function ExportDownload(){
  var url_string = GetDataString();
  CloseExportDialog();
  window.location = $('#export-page').val() + '?' + url_string;
}

/**********************************************************************************************************
Function Name:
  ParseExportXml
Description:
  Find out what actions to take based on the XML returned from the ajax call for the email
Arguments:	[Object] XML object from the ajax call
Returns:	None
**********************************************************************************************************/
function ParseExportXml(xmlMsg){
  if($(xmlMsg).find('smtpstatus').text() == 'disabled'){
    $('#export-sending').fadeOut('fast'); 
    $('#export-result').html('!! No SMTP connection configured !!');
  }
  else if ( $(xmlMsg).find('result').text() == 'false'){
    $('#export-sending').fadeOut('fast'); 
    $('#export-result').html('!! Errors encountered while sending emails !!');
    $(xmlMsg).find('email').each(function(){
      $('#export-result').append('<br/>Failed sending to: ' + $(this).text());
    });
  }
  else {
    CloseExportDialog();
  }
}

/**********************************************************************************************************
Function Name:
  ExportEmail
Description:
  Try to send the export as an email via an ajax call
Arguments:	None
Returns:	None
**********************************************************************************************************/
function ExportEmail(){
  var data = GetDataString();
  $.ajax({
    'url': $('#export-page').val(),
    'type': 'GET',
    'data': data,
    'beforeSend': function(){ $('#export-result').html(''); $('#export-sending').show(); },
    'success': function(msg){ ParseExportXml(msg); },
    'error': function(){ $('#export-sending').fadeOut('fast'); $('#export-result').html('An unexpected error occured.'); }
  });
}

/**********************************************************************************************************
  Jquery 'ready' event. Executed when the DOM can be traversed/manipulated
**********************************************************************************************************/
$(document).ready(function() {
  $("#export-dialog").dialog({
   width: 425,
   bgiframe: true,
   draggable: false,
   resizable: false,
   autoOpen: false,
   modal: true,
   position: ['center','middle'],
   buttons: {
     Ok: function() {
      if($('#export-select-method :selected').val() == 'email'){
         if(ValidateExportForm()){ ExportEmail(); }
       }
       else{
         if(ValidateExportForm()){ ExportDownload() }
       }
      },
     Cancel: function() { CloseExportDialog(); }
   }
  });

  $("#export-dialog").dialog({ beforeclose: function() { CloseExportDialog(); } });
  $(".ui-dialog-titlebar-close").click(function() { CloseExportDialog(); });

  var filename = ($('#export_modal_info [name=system-name]').val()!=undefined) ?
    $('#export_modal_info [name=system-name]').val() : 'export';
  $("#export-file-name").val(filename);
  $('#export-email-list').tooltip();

  // Toggle email input visibility
  $("#export-select-method").change(function () { $('#export-email').toggle(); });

  // Validate emails while typing
  $("#export-email-list").keyup(function () {
    $('#export-email-list').toggleClass('email-list-invalid', !ValidateEmailList());
  });

  // Set the onclick events for the links
  $('a.get-system-pdf').click(function () { ExportPageToPdf('n'); return false; });
  $('a.get-view-pdf').click(function ()   { ExportPageToPdf('y'); return false; });
  $('a.get-view-csv').click(function ()   { ExportPageToCsv();    return false; });

  // Try to keep the modal centered. Doesn't seem to work in IE?
  $(document).scroll(function() {
    if($("#export-dialog").dialog('isOpen')){ $('#export-dialog').dialog('option', 'position', ['center','middle']); }
  });

});

/**********************************************************************************************************
Function Name:
  ExportPageToPdf
Description:
  Called when the 'Export Page to PDF' or 'PDF Report' links are pressed
Arguments:	[String] Passed 'y' if this is a 'Export Page to PDF' link
Returns:	[Boolean] False 
**********************************************************************************************************/
function ExportPageToPdf(viewLink){
  (viewLink=='y') ? $(".pdf-sidemenu-select").hide() : $(".pdf-sidemenu-select").show();
  (viewLink=='y') ? $('#export-page-form').val('y')  : $('#export-page-form').val('n');
  $('#export-file-ext').html('.pdf');
  $('#export-page').val('system_export.php');
  $("#export-dialog").dialog('option','title','Export PDF-Report : ' + $('#export_modal_info input[name=system-name]').val());
  $("#export-dialog").dialog('open');
  return false;
}

/**********************************************************************************************************
Function Name:
  ExportPageToCsv
Description:
  Called when the 'Export Page to CSV' links are pressed
Arguments:	None
Returns:	[Boolean] False
**********************************************************************************************************/
function ExportPageToCsv() {
  $('#export-page').val('list_export.php');
  $('#export-page-form').val('n');
  $('#export-file-ext').html('.csv');
  $(".pdf-sidemenu-select").hide();
  $("#export-dialog").dialog('option','title','Export CSV');
  $("#export-dialog").dialog('open');
  return false;
}
