<span id="infoLabel"><?php echo(STRING_SERVER_CURRENTLY_ONLINE); ?> (<?php echo $cnt = $serverObj->getAllPlayersOnlineCount(); ?>) :</span>
<?php
    if($cnt > 0) {
        $playerOnlineArray = $serverObj->getAllPlayersOnline();
        foreach($playerOnlineArray as $player) {
            ?>
            	<span id="online">
            		<a id="onlinePlayer" href="?view=player&uuid=<?php echo($player->getUUID()); ?>"><?php echo($player->getName()); ?></a>
            	</span>
        	<?php
    	}
    }
?>
<br /><br />
<div id="subTitle">
    <?php echo(STRING_SERVER_SERVER_STATISTICS); ?> &nbsp; &nbsp; <a id="smallLink" href="?view=playerList"><?php echo(STRING_SERVER_LINK_PLAYER_LIST); ?></a>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_SERVER_REGISTERED_PLAYERS); ?>:
    </span>
    <span id="info">
        <?php echo $serverObj->getAllPlayers(); ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_SERVER_MAX_PLAYERS_ON); ?>:
    </span>
    <span id="info">
        <?php 
            echo($serverObj->getMaxPlayersEverOnline());  
            echo(STRING_SERVER_MAX_PLAYERS_NUMBER_TO_TIME_SEPERATOR); 
            echo(QueryUtils::formatDate($serverObj->getMaxPlayersEverOnlineTimeWhenOccured())); 
        ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_SERVER_LIFETIME_LOGONS); ?>:
    </span><span id="info">
        <?php echo($serverObj->getNumberOfLoginsTotal()); ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_SERVER_TIME_PLAYED_TOTAL); ?>:
    </span>
    <span id="info">
        <?php echo(QueryUtils::formatSecs($serverObj->getNumberOfSecondsLoggedOnTotal())); ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_SERVER_SERVER_CURRENT_UPTIME); ?>:
    </span>
    <span id="info">
        <?php echo(QueryUtils::formatSecs($serverObj->getUptimeInSeconds())); ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_SERVER_SERVER_LAST_STARTUP); ?>:
    </span>
    <span id="info">
        <?php echo(QueryUtils::formatDate($serverObj->getStartupTime())); ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_SERVER_SERVER_LAST_SHUTDOWN); ?>:
    </span>
    <span id="info">
        <?php echo(QueryUtils::formatDate($serverObj->getLastShutdownTime())); ?>
    </span>
</div>
<div id="subCategory">
    <?php echo(STRING_ALL_DISTANCES); ?>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_TOTAL_TRAVEL_DISTANCE); ?>:
    </span>
	<span id="info">
	    <?php echo(QueryUtils::formatDistance($serverObj->getDistanceTraveledTotal())); ?>
	</span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_TOTAL_FOOT_TRAVEL_DISTANCE); ?>:
    </span>
    <span id="info">
        <?php echo(QueryUtils::formatDistance($serverObj->getDistanceTraveledByFootTotal())); ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_TOTAL_MINECART_TRAVEL_DISTANCE); ?>:
    </span>
    <span id="info">
        <?php echo(QueryUtils::formatDistance($serverObj->getDistanceTraveledByMinecartTotal())); ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_TOTAL_BOAT_TRAVEL_DISTANCE); ?>:
    </span>
    <span id="info">
        <?php echo(QueryUtils::formatDistance($serverObj->getDistanceTraveledByBoatTotal())); ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_TOTAL_PIG_TRAVEL_DISTANCE); ?>:
    </span>
    <span id="info">
        <?php echo(QueryUtils::formatDistance($serverObj->getDistanceTraveledByPigTotal())); ?>
    </span>
</div>
<div id="subCategory">
    <?php echo(STRING_ALL_BLOCKS); ?> &nbsp; &nbsp; <a href="?view=globalBlocks" id="smallLink"><?php echo(STRING_SERVER_LINK_GLOBAL_BLOCK_LIST); ?></a>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_TOTAL_BLOCKS_PLACED); ?>:
    </span>
    <span id="info">
        <?php echo($serverObj->getBlocksPlacedTotal()); ?> <?php echo(STRING_ALL_BLOCKS); ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_MOST_POPULAR_BLOCK_PLACED); ?>:
    </span>
    <span id="info">
        <?php 
            $block = $serverObj->getBlocksMostPlaced();
            echo QueryUtils::getResourceNameById($block); 
            echo ' - (';
            echo$serverObj->getBlocksPlacedOfTypeTotal($block);  
            echo(STRING_ALL_TIMES); 
            echo ')';
        ?>
     </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_TOTAL_BLOCKS_DESTROYED); ?>:
    </span>
    <span id="info">
        <?php echo($serverObj->getBlocksDestroyedTotal()); ?> <?php echo(STRING_ALL_BLOCKS); ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_MOST_POPULAR_BLOCK_DESTROYED); ?>:
    </span>
    <span id="info">
        <?php 
            $block = $serverObj->getBlocksMostDestroyed();
            echo(QueryUtils::getResourceNameById($block));
            echo ' - (';
            echo($serverObj->getBlocksDestroyedOfTypeTotal($block)); 
            echo(STRING_ALL_TIMES);
            echo ')'; 
        ?>
     </span>
</div>
<div id="subCategory">
    <?php echo(STRING_ALL_ITEMS); ?> &nbsp; &nbsp; <a href="?view=globalItems" id="smallLink"><?php echo(STRING_SERVER_LINK_GLOBAL_ITEMS_LIST); ?></a>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_TOTAL_ITEMS_PICKEDUP); ?>:
    </span>
    <span id="info">
        <?php 
            echo($serverObj->getPickedUpTotal()); 
            echo(STRING_ALL_ITEMS); 
        ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_MOST_POPULAR_ITEM_PICKEDUP); ?>:
    </span>
    <span id="info">
        <?php 
            $pick = $serverObj->getMostPickedUp();
            echo(QueryUtils::getResourceNameById($pick)); 
            echo ' - (';
            echo($serverObj->getPickedUpOfTypeTotal($pick)); 
            echo(STRING_ALL_TIMES);
            echo ')';
        ?>
   </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
    <?php echo(STRING_ALL_TOTAL_ITEMS_DROPPED); ?>:
    </span>
    <span id="info">
        <?php 
            echo($serverObj->getDroppedTotal()); 
            echo(STRING_ALL_ITEMS); 
        ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_MOST_POPULAR_ITEM_DROPPED); ?>:
    </span>
    <span id="info">
        <?php 
            $drop = $serverObj->getMostDropped();
            echo(QueryUtils::getResourceNameById($drop));
            echo ' - (';
            echo($serverObj->getDroppedOfTypeTotal($drop)); 
            echo(STRING_ALL_TIMES);
            echo ')';
        ?>
    </span>
</div>
<div id="subCategory">
    <?php echo(STRING_ALL_KILLS); ?> &nbsp; &nbsp; <a href="?view=globalKills" id="smallLink"><?php echo(STRING_SERVER_LINK_GLOBAL_KILL_LIST); ?></a>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_TOTAL_DEATHS); ?>:
    </span>
    <span id="info">
        <?php echo $serverObj->getTotalKills(); ?>
    </span>
</div>
<br />
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_SERVER_MOST_DANGEROUS_WEAPON); ?>:
    </span>
    <span id="info">
        <?php 
            $weapon = $serverObj->getMostDangerousWeapon();
            echo(QueryUtils::getResourceNameById($weapon['name'])); 
            echo ' - (';
            echo $weapon['count']; 
            echo(STRING_ALL_KILLS);
            echo ')'; 
        ?>
	</span>
</div>
<br />
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_PVP_KILLS); ?>:
    </span>
    <span id="info">
        <?php echo $serverObj->getTotalPVPKills(); ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_SERVER_MOST_DANGEROUS_PLAYER); ?>:
    </span>
    <span id="info">
        <?php
            $ar = $serverObj->getMostKillerPVP();
            $player = $serverObj->getPlayer($ar['name']);
            if ($player) {
            	?>
            	<a id="onlinePlayer" href="?view=player&uuid=<?php echo($player->getUUID()); ?>"><?php echo($player->getName()); ?></a> 
            	- (
            	    <?php 
            	        echo $ar['count'];
            	        echo(STRING_ALL_PVP_KILLS); 
            	    ?>
            	   )
            	<?php } else echo(STRING_ALL_NONE); ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_SERVER_MOST_SQUISHY_PLAYER); ?>:
    </span>
    <span id="info">
    <?php
        $ar = $serverObj->getMostKilledPVP();
        $player = $serverObj->getPlayer($ar['name']);
        if ($player) {
        	?>
        	<a id="onlinePlayer" href="?view=player&uuid=<?php echo($player->getUUID()); ?>"><?php echo($player->getName()); ?></a> 
        	- (
        	    <?php 
        	        echo $ar['count']; 
        	        echo(STRING_ALL_PVP_DEATHS); 
        	    ?>
        	  )
    	<?php } else echo(STRING_ALL_NONE); 
    ?>
    </span>
</div>
<br />
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_PVE_KILLS); ?>:
    </span>
    <span id="info">
        <?php echo $serverObj->getTotalPVEKills(); ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_SERVER_MOST_DANGEROUS_CREATURE); ?>:
    </span>
    <span id="info">
        <?php 
            $ar = $serverObj->getMostDangerousPVECreature();
            echo(QueryUtils::getCreatureNameById($ar['name']));
            echo ' - (';
            echo $ar['count'];
            echo(STRING_ALL_KILLS);
            echo ')'; 
        ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_SERVER_MOST_KILLED_CREATURE); ?>:
    </span>
    <span id="info">
        <?php 
            $ar = $serverObj->getMostKilledPVECreature();
            echo QueryUtils::getCreatureNameById($ar['name']);
            echo ' - (';
            echo $ar['count'];
            echo(STRING_ALL_DEATHS);
            echo ')'; 
        ?>
	</span>
</div>
<br />
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_OTHER_TYPE_DEATHS); ?>:
    </span>
    <span id="info">
        <?php echo $serverObj->getTotalOtherKills(); ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_FALLING_DEATHS); ?>:
    </span>
    <span id="info">
        <?php echo $serverObj->getTotalTypeKills(QueryUtils::getKillTypeIdByName("Fall")); ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_DROWNING_DEATHS); ?>:
    </span>
    <span id="info">
        <?php echo $serverObj->getTotalTypeKills(QueryUtils::getKillTypeIdByName("Drowning")); ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_SUFFOCATING_DEATHS); ?>:
    </span>
    <span id="info">
        <?php echo $serverObj->getTotalTypeKills(QueryUtils::getKillTypeIdByName("Suffocation")); ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_LIGHTENING_DEATHS); ?>:
    </span>
    <span id="info">
        <?php echo $serverObj->getTotalTypeKills(QueryUtils::getKillTypeIdByName("Lightening")); ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_LAVA_DEATHS); ?>:
    </span>
    <span id="info">
        <?php echo $serverObj->getTotalTypeKills(QueryUtils::getKillTypeIdByName("Lava")); ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_FIRE_DEATHS); ?>:
    </span>
    <span id="info">
        <?php echo $serverObj->getTotalTypeKills(QueryUtils::getKillTypeIdByName("Fire")); ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_FIRE_TICK_DEATHS); ?>:
    </span>
    <span id="info">
        <?php echo $serverObj->getTotalTypeKills(QueryUtils::getKillTypeIdByName("Fire Tick")); ?>
    </span>
</div>
<div id="infoLine">
    <span id="infoLabel">
        <?php echo(STRING_ALL_EXPLOSION_DEATHS); ?>:
    </span>
    <span id="info">
        <?php echo $serverObj->getTotalTypeKills(QueryUtils::getKillTypeIdByName("Entity Explosion")); ?>
    </span>
</div>