<?php
echo __('choose lang');
?>
<div>
    <?php
    echo Form::select('lang', $langs);
    ?>
</div>
