<div>
<div><b>Order #</b>: <?php echo $no ?></div>
<div><b>Date</b>: <?php echo strftime("%Y-%m-%d %H:%M:%S", $created) ?></div>
<table class="table table-bordered">
<tr><th>ITEM</th><th>#</th><th>From->To</th><th>Remark</th> </tr>
<?php
	foreach ($list as $key => $r) {
		echo '<tr><td>'.$r['item_sku'].'</td><td>';
		echo $r["quantity"].'</td><td>';
		echo $r["item_from"].'->'.$r["item_to"].'</td><td>';
		echo $r["remark"].'</td></tr>';
	}
 ?>
</table>

</div>
