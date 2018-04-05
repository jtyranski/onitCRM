<?php include "includes/header.php"; ?>
<?php
$prospect_id = $_GET['prospect_id'];

$sql = "SELECT *, date_format(lastaction, \"%m/%d/%y\") as datepretty from prospects where prospect_id='$prospect_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$prospect_status = stripslashes($record['prospect_status']);
$company_name = stripslashes($record['company_name']);
$address = stripslashes($record['address']);
$city = stripslashes($record['city']);
$state = stripslashes($record['state']);
$zip = stripslashes($record['zip']);
  

$lastaction = $record['datepretty'];

$logo = $record['logo'];
$manager = $record['user_id'];
$google_search = $company_name . " " . $address . " " . $city . " " . $state;
$superpages = "http://yellowpages.superpages.com/listings.jsp?";
$superpages .= "STYPE=S&search=Find+It&SRC=&channelId=&sessionId=&MCBP=true&CS=L&C=" . $company_name;
$superpages .= "&L=" . $city . " " . $state . "&searchbutton.x=48&searchbutton.y=22&searchbutton=Find+It";
$googlemap = "http://maps.google.com/maps?q=";
$googlemap .= $address . ", " . $city . ", " . $state . ", " . $zip;
?>
<table class="main" width="100%">
<tr>
<td valign="top">
<?php include "includes/main_nav.php"; ?>
</td>
<td align="right" valign="top">

</td>
</tr>
</table>
<div class="breadcrumb">
<a href="company.php" class="breadcrumb">Company</a> > <a href="company_details.php?prospect_id=<?=$prospect_id?>" class="breadcrumb">
<?=$company_name?></a> > Edit</div>
<form action="company_details_edit_action.php" method="post">
<input type="hidden" name="prospect_id" value="<?=$prospect_id?>">

<input type="text" class="largerbox" name="company_name" value="<?=$company_name?>"><br>
<input type="text" class="largerbox" name="address" value="<?=$address?>"><br>
<input type="text" class="largerbox" name="city" value="<?=$city?>"><br>
<select name="state">
<?php
$sql2 = "SELECT * from states order by state_name";
$result2 = executequery($sql2);
while($record2 = go_fetch_array($result2)){
  ?><option value="<?=$record2['state_code']?>"<?php if ($state==$record2['state_code']) echo " selected"; ?>><?=$record2['state_name']?></option>
  <?php
}
?>
</select>
<br>
<input type="text" class="largerbox" name="zip" value="<?=$zip?>"><br>

<br>
<input type="submit" name="submit1" value="Submit">
</form>

<?php include "includes/footer.php"; ?>