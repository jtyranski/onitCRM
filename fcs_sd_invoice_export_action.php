<?php
include "includes/functions.php";

$leak_id = go_escape_string($_POST['leak_id']);
$fields = $_POST['fields'];
$name = $_POST['name'];

$submit1 = $_POST['submit1'];
$export_type = $_POST['export_type'];
if($export_type=="") $export_type = "xml";

$am_leakcheck = array("leak_id", "invoice_id", "custom_field", "desc_work_performed", "labor_cost", "travel_cost", "materials_cost", "other_cost", "tax_amount", "tax_percent", "invoice_total");

if($submit1 != ""){
  if(is_array($fields)){
    for($x=0;$x<sizeof($fields);$x++){
      $fieldname = $fields[$x];
	  if(in_array($fieldname, $am_leakcheck)) $fieldname = "a." . $fieldname;
  	  if($fieldname=="invoice_sent_date") $fieldname = "date_format(a.invoice_sent_date, \"%m/%d/%Y\") as invoice_sent_date";
	  if($fieldname=="invoice_due_date") $fieldname = "date_format(a.invoice_due_date, \"%m/%d/%Y\") as invoice_due_date";
	  
	  if($fieldname=="companyname") $fieldname = "c.company_name as companyname";
	  if($fieldname=="companyaddress") $fieldname = "c.address as companyaddress";
	  if($fieldname=="companycity") $fieldname = "c.city as companycity";
	  if($fieldname=="companystate") $fieldname = "c.state as companystate";
	  if($fieldname=="companyzip") $fieldname = "c.zip as companyzip";
	  
	  if($fieldname=="propertyname") $fieldname = "b.site_name as propertyname";
	  if($fieldname=="propertyaddress") $fieldname = "b.address as propertyaddress";
	  if($fieldname=="propertycity") $fieldname = "b.city as propertycity";
	  if($fieldname=="propertystate") $fieldname = "b.state as propertystate";
	  if($fieldname=="propertyzip") $fieldname = "b.zip as propertyzip";
	  
	  $query .= $fieldname . ", ";
    }
  }
  $query = go_reg_replace(", $", "", $query);
  $sql = "SELECT $query from am_leakcheck a, properties b, prospects c where a.leak_id='$leak_id' and a.property_id=b.property_id and a.prospect_id=c.prospect_id";
  $result = executequery($sql);
  $record = go_fetch_array($result);

  /*
  $doc = new DomDocument('1.0');
  $doc->formatOutput = true;
  $root = $doc->createElement('root');
  $root = $doc->appendChild($root);
  if(is_array($fields)){
    for($x=0;$x<sizeof($fields);$x++){
      $fieldname = $fields[$x];
	  $child = $doc->createElement($name[$fieldname]);
      $child = $root->appendChild($child);
	  $value = $doc->createTextNode($record[$fieldname]);
      $value = $child->appendChild($value);
	  //echo "<" . $name[$fieldname] . ">" . $record[$fieldname] . "</" . $name[$fieldname] . ">\n";
	}
  }
  $xml_string = $doc->saveXML();
  $file = "uploaded_files/invoice_export/invoice_$leak_id.xml";
  $doc->save($file);
  
  if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
  }
  */
  
  $numformat = array("labor_cost", "other_cost", "materials_cost", "travel_cost", "tax_amount", "invoice_total");
  
  if($export_type=="xml"){
  $file = "uploaded_files/invoice_export/invoice_$leak_id.xml";
  $fp = fopen($file, 'w');
  fwrite($fp, "<Invoice>\n");
  if(is_array($fields)){
    for($x=0;$x<sizeof($fields);$x++){
      $fieldname = $fields[$x];
	  $value = $record[$fieldname];
	  if(in_array($fieldname, $numformat)) $value = number_format($value, 2);
	  
	  fwrite($fp, "<" . $name[$fieldname] . ">" . $value . "</" . $name[$fieldname] . ">\n");
	}
  }
  fwrite($fp, "</Invoice>\n");
  fclose($fp);
  }
  
  if($export_type=="csv"){
  $file = "uploaded_files/invoice_export/invoice_$leak_id.csv";
  $fp = fopen($file, 'w');
  if(is_array($fields)){
    $line = "";
    for($x=0;$x<sizeof($fields);$x++){
      $fieldname = $fields[$x];
	  $line .= "\"" . $name[$fieldname] . "\"";
	  if($x<sizeof($fields)) $line .= ",";
	}
	$line .= "\n";
	fwrite($fp, $line);
	
	$line = "";
    for($x=0;$x<sizeof($fields);$x++){
      $fieldname = $fields[$x];
	  $value = $record[$fieldname];
	  if(in_array($fieldname, $numformat)) $value = number_format($value, 2);
	  $line .= "\"" . $value . "\"";
	  if($x<sizeof($fields)) $line .= ",";
	}
	$line .= "\n";
	fwrite($fp, $line);
  }
  fclose($fp);
  }
  
  if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
  }
}



?>
