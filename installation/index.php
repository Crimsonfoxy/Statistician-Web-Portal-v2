<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?php echo $this->get('title'); ?></title>
    </head>
    <body>
        <fieldset name="installation">
            <legend>Installation</legend>
	    <form name="install" method="post" action="<?php echo fURL::getWithQueryString(); ?>">
            <?php
            $this->place('tpl');
            ?>
	    </form>
        </fieldset>
    </body>
</html>