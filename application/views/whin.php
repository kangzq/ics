<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome_WAREHOUSE-IN</title>
	<link href="<?php echo base_url();?>media/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link rel="shortcut icon" href="<?php echo base_url();?>media/image/favicon.ico" />
	<style type="text/css">
	input[type=number]::-webkit-inner-spin-button,input[type=number]::-webkit-outer-spin-button { 
	  -webkit-appearance: none; 
	  margin: 0; 
	}
	</style>
</head>
<body> 
<div class="container" style="width:90%">
	<div class="navbar">
	    <ul class="nav nav-tabs" style="width:100%">
	      <li><a href="<?php echo base_url();?>">WAREHOUSE</a></li>
	      <li class="active"><a href="<?php echo base_url();?>whin">WAREHOUSE-IN</a></li>
	      <li><a href="<?php echo base_url();?>whout">WAREHOUSE-OUT</a></li>
	    </ul>
	</div>
	<div class="clearfix"></div>
	<div class="container">
		<br/>
		<div class="alert alert-success hide">Success! You can continue create new order.<a href="#" class="close" data-dismiss="alert">&times;</a></div>
		<div class="alert alert-error hide"><span>Opps! Change a few things up and try submitting again.</span><a href="#" class="close" data-dismiss="alert">&times;</a></div>

		<form name="data_form" id="data_form" method="post" onsubmit="submit_form();return false;" class="form-horizontal">
			<input type="text" id="input_no" name="input_no" placeholder="Order #" required maxlength="10" style="margin-bottom: 10px;" value="<?php if(isset($input_no)) echo $input_no ?>"/>
			<?php if(!empty($order_id)){
				echo '<a href="javascript:;" onclick="javascript:remove_order('.$order_id.')">Remove this order</a>';
			} ?>
			<table class="table table-bordered table-striped" id="item_data">
				<thead><tr>
					<th width="8%">有拖板?</th>
					<th width="5%">Pallet #</th>
					<th width="30%">ITEM</th>
					<th>箱號</th>
					<th>箱數</th>
					<th>QTY(@CTN)</th> 
					<th>QTY</th>
					<th>Action</th>
				</tr></thead> 
				<tbody>
					<?php 
						if(isset($items)){
							foreach ($items as $item) {
								echo '<tr><td>'.($item["is_packed"]?'Y':'N');
								echo '</td><td style="text-align: right;">'.$item["pallet_id"];
								echo '</td><td>'.$item["item_sku"];
								echo '</td><td>'.$item["box_id"];
								echo '</td><td style="text-align: right;">'.$item["box_num"];
								echo '</td><td style="text-align: right;">'.$item["box_capacity"];
								echo '</td><td style="text-align: right;">'.intval($item["box_num"]*$item["box_capacity"]).' PCS';
								echo '</td><td style="text-align: center;"><a href="javascript:;" onclick="javascript:remove_item(\''.$item["id"].'\')">X</a></td></tr>';

							}
						}

					?>
				</tbody>
			</table>
			<input type="submit" value="Submit" class="btn btn-primary" />
			<input type="button" value="Add Row" class="btn" onclick="add_row()" />
		</form>
	</div>
	<div class="hide"><table id="tr_template">
		<tr><td><input type="checkbox" name="item[_IDX_][packed]" value="1" checked /></td>
			<td><input type="number" name="item[_IDX_][pallet_id]" value="_IDX_" class="input-mini" min=1 max=1000 /></td>
			<td><input type="text" name="item[_IDX_][sku]" value="" placeholder="SKU #" class="input-large" required maxlength="16"/></td>
			<td><input type="text" name="item[_IDX_][box_id]" value="" placeholder="Box NO" class="input-mini" maxlength="10"/></td>
			<td><input type="number" name="item[_IDX_][box_num]" value="" placeholder="Boxes #" class="input-mini box_num" min=1 onblur="calc_qty(this)"/></td>
			<td><input type="number" name="item[_IDX_][box_capacity]" value="" class="input-mini box_cap" placeholder="QTY(@CTN)" min=1 onblur="calc_qty(this)"/></td>
			<td><div class="input-append"><input type="number" name="item[_IDX_][quantity]" value="" placeholder="QTY" class="span2 input-mini item_qty" required min=1 /><span class="add-on">PCS</span></div></td>
			<td style="text-align: center;"><a href="javascript:;" onclick="javascript:remove_item(0)">X</a></td></tr>
		</table>
	</div>
</div>


<script type="text/javascript" src="<?php echo base_url();?>media/js/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>media/js/bootstrap.min.js"></script>
<script type="text/javascript">
function submit_form(){
	$(".alert").hide();
	if(!confirm("Confirm submit the form?")) return false;

	$.post("<?php echo base_url();?>whin/create/", $("#data_form").serialize(), function(response){
		if(1==response.status){
			<?php if(!empty($order_id)){
				echo 'alert("Update success.");';
				echo 'window.location.reload();';
			}?>
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

function add_row(){
	var tb = $("#item_data");
	var tmplt = $("#tr_template tbody").html().replace(/_IDX_/g, (tb.find("tr").length));
	tb.append(tmplt);
}

function calc_qty(item){
	var tr = $(item).parents('tr');
	var box_num = parseInt(tr.find('.box_num').val());
	var box_cap = parseInt(tr.find('.box_cap').val());
	if(box_num>0&&box_cap>0){
		tr.find('.item_qty').val(box_num*box_cap);
	}
}

function remove_item(id){
	if(!confirm("Confirm to remove this item?")) return false;
	var tr = $(event.target).parents('tr');
	
	if(0!==id){
		$.post("<?php echo base_url();?>whin/drop/", {"item_id": id}, function(response){
			console.log(response);
			if(1==response.status){
				tr.remove();
			}
			else{
				alert(response.msg);
			}
		});
	}
	else{
		tr.remove();
	}
}

function remove_order(id){
	if(!confirm("Confirm to remove this full order?")) return false;
	
	$.post("<?php echo base_url();?>whin/drop/", {"order_id": id}, function(response){
		if(1==response.status){
			window.location.href = "<?php echo base_url() ?>whin/";
		}
		else{
			alert(response.msg);
		}
	});
}

$(function(){
	add_row();

	$("input[type=number]").css({"text-align":"right"});
});

</script>
</body>
</html>