<?php //$this->layout = 'login'; ?>
<section class="row">
	<h2>Login</h2>
	<div class="userform medium-centered columns medium-8">
	<?php echo $this->Session->flash(); ?>
	<?php echo $this->Form->create('User');?>
		<fieldset>
		 <?php
				echo $this->Form->input('username');
				echo $this->Form->input('password');
		?>
		</fieldset>
		<input type="submit" class="tiny button" value="Login" />
		<small><a href="/users/forgot">Forgot password?</a></small>
	<?php echo $this->Form->end();?>
	
	</div>
</section>
