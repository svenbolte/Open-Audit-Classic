<?php 

$page = "admin";
require_once "include.php";
require_once "include_audit_functions.php";

?>
<link media="screen" rel="stylesheet" type="text/css" href="audit_command.css"/>
<script type='text/javascript' src="javascript/audit_cmd.js"></script>
<script type='text/javascript' src="javascript/ajax.js"></script>
<body>
<td valign="top">
<div class="main_each">
  <table width="100%" border="0" style="height: 70px">
    <tr><td rowspan="2" class="contenthead">Edit Commands</td></tr>
  </table>
  <label>Command Name</label>
  <input size="15" type="text" id="input-cmdname"/><br>
  <br>
  <label>Command</label>
  <div><input size="30" type="text" id="input-cmd"/>
    <img src="images/add.png" onClick="addToCommands('cmd_table')" id="add-img"/>
  </div><br>
  <form action="javascript:submitCommands();" method="post" id="form_cmd">
    <table id="cmd_table">
    <?php 
      $db=GetOpenAuditDbConnection();;
      @get_command_info($db);
    ?>
    </table>
    <br><br>
    <input value="Submit Changes" type="submit" id="submit-button"/>
    <br><br>
<center><span id="form_result"></span></center>
  </form>
</div>
</td>
<?php // include "include_right_column.php"; ?>
</body>
</html>
