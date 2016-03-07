<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome_WAREHOUSE</title>
	<link href="media/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link rel="shortcut icon" href="media/image/favicon.ico" />
</head>
<body> 
<div class="container row-fluid" style="width:90%">
	<div class="navbar">
	    <ul class="nav nav-tabs" style="width:100%">
	      <li class="active"><a href="./">WAREHOUSE</a></li>
	      <li><a href="whin">WAREHOUSE-IN</a></li>
	      <li><a href="whout">WAREHOUSE-OUT</a></li>
	    </ul>
	</div>
</div>
<div class="container row-fluid" style="width:90%;margin-top: 20px;">
	<table class="table table-bordered table-striped" id="item_list">
		<tr><th rowspan=2>PO NO.</th><th rowspan=2>ITEM</th><th rowspan=2>TOTAL</th><th rowspan=2># REMAINING</th><th colspan=5 width="50%">DETAILS</th></tr>
		<tr><td>Date</td><td>From->To</td><td># Boxs</td><td>Reamrk</td><td>Action</td></tr>
	</table>
</div>


<script type="text/javascript" src="media/js/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="media/js/bootstrap.min.js"></script>
<script type="text/javascript">
function load_list(){

	$.get('warehouse/item_list', {"a": 1, "b":2}, function(data){
		console.log(data);
		render_list(data);
	})
}

function render_list(data){
	if(data && data.total>0){
		var cnt = data.list.length;
		var html = '', item;
		for (var i = 0; i < cnt; i++) {
			item = data.list[i];
			var d = item.detail;
			var rowspan = 1;
			if(d.length>1) rowspan = d.length;

			html = '<tr><td rowspan='+rowspan+'>';
			html+= item.no + '</td><td rowspan='+rowspan+'>';
			html+= item.item_sku + '</td><td rowspan='+rowspan+'>';
			html+= item.box_num + '</td><td rowspan='+rowspan+'>';
			html+= item.item_left + '</td>';
			for (var j = 0; j < item.detail.length; j++) {
				if(j>0) html += '<tr>';
				html += '<td>';
				html += item.detail[j].created + '</td><td>';
				html += item.detail[j].item_from + '->';
				html += item.detail[j].item_to + '</td><td>';
				html += item.detail[j].quantity + '</td><td>';
				html += item.detail[j].remark + '</td><td><a href="whout/detail/';
				html += item.detail[j].oid + '">View order</a></td>';
				html += '</tr>';
			};
			if(0==d.length) html += '<td colspan=5>';

			html+= '</td></tr>';
			$("#item_list").append(html);
		};
		$("#item_list tr:gt(1)").find("td:nth-child(3),td:nth-child(4),td:nth-child(7)").css({"text-align": "right"})
	}
	else{
		//$("#item_list").
	}
}

$(function(){
	load_list();
	$("#item_list tr:gt(1)").find("td:nth-child(3),td:nth-child(4),td:nth-child(7)").css({"text-align": "right"})
});
</script>

</body>
</html>