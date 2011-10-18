<div id="subTitle"><?php echo(STRING_SERVER_GLOBAL_ITEM_STATISTICS); ?></div>
<table>
	<th><?php echo(STRING_ALL_BLOCK_TYPE); ?></th><th><?php echo(STRING_ALL_PICKEDUP); ?></th><th><?php echo(STRING_ALL_DROPPED); ?></th>
	<?php
	$query = QueryUtils::getItemList();
	
	while($row = mysql_fetch_assoc($query)) {
	    echo '<tr>';
    	    echo '<td>';
    	        echo $row['name'];
    	    echo '</td>';
    	    echo '<td>';
    	        echo $row['picked'];
    	    echo '</td>';
    	    echo '<td>';
    	        echo $row['dropped'];
    	    echo '</td>';
	    echo '</tr>';
	}	
	?>
</table>