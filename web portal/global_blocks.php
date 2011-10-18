<div id="subTitle"><?php echo(STRING_SERVER_GLOBAL_BLOCK_STATISTICS); ?></div>
<table>
	<th><?php echo(STRING_ALL_BLOCK_TYPE); ?></th><th><?php echo(STRING_ALL_DESTROYED); ?></th><th><?php echo(STRING_ALL_PLACED); ?></th>
	<?php
	$query = QueryUtils::getBlockList();
	
	while($row = mysql_fetch_assoc($query)) {
        echo '<tr>';
            echo '<td>';
                echo $row['name'];
            echo '</td>';
            echo '<td>';
                echo $row['destroyed'];
            echo '</td>';
            echo '<td>';
                echo $row['placed'];
            echo '</td>';
        echo '</tr>';
	}
	?>
</table>