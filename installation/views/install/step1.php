Database<br>
<p>
<?php 
echo Form::label('host', 'Host: ');
echo Form::input('host', Arr::get($data, 'host'), array('placeholder' => 'localhost'));
?>
</p>

<p>
<?php 
echo Form::label('port', 'Port: ');
echo Form::input('port', Arr::get($data, 'port'), array('placeholder' => '3306'));
?>
</p>

<?php 
echo Form::label('database', 'Database: ');
echo Form::input('database', Arr::get($data, 'database'), array('placeholder' => 'statistican'));
?>
</p>

<p>
<?php 
echo Form::label('user', 'User: ');
echo Form::input('user', Arr::get($data, 'user'), array('placeholder' => 'statistican'));
?>
</p>

<p>
<?php 
echo Form::label('password', 'Password: ');
echo Form::password('password');
?>
</p>

<p>
<?php 
echo Form::label('prefix', 'Prefix: ');
echo Form::input('prefix', Arr::get($data, 'prefix'), array('placeholder' => 'stats_'));
?>
</p>
