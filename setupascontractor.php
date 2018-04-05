<?php include "includes/header.php"; ?>
<?php
 // this is just for fcs login
$x = $_GET['x'];
if($x=="") $x = 0;
$step = $x + 1;
$total = sizeof($_SESSION['list_prospect_id']);
$prospect_id = $_SESSION['list_prospect_id'][$x];

$sql = "SELECT company_name, address, city, state, zip from prospects where prospect_id='$prospect_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$company_name = stripslashes($record['company_name']);
$address = stripslashes($record['address']);
$city = stripslashes($record['city']);
$state = stripslashes($record['state']);
$zip = stripslashes($record['zip']);

?>


<div align="center">
  <div class="whiteround" style="height:800px;">
  <div align="left">
  <form action="setupascontractor_action.php" method="post">
  <input type="hidden" name="prospect_id" value="<?=$prospect_id?>">
  <input type="hidden" name="x" value="<?=$x?>">
  <strong>Create a New Core (<?=$step?> of <?=$total?>)</strong><br>
  <table class="main">

  <tr>
  <td align="right">Company Name</td>
  <td><input type="text" name="master_name" value="<?=$company_name?>" size="40"></td>
  </tr>
  <tr>
  <td align="right">Address</td>
  <td><input type="text" name="address" value="<?=$address?>" size="40"></td>
  </tr>
  <tr>
  <td align="right">City</td>
  <td><input type="text" name="city" value="<?=$city?>" size="40"></td>
  </tr>
  <tr>
  <td align="right">State</td>
  <td>
  <select name="state">
  <option value="">Select a State</option>
  <?php
  $sql2 = "SELECT * from states order by state_name";
  $result2 = executequery($sql2);
  while($record2 = go_fetch_array($result2)){
    ?><option value="<?=$record2['state_code']?>"<?php if ($state==$record2['state_code']) echo " selected"; ?>><?=$record2['state_name']?></option>
    <?php
  }
  ?>
  </select>
  </td>
  </tr>

  <tr>
  <td align="right">Zip</td>
  <td><input type="text" name="zip" value="<?=$zip?>" size="40"></td>
  </tr>
  </table>
  
  <table class="main" cellpadding="4">
  <tr>
  <td>First Name</td>
  <td>Last Name</td>
  <td>Email</td>
  <td>Admin</td>
  <td>Password</td>
  </tr>
  <?php
  $list = GetContacts("", $prospect_id);
  for($y = 0;$y<sizeof($list);$y++){
    ?>
	<tr>
	<td><input type="text" name="firstname[]" value="<?=$list[$x]['firstname']?>"></td>
	<td><input type="text" name="lastname[]" value="<?=$list[$x]['lastname']?>"></td>
	<td><input type="text" name="email[]" value="<?=$list[$x]['email']?>" size="40"></td>
	<td>
	<select name="admin[]">
	<option value="1" selected="selected">Yes</option>
	<option value="0">No</option>
	</select>
	</td>
	<td><input type="text" name="password"></td>
	</tr>
	<?php
  }
  ?>
  <tr>
	<td><input type="text" name="firstname[]"></td>
	<td><input type="text" name="lastname[]"></td>
	<td><input type="text" name="email[]" size="40"></td>
	<td>
	<select name="admin[]">
	<option value="1" selected="selected">Yes</option>
	<option value="0">No</option>
	</select>
	</td>
	<td><input type="text" name="password"></td>
  </tr>
  </table>
  <input type="submit" name="submit1" value="Create">
  </form>
  
  </div>
  </div>
</div>

<?php include "includes/footer.php"; ?>