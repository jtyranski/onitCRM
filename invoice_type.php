<?php
include "includes/header_white.php";
?>
<div style="padding-bottom:20px;"><a href="admin_info.php">Back to Company Info</a></div>

<div style="font-family:arial,helvetica,sans-serif;">
    <div style="padding-left:10px;"><span>Type</span><span style="padding-left:130px;">Gen Ledger Code</span></div>
    <form action="invoice_type.php" method="POST" name="invoice_type">
      <div style="padding-left:10px;">
        <span><input id="invoice_type" type="textbox" name="invoice_type" size="20" /></span>
        <span><input id="gen_ledger_code" type="textbox" name="gen_ledger_code" size="10" /></span>
        <span style="padding-left:10px;"><input type="submit" name="submit" value="Add" /></span>
      </div>
    </form>
    <div>
    <?php  // get custom invoice_types (based on T&M type)
    $sql = "SELECT * FROM invoice_types WHERE master_id='" . $SESSION_MASTER_ID . "' AND display=1";
    $result = mysql_query($sql);
    if(!$result) { echo "<p style='color:red;'>Could not successfully run query ($sql)" . mysql_error() ."</p>"; }
    // if no invoice types are found, let user know the list is empty
    if(mysql_num_rows($result)==0) { echo "<p style='color:red;'>No Invoice Types found</p>"; }
    while($row = mysql_fetch_assoc($result)) {
      echo "<p style='padding-left:30px;'><a style='color:red;padding-right:10px;' href='del_invoice_type.php?id=" .$row['id'] ."' onclick='return del_type();'>x</a>". $row['invoice_type'] ." [". $row['general_ledger'] ."]</p>";
    }
    ?>
    </div>
</div>

<?php
if($_POST) {
  echo "<div style='font-family:arial,helvetica,sans-serif;'>";
  if($_POST['invoice_type']=="") {
    echo "<p style='color:red;'>A Type name must be specified</p>";
  } else {
    $invoice_type = $_POST['invoice_type'];
  }
  if($_POST['gen_ledger_code']=="") {
    echo "<p style='color:red;'>A General Ledger Code must be entered</p>";
  } else {
    $gen_ledger_code = $_POST['gen_ledger_code'];
  }

  $sql = "SELECT id FROM invoice_types WHERE master_id='" . $SESSION_MASTER_ID . "' AND display=1 AND (invoice_type='". $invoice_type ."' OR general_ledger='". $gen_ledger_code ."')";
  $result = mysql_query($sql);
  if(mysql_num_rows($result) > 0) {
    echo "<p style='color:red;'>That Invoice Type or General Ledger Code already exists</p>";
  } else {
    $sql = "INSERT INTO invoice_types (invoice_type, master_id, general_ledger) VALUES ('". $invoice_type ."','" . $SESSION_MASTER_ID . "','". $gen_ledger_code ."')";
    $result = mysql_query($sql);
    echo "New Type added successfully";
    echo "<meta http-equiv='refresh' content='0'>";
  }
  echo "</div>";
}
?>
<script>
function del_type()
{
  if(confirm("Are you sure?")) {
    return true;
  } else {
    return false;
  }
}
</script>


