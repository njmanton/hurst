<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->css('master'); // @includes all other stylesheets
		echo $this->Html->script('vendor/modernizr-latest'); // should load in head for html5shim & stop FOUC
	?>
	<script async src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.js"></script>
	<script>window.jQuery || document.write ('<script src="/js/vendor/jquery-1.11.0.min.js">\x3C/script>')</script>
	<script src="/js/vendor/highcharts.js"></script>
</head>
<body>
	<div>

		<?php echo $this->element('header'); ?>
		<main id="content" role="main">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</main>
		<?php echo $this->element('footer'); ?>

	</div>
	
	<script src="/js/hurst.min.js"></script>
	<script src="/js/foundation/foundation.js"></script>
	<script src="/js/foundation/foundation.alert.js"></script>
	<script src="/js/foundation/foundation.tooltip.js"></script>
	<script src="/js/foundation/foundation.topbar.js"></script>

	<script>
		$(function() {
			// initialise all foundation js
			$(document).foundation();
		});
	</script>

</body>
</html>
