<!DOCTYPE html>
<html>
<head>
  <title><?php echo $title ?></title>
  <?php foreach ($styles as $file => $type) echo HTML::style($file, array('media' => $type)), PHP_EOL ?>
  <?php foreach ($scripts as $file) echo HTML::script($file), PHP_EOL ?>  
</head>


<body>
	<div id="topLeadBar"><div id="titleMain"></div><div id="creeper"></div></div>        
	<div id="content">
            <div id="listTitle"><a href="/"><?php echo 'Server Name'; ?></a></div>
            <?php echo $content; ?>
	</div>
	<br />
	<div id="copyright">
            Statistician v2 :: orginal by ChaseHQ<br>
            Version :: <?php echo $version; ?><br>
            <?php
            
            $execution = Profiler::application();            
            
            echo round($execution['current']['time'], 4);
            echo ' seconds';            
            ?>
        </div>
</html>
