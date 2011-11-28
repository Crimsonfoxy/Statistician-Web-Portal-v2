<?php
/*
 * Shows the error messages.
 */
fMessaging::show('errors');
?>

<form name="step2" action="?step=two" method="post">
<fieldset>
    <legend>Database</legend>
    <label for="host">Server:</label>
    <input type="text" name="host" placeholder="localhost" value="<?php echo $this->get('host') ?>"><br>
    <label for="user">User:</label>
    <input type="text" name="user" placeholder="user" value="<?php echo $this->get('user') ?>"><br>
    <label for="pw">Password:</label>
    <input type="text" name="pw" placeholder="password" value="<?php echo $this->get('pw') ?>"><br>
    <label for="database">Database:</label>
    <input type="text" name="database" placeholder="statistican" value="<?php echo $this->get('database') ?>"><br>
    <label for="prefix">Prefix:</label>
    <input type="text" name="prefix" value="<?php echo $this->get('prefix') ?>"><br>    
</fieldset>    
    <br>
    <input type="submit" name="db_submit" value="Submit">
</form>