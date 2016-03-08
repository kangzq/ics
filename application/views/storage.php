<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome_WAREHOUSE</title>
	<link href="media/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link href="media/css/ics.css" rel="stylesheet" type="text/css"/>
	<link rel="shortcut icon" href="media/image/favicon.ico" />
	
</head>
<body>
<div id="wrap"> 
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
	<div class="">
		<form class="form-search" id="search_form" onsubmit="load_list();return false;">
			<input type="text" name="filter[pono]" class="input-small" placeholder="PO NO." maxlength="10" />
			<input type="text" name="filter[sku]" id="sku_search" class="input-medium" placeholder="Item SKU" maxlength="20" />
			<input type="text" name="filter[date]" class="input-medium js-datepicker" placeholder="Date" maxlength="12" />
			<input type="hidden" name="page" id="page" value="1" />
			<button type="submit" class="btn btn-primary">Search</button>
			<button type="reset" class="btn">Reset</button>
		</form>
	</div>
	<table class="table table-bordered table-striped" id="item_list">
		<tr><th rowspan=2>PO NO.</th><th rowspan=2>ITEM</th><th rowspan=2>TOTAL</th><th rowspan=2># REMAINING</th><th colspan=5 width="50%">DETAILS</th></tr>
		<tr><td>Date</td><td>From->To</td><td># Boxs</td><td>Reamrk</td><td>Action</td></tr>
	</table>
	<div class="pagination pagination-right">
	  <ul></ul>
	</div>
</div>
</div>

<div class="footer" id="footer">
	<span class="hide"><a  data-toggle="modal"  data-target="#modal" href="" id="modal_trigger"></a></span>
	<div class="container"><p>Powered by <a href="mailto:xeon.kang@qq.com">Kang</a></p></div>
</div>

<!-- Modal -->
<div id="modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="Order Detail" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Order Detail</h3>
  </div>
  <div class="modal-body">
    <p>One fine body…</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>


<script type="text/javascript" src="media/js/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="media/js/bootstrap.min.js"></script>
<script type="text/javascript" src="http://v2.bootcss.com/assets/js/bootstrap-typeahead.js"></script>
<script type="text/javascript" src="media/js/jquery-ui-1.10.1.custom.min.js"></script>
<script type="text/javascript">
function load_list(){

	$.get('warehouse/item_list', $("#search_form").serialize(), function(data){
		render_list(data);
	})
}

function render_list(data){
	$("#item_list tr:gt(1)").empty();
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
				html += item.detail[j].remark + '</td><td><a href="javascript:order_detail(';
				html += item.detail[j].oid + ')">View order</a></td>';
				html += '</tr>';
			};
			if(0==d.length) html += '<td colspan=5>';

			html+= '</td></tr>';
			$("#item_list").append(html);
		};
		$("#item_list tr:gt(1)").find("td:nth-child(3),td:nth-child(4),td:nth-child(7)").css({"text-align": "right"})
		pagenation(data.page, data.total_page);
	}
	else{
		$("#item_list").append('<tr><td colspan="9">No item found.</td></tr>')
		pagenation(1, 1);
	}
}

function pagenation(cur, total){
	var pg = $(".pagination ul");
	pg.empty();

	if(1==cur){
		pg.append('<li class="disabled"><span>Prev</span></li>')
	}
	else{
		pg.append('<li><a href="javascript:;" onclick="prev_page()">Prev</a></li>')
	}

	for (var i = 1; i <= total; i++) {
		if(i==cur) pg.append('<li class="active"><span>'+i+'</span></li>')
		else pg.append('<li><a href="javascript:;" onclick="goto_page('+i+')">'+i+'</a></li>')
	};

	if(cur == total){
		pg.append('<li class="disabled"><span>Next</span></li>')
	}
	else{
		pg.append('<li><a href="javascript:;" onclick="next_page()">Next</a></li>')
	}
}

function goto_page(page){
	$("#page").val(page);$("#search_form").submit();
}

function prev_page(){
	var page = $("#page").val();
	page -= 1;
	if(page<1) page =1;
	goto_page(page);
}

function next_page(){
	var page = $("#page").val();
	var l= $(".pagination li").length - 1;
	var max = $(".pagination li:nth-child(" + l + ")").text()
	page = parseInt(page) + 1;
	if(page>max) page = max;
	goto_page(page);
}

function order_detail(oid){
	$(".modal-body").load('whout/detail/'+oid);
	$("#modal_trigger").trigger('click');
}

$(function(){
	load_list();
	$("#item_list tr:gt(1)").find("td:nth-child(3),td:nth-child(4),td:nth-child(7)").css({"text-align": "right"})

	$('#sku_search').typeahead({
	    source: function (query, process) {
	        var parameter = {query: query};
	        $.get('warehouse/sku_list', parameter, function (data) {
	            process(data);
	        });
	    }
	});

	$(".js-datepicker").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: "m/d/yy",
      minDate: new Date(2015, 1 - 1, 1),
      maxDate: new Date()
    });
});
</script>

</body>
</html>