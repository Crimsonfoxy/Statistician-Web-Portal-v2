<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?php echo $this->get('title'); ?></title>
    </head>
    <body>
        <fieldset name="installation">
            <legend>Installation:</legend>        
            <?php
            $this->place('step');
            ?>
        </fieldset>
    </body>
</html>