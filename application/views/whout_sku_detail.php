<div>

<table class="table table-bordered">
<tr><th>Date</th><th>From->To</th><th># of Boxes</th><th>Remark</th> </tr>
<?php

if(count($list)<1){
	echo '<tr><td colspan="4">No records yet.</td></tr>';
}
else{
	foreach ($list as $key => $r) {
		echo '<tr><td>'.$r['created'].'</td><td>';
		echo $r["item_from"].'->'.$r["item_to"].'</td><td>';
		echo $r["quantity"].'</td><td>';
		echo $r["remark"].'</td></tr>';
	}
}

 ?>
</table>

</div>
