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
		<div class="alert alert-error hide"><span>Opps! Change a few things up and try submitting again.</span><a href="#" class="close" data-dismiss="alert">&times;</a></div>

		<form name="data_form" id="data_form" method="post" onsubmit="submit_form();return false;" class="form-horizontal">
			<input type="text" id="input_no" name="input_no" placeholder="Order #" required maxlength="10" style="margin-bottom: 10px;"/>
			<table class="table table-bordered table-striped" id="item_data">
				<thead><tr>
					<th width="5%">#</th>
					<th width="30%">ITEM</th>
					<th>LEFT</th>
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
			<td><input type="text" name="item[_IDX_][sku]" value="" placeholder="SKU #" class="input-large sku_input" required maxlength="16" onblur="get_sku_left(this);"/></td>
			<td class="sku_left">0</td>
			<td><input type="number" name="item[_IDX_][box_num]" value="" placeholder="Boxes #" class="input-mini box_num" min=1 required/></td>
			<td><input type="text" name="item[_IDX_][item_from]" value="" class="input-mini" placeholder="From" maxlength="32" required /></td>
			<td><input type="text" name="item[_IDX_][item_to]" value="" class="input-mini" placeholder="To" maxlength="32" required /></td>
			<td><input type="text" name="item[_IDX_][remark]" value="" class="input-medium" placeholder="Remark" maxlength="200" /></td></tr>
		</table>
	</div>
</div>

<script type="text/javascript" src="media/js/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="media/js/bootstrap.min.js"></script>
<script type="text/javascript" src="http://v2.bootcss.com/assets/js/bootstrap-typeahead.js"></script>
<script type="text/javascript">
function submit_form(){
	$(".alert").hide();
	if(!confirm("Confirm submit the form?")) return false;

	$.post("whout/create/", $("#data_form").serialize(), function(response){
		if(1==response.status){
			$(".alert-success").show();
			$("#input_no").val('');
			$("#item_data tbody").empty();
			add_row();
		}
		else{
			$(".alert-error span").text(response.msg);
			$(".alert-error").show();
		}
	})
}

function get_sku_left(item){
	var sku = $(item).val();
	var tr = $(item).parents('tr');
	if(0==sku.length) return;
	$.post("whout/sku_left",{'item_sku':sku}, function(data){
		if(0==data.status){
			tr.find(".sku_left").text(0);
			alert('No items found.');
			$(item).focus();
		}
		else{
			tr.find(".sku_left").text(data.left);
			tr.find(".box_num").attr({"max":data.left});
		}
	});
}

function add_row(){
	var tb = $("#item_data");
	var tmplt = $("#tr_template tbody").html().replace(/_IDX_/g, (tb.find("tr").length));
	tb.append(tmplt);
	bind_typeahead()
}

function bind_typeahead(){
	$('.sku_input').typeahead({
	    source: function (query, process) {
	        var parameter = {query: query};
	        $.get('warehouse/sku_list', parameter, function (data) {
	            process(data);
	        });
	    }
	});
}

$(function(){
	add_row();

	$("input[type=number],.sku_left").css({"text-align":"right"});
});

</script>
</body>
</html>