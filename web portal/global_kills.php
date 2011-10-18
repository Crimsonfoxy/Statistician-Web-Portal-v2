<?php 
$pag = new Paginator();
$pag->mid_range = 5;
$pag->items_per_page = 30;

$cat = (isset($_GET['cat']) ? $_GET['cat'] : '');

if($cat == 'pvp') {
    echo '<a href="?view=globalKills" ><div id="subCategory">'.STRING_SERVER_GLOBAL_KILL_STATISTICS.'</div></a>';
    echo '<div id="subCategory"> '.STRING_ALL_PVP_KILLS.' ('.$serverObj->getTotalPVPKills().')</div>';    
    
    ?>
    <table>
    <tr>
    	<th></th>
    	<th><?php echo STRING_ALL_KILLER; ?></th>
    	<th><?php echo STRING_ALL_WEAPON; ?></th>
    	<th><?php echo STRING_ALL_VICTIM; ?></th>
    	<th><?php echo STRING_ALL_KILLTIME; ?></th>
	</tr>
    <?php
    
    $pag->items_total = $serverObj->getTotalPVPKills();
    $pag->paginate();
    $pagination = $pag->display_pages();
    
    $i = $pag->low;
    
    $query = $serverObj->getPVPKills($pag->limit);
    
    while($row = mysql_fetch_assoc($query)) {
        $i++;
        
        echo '<tr>';
        echo '<td>';
        echo $i;
        echo '</td>';
        echo '<td>';
        echo '<a href="?view=player&uuid='.$row['killerID'].'" >';
        echo $row['killer'];
        echo '</a>';
        echo '</td>';
        echo '<td>';
        echo $row['weapon'];
        echo '</td>';
        echo '<td>';
        echo '<a href="?view=player&uuid='.$row['killedID'].'" >';
        echo $row['victim'];
        echo '</a>';
        echo '</td>';
        echo '<td>';
        echo QueryUtils::formatDate($row['time']);
        echo '</td>';
        echo '</tr>';
    }
    
    ?>
    </table>
    <?php  
    echo '<div id="pageSelector">';  
    echo $pagination;
    echo '</div>';
}
elseif($cat == 'pve') {
    echo '<a href="?view=globalKills" ><div id="subCategory">'.STRING_SERVER_GLOBAL_KILL_STATISTICS.'</div></a>';
    echo '<div id="subCategory"> '.STRING_ALL_PVE_KILLS.' ('.$serverObj->getTotalPVEKills().')</div>';
    
    ?>
            <table>
            <tr>
            	<th></th>
            	<th><?php echo STRING_ALL_KILLER; ?></th>
            	<th><?php echo STRING_ALL_WEAPON; ?></th>
            	<th><?php echo STRING_ALL_VICTIM; ?></th>
            	<th><?php echo STRING_ALL_KILLTIME; ?></th>
        	</tr>
            <?php
            
            $pag->items_total = $serverObj->getTotalPVEKills();
            $pag->paginate();
            $pagination = $pag->display_pages();
            
            $i = $pag->low;
            
            $query = $serverObj->getPVEKills($pag->limit);
            
            while($row = mysql_fetch_assoc($query)) {
                $i++;
                
                echo '<tr>';
                echo '<td>';
                echo $i;
                echo '</td>';
                if($row['killer'] != 'Player') {                    
                    echo '<td>';
                    echo $row['killer'];
                    echo '</td>';
                }
                else {
                    echo '<td>';
                    echo '<a href="?view=player&uuid='.$row['killerID'].'" >';
                    echo $row['killer_player'];
                    echo '</a>';
                    echo '</td>';
                }
                echo '<td>';
                echo $row['weapon'];
                echo '</td>';
                if($row['killed'] != 'Player') {                    
                    echo '<td>';
                    echo $row['killed'];
                    echo '</td>';
                }
                else {
                    echo '<td>';
                    echo '<a href="?view=player&uuid='.$row['killedID'].'" >';
                    echo $row['killed_player'];
                    echo '</a>';
                    echo '</td>';
                }
                echo '<td>';
                echo QueryUtils::formatDate($row['time']);
                echo '</td>';
                echo '</tr>';
            }
            
            ?>
            </table>
            <?php  
            echo '<div id="pageSelector">';  
            echo $pagination;
            echo '</div>';
}
else if($cat == 'other') {
    echo '<a href="?view=globalKills" ><div id="subCategory">'.STRING_SERVER_GLOBAL_KILL_STATISTICS.'</div></a>';
    echo '<div id="subCategory"> '.STRING_ALL_OTHER_DEATHS.' ('.$serverObj->getTotalOtherKills().')</div>';
    
    ?>
                <table>
                <tr>
                	<th></th>
                	<th><?php echo STRING_ALL_VICTIM; ?></th>
                	<th><?php echo STRING_ALL_REASON; ?></th>
                	<th><?php echo STRING_ALL_KILLTIME; ?></th>
            	</tr>
                <?php
                
                $pag->items_total = $serverObj->getTotalOtherKills();  
                $pag->paginate();
                $pagination = $pag->display_pages();
                
                $i = $pag->low;
                
                $query = $serverObj->getOtherKills($pag->limit);
                
                while($row = mysql_fetch_assoc($query)) {
                    $i++;
                    
                    echo '<tr>';
                    echo '<td>';
                    echo $i;
                    echo '</td>';                   
                    echo '<td>';
                    echo $row['killed'];
                    echo '</td>';                  
                    echo '<td>';
                    echo $row['type'];
                    echo '</td>';
                    echo '<td>';
                    echo QueryUtils::formatDate($row['time']);
                    echo '</td>';
                    echo '</tr>';
                }
                
                ?>
                </table>
                <?php  
                echo '<div id="pageSelector">';  
                echo $pagination;
                echo '</div>';
}
else {
    ?>
    <div id="subTitle"><?php echo(STRING_SERVER_GLOBAL_KILL_STATISTICS); ?></div>
    <a href="?view=globalKills&cat=pvp" name="pvpKills">
    	<div id="subCategory"><?php echo STRING_ALL_PVP_KILLS; echo ' ('.$serverObj->getTotalPVPKills().')'; ?></div>
    </a>
    <a href="?view=globalKills&cat=pve" name="pveKills">
    	<div id="subCategory"><?php echo STRING_ALL_PVE_KILLS; echo ' ('.$serverObj->getTotalPVEKills().')'; ?></div>
    </a>
    <a href="?view=globalKills&cat=other" name="otherDeaths">
    	<div id="subCategory"><?php echo STRING_ALL_OTHER_DEATHS ; echo ' ('.$serverObj->getTotalOtherKills().')'; ?></div>
    </a>
    <?php
}
?>