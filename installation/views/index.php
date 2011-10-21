<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?php echo $title; ?></title>
    </head>
    <body>
        <?php
        echo Form::open();
        echo $content;
        ?>
        <div style="margin-top: 30px;">
            <?php
            echo Form::submit('submit', __('Next Step'));
            ?>
        </div>
        <?php
        echo Form::close();
        ?>
    </body>
</html>
