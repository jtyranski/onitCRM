<?php include "includes/functions.php"; ?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<script>
function DelItem(x){
  cf = confirm("Are you sure you want to delete this video?");
  if(cf){
    document.location.href="youtube_delete.php?video_id=" + x;
  }
}
</script>
<?php

$order_by = $_GET['order_by'];
$order_by2 = $_GET['order_by2'];
if($order_by == "") $order_by = "video_id";
if($order_by2 == "") $order_by2 = "desc";
if($order_by2 == "asc"){
  $function = "compare";
}
else {
  $function = "rcompare";
}

function compare($x, $y)
{
global $order_by;
if ( strtoupper($x[$order_by]) == strtoupper($y[$order_by]) )
 return 0;
else if ( strtoupper($x[$order_by]) < strtoupper($y[$order_by]) )
 return -1;
else
 return 1;
}

function rcompare($x, $y)
{
global $order_by;
if ( strtoupper($x[$order_by]) == strtoupper($y[$order_by]) )
 return 0;
else if ( strtoupper($x[$order_by]) < strtoupper($y[$order_by]) )
 return 1;
else
 return -1;
}

$counter = 0;
$sql = "SELECT a.video_name, a.page, a.video_id, b.master_name, c.company_name, a.master_id, a.prospect_id 
from videos_embed a, master_list b, prospects c
where a.master_id=b.master_id and a.prospect_id=c.prospect_id
order by a.video_id desc";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $video_name = stripslashes($record['video_name']);
  $page = stripslashes($record['page']);
  $video_id = stripslashes($record['video_id']);
  $master_name = stripslashes($record['master_name']);
  $company_name = stripslashes($record['company_name']);
  $master_id = stripslashes($record['master_id']);
  $prospect_id = stripslashes($record['prospect_id']);
  
  $row[$counter]['video_name'] = $video_name;
  $row[$counter]['page'] = $page;
  $row[$counter]['video_id'] = $video_id;
  $row[$counter]['master_name'] = $master_name;
  $row[$counter]['company_name'] = $company_name;
  $row[$counter]['master_id'] = $master_id;
  $row[$counter]['prospect_id'] = $prospect_id;
  
  $counter++;
}
usort($row, $function);

?>
<a href="youtube_edit.php?video_id=new">Add a new video</a><br>
<table class="main" width="100%">
<tr>
<td><strong><?=sort_header("video_name", "Name")?></strong></td>
<td><strong><?=sort_header("master_name", "FCS Client")?></strong></td>
<td><strong><?=sort_header("company_name", "Company")?></strong></td>
<td><strong><?=sort_header("page", "Page")?></strong></td>
<td></td>
<td></td>
<td></td>
</tr>

<?php
for($x=0;$x<sizeof($row);$x++){
?>
  <tr<?php if($x % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td valign="top"><?=$row[$x]['video_name']?></td>
  <td valign="top">
  <?=$row[$x]['master_name']?>
  </td>
  <td valign="top">
  <a href="view_company.php?prospect_id=<?=$row[$x]['prospect_id']?>" target="_top"><?=$row[$x]['company_name']?></a>
  </td>
  <td valign="top"><?=$row[$x]['page']?></td>
  <td valign="top"><a href="youtube_edit.php?video_id=<?=$row[$x]['video_id']?>">edit</a></td>
  <td valign="top"><a href="youtube_view.php?video_id=<?=$row[$x]['video_id']?>">view</a></td>
  <td valign="top"><a href="javascript:DelItem('<?=$row[$x]['video_id']?>')">delete</a></td>
  </tr>
  <?php
}
?>
</table>
  


  
  
  
