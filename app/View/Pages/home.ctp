<?php $this->set('title_for_layout', 'World Cup Goalmine 2014'); ?>
<?php $posts = $this->requestAction('/posts/'); ?>
<section class="row">
	<h2>World Cup Goalmine</h2>
	<div class="medium-9 columns">
		<?php foreach ($posts as $post) { echo $this->element('post', ['post' => $post['Post'], 'poster' => $post['User']['username']]); } ?>
  </div>
  <div class="medium-3 columns">
  	<aside role="complementary" class="twitter-box">
			<a class="twitter-timeline" height="400" data-chrome="nofooter" href="https://twitter.com/goalmine_eu" data-theme="dark" data-widget-id="294738662560763904">Tweets by @goalmine_eu</a>
			<script>
				!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
			</script>
		</aside>
  </div>
</section>
