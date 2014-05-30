<!DOCTYPE html>
<html lang="en" dir="ltr" id="loginpage">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>World Cup Goalmine Login</title>
	<?php
		echo $this->Html->css('master'); // @includes all other stylesheets
		echo $this->Html->script('modernizr-latest'); // should load in head for html5shim & stop FOUC
	?>
</head>
<body >

	<main role="main" id="container">

			<?php echo $this->fetch('content'); ?>

	</div>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.js"></script>
	<script>window.jQuery || document.write ('<script src="/js/jquery-1.11.0.min.js">\x3C/script>')</script>
</body>
</html>
