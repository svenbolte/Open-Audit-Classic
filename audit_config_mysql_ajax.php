<?php
require_once "include_config.php";
require_once "include_functions.php";
require_once "include_audit_functions.php";

function Add_Audit_Mysql($db,$database) {
  mysqli_select_db($db,$mysqli_database);

  $table  = $_POST['table'];
  $field  = $_POST['field'];
  $data   = $_POST['data'];
  $sort   = $_POST['sort'];
  $id     = $_POST['id'];

  if ( $id == 'new' ) {
    $sql = "INSERT INTO mysqli_queries ( mysqli_queries_table, mysqli_queries_field,
                                            mysqli_queries_data , mysqli_queries_sort   
            VALUES ( '{$table}','{$field}','{$data}','{$sort}' )";
  }
  else {
    $sql  = "UPDATE `mysqli_queries` 
             SET mysqli_queries_field = '{$field}', mysqli_queries_table = '{$table}',
                 mysqli_queries_data = '{$data}' , mysqli_queries_sort = '{$sort}'
             WHERE mysqli_queries_id = '{$id}'";
  }

  mysqli_query($db,$sql) or die("Could not add mysql query options: " . mysqli_error($db) . "<br>");
  $form_action = ( $_POST['form_action'] == "edit" ) ? 'updated' : 'added';
}

$table  = $_POST['table'];
$action = $_POST['action'];
$db=GetOpenAuditDbConnection();;

switch ($action) {
  case "get_fields":
    mysqli_select_db($db,$mysqli_database);
    $field_id = ( isset($_POST['field_id']) ) ? $_POST['field_id'] : "fields_{$table}";
    $select = "<select class=\"mysql\" id=\"{$field_id}\">";
    if ( ! isset($_POST['add_query_row']) ) {
      $select .= "<option value=\"nothing\" SELECTED>Select Field</option>
                 <option value=\"nothing\">-------</option>";
    }
    if ( $table != "nothing" ) {
      $fields = Get_mysqli_Fields($db,$table);
      foreach ( $fields as $field ) {
        $select .= "<option value=\"{$field}\">{$field}</option>";
      }
    }
    $select .= "</select>";
    echo $select;
    break;
  case "add_query":
    Add_Audit_Mysql($db,$mysqli_database);
    break;
}

?>
