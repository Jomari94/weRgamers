<?php
?>
<?php if ($model->type == 'messg'){ ?>
    <span class="glyphicon glyphicon-envelope"></span>
<?php } elseif ($model->type == 'follw') {?>
    <span class="glyphicon glyphicon-user"></span>
    <span class="glyphicon glyphicon-thumbs-up"></span>
<?php } elseif ($model->type == 'event') {?>
    <span class="glyphicon glyphicon glyphicon-calendar"></span>
    <span class="glyphicon glyphicon glyphicon-ok"></span>
<?php } elseif ($model->type == 'evenc') {?>
    <span class="glyphicon glyphicon glyphicon-calendar"></span>
    <span class="glyphicon glyphicon glyphicon-remove"></span>
<?php } elseif ($model->type == 'solic') {?>
    <span class="glyphicon glyphicon-user"></span>
    <span class="glyphicon glyphicon-user"></span>
    <span class="glyphicon glyphicon-arrow-left"></span>
<?php } elseif ($model->type == 'confi') {?>
    <span class="glyphicon glyphicon-user"></span>
    <span class="glyphicon glyphicon-user"></span>
    <span class="glyphicon glyphicon-ok"></span>
<?php } ?>
<span><?= $model->content ?></span>
