<?php
/*
 * Shows the error messages.
 */
fMessaging::show('errors');
?>

<form name="step2" action="?step=two" method="post">
<fieldset>
    <legend>Database</legend>
    <label for="host"><?php echo fText::compose('server'); ?>:</label>
    <input type="text" name="host" placeholder="localhost" value="<?php echo $this->get('host') ?>"><br>
    <label for="user"><?php echo fText::compose('user'); ?>:</label>
    <input type="text" name="user" placeholder="user" value="<?php echo $this->get('user') ?>"><br>
    <label for="pw"><?php echo fText::compose('pw'); ?>:</label>
    <input type="text" name="pw" placeholder="password" value="<?php echo $this->get('pw') ?>"><br>
    <label for="database"><?php echo fText::compose('db'); ?>:</label>
    <input type="text" name="database" placeholder="statistican" value="<?php echo $this->get('database') ?>"><br>
    <label for="prefix"><?php echo fText::compose('prefix'); ?>:</label>
    <input type="text" name="prefix" value="<?php echo $this->get('prefix' , 'stat_') ?>"><br>    
</fieldset>    
    <br>
    <input type="submit" name="db_submit" value="<?php echo fText::compose('submit'); ?>">
</form>