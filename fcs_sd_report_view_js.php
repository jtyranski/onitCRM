<script>
function editField(field){
  url="fcs_sd_invoice_edit.php?leak_id=<?=$leak_id?>&action=form&field=" + field;
  newdesc = document.getElementById('desc_work_performed').value;
  newdesc = newdesc.replace(/\&/g, "AMPERSAND");
  newdesc = newdesc.replace(/\#/g, "POUNDSIGN");
  newdesc = newdesc.replace(/\"/g, "DBLQUOTE");
  newdesc = newdesc.replace(/\n/g, "NEWLINE");
  url=url+"&desc_work_performed=" + newdesc;
  newbillto = document.getElementById('billto').value;
  newbillto = newbillto.replace(/\&/g, "AMPERSAND");
  newbillto = newbillto.replace(/\#/g, "POUNDSIGN");
  newbillto = newbillto.replace(/\"/g, "DBLQUOTE");
  newbillto = newbillto.replace(/\n/g, "NEWLINE");
  url=url+"&billto=" + newbillto;
  
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
  //document.getElementById('invoice_debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function updateField(f){
  
  field = f;
  
  
  if(field=="simple_invoice"){
    x = document.getElementById('simple_invoice');
    if(x.checked==true){
      newvalue = 1;
    }
    else {
      newvalue = 0;
    }
  }
  else {
    newvalue = document.getElementById('newvalue').value;
    newvalue = newvalue.replace("&", "AMPERSAND");
  }
  
  url = "fcs_sd_invoice_edit.php?leak_id=<?=$leak_id?>&action=update&field=" + field + "&newvalue=" + newvalue;
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function editFieldMat(field, id){
  url="fcs_sd_invoice_edit.php?leak_id=<?=$leak_id?>&action=formmat&field=" + field + "&mat_id=" + id;
  newdesc = document.getElementById('desc_work_performed').value;
  newdesc = newdesc.replace(/\&/g, "AMPERSAND");
  newdesc = newdesc.replace(/\#/g, "POUNDSIGN");
  newdesc = newdesc.replace(/\"/g, "DBLQUOTE");
  newdesc = newdesc.replace(/\n/g, "NEWLINE");
  url=url+"&desc_work_performed=" + newdesc;
  newbillto = document.getElementById('billto').value;
  newbillto = newbillto.replace(/\&/g, "AMPERSAND");
  newbillto = newbillto.replace(/\#/g, "POUNDSIGN");
  newbillto = newbillto.replace(/\"/g, "DBLQUOTE");
  newbillto = newbillto.replace(/\n/g, "NEWLINE");
  url=url+"&billto=" + newbillto;
  
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
  //document.getElementById('invoice_debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function updateFieldMat(f, id){
  
  field = f;
  newvalue = document.getElementById('newvalue').value;
  newvalue = newvalue.replace(/\&/g, "AMPERSAND");
  newvalue = newvalue.replace(/\#/g, "POUNDSIGN");
  newvalue = newvalue.replace(/\"/g, "DBLQUOTE");
  
  
  url = "fcs_sd_invoice_edit.php?leak_id=<?=$leak_id?>&action=updatemat&field=" + field + "&newvalue=" + newvalue + "&mat_id=" + id;
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function editFieldOther(field, id){
  url="fcs_sd_invoice_edit.php?leak_id=<?=$leak_id?>&action=formother&field=" + field + "&other_id=" + id;
  newdesc = document.getElementById('desc_work_performed').value;
  
  
  newdesc = newdesc.replace(/\&/g, "AMPERSAND");
  newdesc = newdesc.replace(/\#/g, "POUNDSIGN");
  newdesc = newdesc.replace(/\"/g, "DBLQUOTE");
  newdesc = newdesc.replace(/\n/g, "NEWLINE");
  url=url+"&desc_work_performed=" + newdesc;
  
  newbillto = document.getElementById('billto').value;
  newbillto = newbillto.replace(/\&/g, "AMPERSAND");
  newbillto = newbillto.replace(/\#/g, "POUNDSIGN");
  newbillto = newbillto.replace(/\"/g, "DBLQUOTE");
  newbillto = newbillto.replace(/\n/g, "NEWLINE");
  url=url+"&billto=" + newbillto;
  //alert(url);
  
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
  //document.getElementById('invoice_debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function updateFieldOther(f, id){
  
  field = f;
  newvalue = document.getElementById('newvalue').value;
  newvalue = newvalue.replace(/\&/g, "AMPERSAND");
  newvalue = newvalue.replace(/\#/g, "POUNDSIGN");
  newvalue = newvalue.replace(/\"/g, "DBLQUOTE");
  
  
  url = "fcs_sd_invoice_edit.php?leak_id=<?=$leak_id?>&action=updateother&field=" + field + "&newvalue=" + newvalue + "&other_id=" + id;
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function viewedit(){
  url="fcs_sd_invoice_edit.php?leak_id=<?=$leak_id?>&action=viewedit";
  newdesc = document.getElementById('desc_work_performed').value;
  newdesc = newdesc.replace(/\&/g, "AMPERSAND");
  newdesc = newdesc.replace(/\#/g, "POUNDSIGN");
  newdesc = newdesc.replace(/\"/g, "DBLQUOTE");
  newdesc = newdesc.replace(/\n/g, "NEWLINE");
  url=url+"&desc_work_performed=" + newdesc;
  newbillto = document.getElementById('billto').value;
  newbillto = newbillto.replace(/\&/g, "AMPERSAND");
  newbillto = newbillto.replace(/\#/g, "POUNDSIGN");
  newbillto = newbillto.replace(/\"/g, "DBLQUOTE");
  newbillto = newbillto.replace(/\n/g, "NEWLINE");
  url=url+"&billto=" + newbillto;
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
  //document.getElementById('invoice_debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function viewedit_submit(){
  gosubmit("returnviewmode");
}

function refreshinvoice(){
  url="fcs_sd_invoice_edit.php?leak_id=<?=$leak_id?>&action=refresh";
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
  //document.getElementById('invoice_debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function lockinvoice(){
  url="fcs_sd_invoice_edit.php?leak_id=<?=$leak_id?>&action=lock";
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
  //document.getElementById('invoice_debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function additem(){
  document.getElementById('invoice_additem').style.display="none";
  document.getElementById('invoice_additem_info').style.display="";
}
function addother(){
  document.getElementById('invoice_addother').style.display="none";
  document.getElementById('invoice_addother_info').style.display="";
}

function invoicenewmat(){
  mat_description = document.getElementById('mat_description').value;
  mat_quantity = document.getElementById('mat_quantity').value;
  mat_unit = document.getElementById('mat_unit').value;
  mat_cost = document.getElementById('mat_cost').value;
  mat_description = mat_description.replace(/\&/g, "AMPERSAND");
  mat_description = mat_description.replace(/\#/g, "POUNDSIGN");
  mat_description = mat_description.replace(/\"/g, "DBLQUOTE");
  
  url="fcs_sd_invoice_edit.php?leak_id=<?=$leak_id?>&action=newmaterial&mat_description=" + mat_description + "&mat_quantity=" + mat_quantity + "&mat_unit=" + mat_unit + "&mat_cost=" + mat_cost;
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
  //document.getElementById('invoice_debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function invoicedelmat(mat_id){
  
  url="fcs_sd_invoice_edit.php?leak_id=<?=$leak_id?>&action=delmat&mat_id=" + mat_id;
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
  //document.getElementById('invoice_debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function invoicenewother(){
  other_description = document.getElementById('other_descriptionx').value;
  other_quantity = document.getElementById('other_quantityx').value;
  other_unit = document.getElementById('other_unitx').value;
  other_cost = document.getElementById('other_costx').value;
  other_description = other_description.replace(/\&/g, "AMPERSAND");
  other_description = other_description.replace(/\#/g, "POUNDSIGN");
  other_description = other_description.replace(/\"/g, "DBLQUOTE");
  
  url="fcs_sd_invoice_edit.php?leak_id=<?=$leak_id?>&action=newother&other_description=" + other_description + "&other_quantity=" + other_quantity + "&other_unit=" + other_unit + "&other_cost=" + other_cost;
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
  //document.getElementById('invoice_debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function invoicedelother(id){
  
  url="fcs_sd_invoice_edit.php?leak_id=<?=$leak_id?>&action=delother&other_id=" + id;
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
  //document.getElementById('invoice_debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function set_taxable(x, field){
  if(x.checked==true){
    y = 1;
  }
  else {
    y = 0;
  }
  url="fcs_sd_invoice_edit.php?leak_id=<?=$leak_id?>&action=set_taxable&field=" + field + "&checked=" + y;
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
  //document.getElementById('invoice_debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function refreshdocuments(){
  url="fcs_sd_invoice_documents.php?leak_id=<?=$leak_id?>&action=refresh";
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
  //document.getElementById('invoice_debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function deletedocuments(photo_id){
  url="fcs_sd_invoice_documents.php?leak_id=<?=$leak_id?>&action=delete&photo_id=" + photo_id;
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
  //document.getElementById('invoice_debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function mat_dropdown_go(x){
  y = x.value;
  url="fcs_sd_invoice_materials.php?mat_id=" + y;
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
  //document.getElementById('invoice_debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function mat_category_dropdown_go(x){
  y = x.value;
  url="fcs_sd_invoice_materials_cat.php?category_id=" + y;
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
  //document.getElementById('invoice_debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}
</script>