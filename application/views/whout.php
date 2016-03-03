<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome_WAREHOUSE-OUT</title>
	<link href="media/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link rel="shortcut icon" href="media/image/favicon.ico" />
	<style type="text/css">
	input[type=number]::-webkit-inner-spin-button,input[type=number]::-webkit-outer-spin-button { 
	  -webkit-appearance: none; 
	  margin: 0; 
	}
	</style>
</head>
<body> 
<div class="container row-fluid" style="width:90%">
	<div class="navbar">
	    <ul class="nav nav-tabs">
	      <li><a href="./">WAREHOUSE</a></li>
	      <li><a href="whin">WAREHOUSE-IN</a></li>
	      <li class="active"><a href="whout">WAREHOUSE-OUT</a></li>
	    </ul>
	</div>

	<div class="clearfix"></div>
	<div class="container">
		<br/>
		<div class="alert alert-success hide">Success! You can continue create new order.<a href="#" class="close" data-dismiss="alert">&times;</a></div>
		<div class="alert alert-error hide">Opps! Change a few things up and try submitting again.<a href="#" class="close" data-dismiss="alert">&times;</a></div>

		<form name="data_form" id="data_form" method="post" onsubmit="submit_form();return false;" class="form-horizontal">
			<input type="text" id="input_no" name="input_no" placeholder="Order #" required maxlength="10" style="margin-bottom: 10px;"/>
			<table class="table table-bordered table-striped" id="item_data">
				<thead><tr>
					<th width="5%">#</th>
					<th width="30%">ITEM</th>
					<th>箱數</th>
					<th>FROM</th> 
					<th>TO</th>
					<th>REMARK</th>
				</tr></thead> 
				<tbody>
				</tbody>
			</table>
			<input type="submit" value="Submit" class="btn btn-primary" />
			<input type="button" value="Add Row" class="btn" onclick="add_row()" />
		</form>
	</div>
	<div class="hide"><table id="tr_template">
		<tr><td>_IDX_</td>
			<td><input type="text" name="item[_IDX_][sku]" value="" placeholder="SKU #" class="input-large" required maxlength="16"/></td>
			<td><input type="number" name="item[_IDX_][box_num]" value="" placeholder="Boxes #" class="input-mini" min=1 required/></td>
			<td><input type="text" name="item[_IDX_][item_from]" value="" class="input-mini" placeholder="From" maxlength="32" required /></td>
			<td><input type="text" name="item[_IDX_][item_to]" value="" class="input-mini" placeholder="To" maxlength="32" required /></td>
			<td><input type="text" name="item[_IDX_][remark]" value="" class="input-medium" placeholder="Remark" maxlength="200" /></td></tr>
		</table>
	</div>
</div>

<script type="text/javascript" src="media/js/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="media/js/bootstrap.min.js"></script>
<script type="text/javascript">
function submit_form(){
	if(!confirm("Confirm submit the form?")) return false;

	$.post("whin/create/", $("#data_form").serialize(), function(response){
		if(1==response.status) $(".alert-success").show();
	})
}

function add_row(){
	var tb = $("#item_data");
	var tmplt = $("#tr_template tbody").html().replace(/_IDX_/g, (tb.find("tr").length));
	tb.append(tmplt);
}

$(function(){
	add_row();

	$("input[type=number]").css({"text-align":"right"});
});

</script>
</body>
</html>