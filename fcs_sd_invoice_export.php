<?php
include "includes/header_white.php";

$leak_id = $_GET['leak_id'];
$sql = "SELECT custom_sd_field from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$custom_sd_field = getsingleresult($sql);

?>
<script>
var form='export'; //Give the form name here

function SetChecked(x,chkName) {
  dml=document.forms[form];
  len = dml.elements.length;
  var i=0;
  for( i=0 ; i<len ; i++) {
    if (dml.elements[i].name==chkName && dml.elements[i].disabled==false) {
      dml.elements[i].checked=x.checked;
    }
  }
}
</script>
<form action="fcs_sd_invoice_export_action.php" method="post" name="export">
<input type="hidden" name="leak_id" value="<?=$leak_id?>">
<div style="position:relative;">
<div style="float:left;">
<table class="main">
<tr>
<td><input type='checkbox' onchange="SetChecked(this, 'fields[]')" checked="checked"></td>
<td><?=$MAIN_CO_NAME?> Fields to export:</td>
<td>Field Names map to:</td>
</tr>
<tr>
<td><input type="checkbox" name="fields[]" value="leak_id" checked="checked"></td>
<td>Dispatch ID #</td>
<td><input type="text" name="name[leak_id]" value="Dispatch ID #"></td>
</tr>
<tr>
<td><input type="checkbox" name="fields[]" value="invoice_id" checked="checked"></td>
<td>Invoice ID #</td>
<td><input type="text" name="name[invoice_id]" value="Invoice ID #"></td>
</tr>
<tr>
<td><input type="checkbox" name="fields[]" value="invoice_sent_date" checked="checked"></td>
<td>Date</td>
<td><input type="text" name="name[invoice_sent_date]" value="Date"></td>
</tr>
<tr>
<td><input type="checkbox" name="fields[]" value="invoice_due_date" checked="checked"></td>
<td>Due Date</td>
<td><input type="text" name="name[invoice_due_date]" value="Due Date"></td>
</tr>
<tr>
<td><input type="checkbox" name="fields[]" value="custom_field" checked="checked"></td>
<td>Custom Invoice Field</td>
<td><input type="text" name="name[custom_field]" value="<?=$custom_sd_field?>"></td>
</tr>
<tr>
<td><input type="checkbox" name="fields[]" value="desc_work_performed" checked="checked"></td>
<td>Description</td>
<td><input type="text" name="name[desc_work_performed]" value="Description"></td>
</tr>
<tr>
<td colspan="2"></td>
</tr>
<tr>
<td><input type="checkbox" name="fields[]" value="companyname" checked="checked"></td>
<td>Company Name</td>
<td><input type="text" name="name[companyname]" value="Company"></td>
</tr>
<tr>
<td><input type="checkbox" name="fields[]" value="companyaddress" checked="checked"></td>
<td>Company Address</td>
<td><input type="text" name="name[companyaddress]" value="Company Address"></td>
</tr>
<tr>
<td><input type="checkbox" name="fields[]" value="companycity" checked="checked"></td>
<td>Company City</td>
<td><input type="text" name="name[companycity]" value="Company City"></td>
</tr>
<tr>
<td><input type="checkbox" name="fields[]" value="companystate" checked="checked"></td>
<td>Company State</td>
<td><input type="text" name="name[companystate]" value="Company State"></td>
</tr>
<tr>
<td><input type="checkbox" name="fields[]" value="companyzip" checked="checked"></td>
<td>Company Zip</td>
<td><input type="text" name="name[companyzip]" value="Company Zip"></td>
</tr>
</tr>
<tr>
<td colspan="2"></td>
</tr>
<tr>
<td><input type="checkbox" name="fields[]" value="propertyname" checked="checked"></td>
<td>Property Name</td>
<td><input type="text" name="name[propertyname]" value="Property"></td>
</tr>
<tr>
<td><input type="checkbox" name="fields[]" value="propertyaddress" checked="checked"></td>
<td>Property Address</td>
<td><input type="text" name="name[propertyaddress]" value="Property Address"></td>
</tr>
<tr>
<td><input type="checkbox" name="fields[]" value="propertycity" checked="checked"></td>
<td>Property City</td>
<td><input type="text" name="name[propertycity]" value="Property City"></td>
</tr>
<tr>
<td><input type="checkbox" name="fields[]" value="propertystate" checked="checked"></td>
<td>Property State</td>
<td><input type="text" name="name[propertystate]" value="Property State"></td>
</tr>
<tr>
<td><input type="checkbox" name="fields[]" value="propertyzip" checked="checked"></td>
<td>Property Zip</td>
<td><input type="text" name="name[propertyzip]" value="Property Zip"></td>
</tr>
</tr>
<tr>
<td colspan="2"></td>
</tr>
<tr>
<td><input type="checkbox" name="fields[]" value="travel_cost" checked="checked"></td>
<td>Travel Cost</td>
<td><input type="text" name="name[travel_cost]" value="Travel Cost"></td>
</tr>
<tr>
<td><input type="checkbox" name="fields[]" value="labor_cost" checked="checked"></td>
<td>Labor Cost</td>
<td><input type="text" name="name[labor_cost]" value="Labor Cost"></td>
</tr>
<tr>
<td><input type="checkbox" name="fields[]" value="materials_cost" checked="checked"></td>
<td>Material Cost</td>
<td><input type="text" name="name[materials_cost]" value="Material Cost"></td>
</tr>
<tr>
<td><input type="checkbox" name="fields[]" value="other_cost" checked="checked"></td>
<td>Other Cost</td>
<td><input type="text" name="name[other_cost]" value="Other Cost"></td>
</tr>
<tr>
<td colspan="2"></td>
</tr>
<tr>
<td><input type="checkbox" name="fields[]" value="tax_percent" checked="checked"></td>
<td>Tax %</td>
<td><input type="text" name="name[tax_percent]" value="Tax Percent"></td>
</tr>
<tr>
<td><input type="checkbox" name="fields[]" value="tax_amount" checked="checked"></td>
<td>Tax Amount</td>
<td><input type="text" name="name[tax_amount]" value="Tax Amount"></td>
</tr>
<tr>
<td colspan="2"></td>
</tr>
<tr>
<td><input type="checkbox" name="fields[]" value="invoice_total" checked="checked"></td>
<td>Invoice Total</td>
<td><input type="text" name="name[invoice_total]" value="Invoice Total"></td>
</tr>
</table>
</div>
<div style="float:left; padding-left:25px;" class="main">
<input type="radio" name="export_type" value="xml" checked="checked">XML<br>
<input type="radio" name="export_type" value="csv">CSV
</div>
</div>
<div style="clear:both;"></div>
<input type="submit" name="submit1" value="Generate">
</form>