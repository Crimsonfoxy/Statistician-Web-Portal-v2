<?php
/*
 * Shows the error messages.
 */
fMessaging::show('errors');
?>
<fieldset id="converter">
    <?php if($this->get('state') == null): ?>
    <p>Enter the data where your old data is stored!</p>
    <label for="host"><?php echo fText::compose('server'); ?>:</label>
    <input type="text" name="host" id="host" placeholder="localhost" value="<?php echo $this->get('host') ?>"><br>
    <label for="user"><?php echo fText::compose('user'); ?>:</label>
    <input type="text" name="user" id="user" placeholder="user" value="<?php echo $this->get('user') ?>"><br>
    <label for="pw"><?php echo fText::compose('pw'); ?>:</label>
    <input type="text" name="pw" id="pw" placeholder="password" value="<?php echo $this->get('pw') ?>"><br>
    <label for="database"><?php echo fText::compose('db'); ?>:</label>
    <input type="text" name="database" id="database" placeholder="statistican" value="<?php echo $this->get('database') ?>"><br> 
    <?php endif; ?>
    
    <?php if($this->get('state') == 2): ?>
    <p>Calculates how many database entries will be run...</p>
    <p>Choose which entries do you want to convert:</p>
    <table>
	<tr>
	    <td>Players:</td>
	    <td><?php echo $this->get('players'); ?></td>
	    <td><input type="checkbox" name="convert[players]" id="convert_players" disabled="disabled" checked="checked"></td>
	</tr>
	<tr>
	    <td>Blocks placed:</td>
	    <td><?php echo $this->get('blocks_placed'); ?></td>
	    <td><input type="checkbox" name="convert[blocks][placed]" id="convert_placed_blocks" checked="checked"></td>
	</tr>
	<tr>
	    <td>Blocks destroyed:</td>
	    <td><?php echo $this->get('blocks_destroyed'); ?></td>
	    <td><input type="checkbox" name="convert[blocks][destroyed]" id="convert_des_blocks" checked="checked"></td>
	</tr>
	<tr>
	    <td>Items dropped:</td>
	    <td><?php echo $this->get('items_dropped'); ?></td>
	    <td><input type="checkbox" name="convert[items][dropped]" id="convert_dropped_items" checked="checked"></td>
	</tr>
	<tr>
	    <td>Items picked up:</td>
	    <td><?php echo $this->get('items_picked'); ?></td>
	    <td><input type="checkbox" name="convert[items][picked]" id="convert_picked_items" checked="checked"></td>
	</tr>
	<tr>
	    <td>PVP Kills:</td>
	    <td><?php echo $this->get('pvp'); ?></td>
	    <td><input type="checkbox" name="convert[pvp]" id="convert_pvp" checked="checked"></td>
	</tr>
	<tr>
	    <td>PVE Kills:</td>
	    <td><?php echo $this->get('pve'); ?></td>
	    <td><input type="checkbox" name="convert[pve]" id="convert_pve" checked="checked"></td>
	</tr>	
    </table>
    <input type="hidden" name="start" value="true">
    <?php endif; ?>
    <?php if($this->get('state') == 3): ?>
    Converted
    <?php endif; ?>
</fieldset>
<input type="submit" name="converter_submit" value="<?php echo fText::compose('Next'); ?>">