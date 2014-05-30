<?php $this->set('title_for_layout', 'Invite a friend'); ?>
<?php
	$msg = __('You have been invited by %s (%s) to take part in World Cup Goalmine, a prediction game for the 2014 World Cup finals. Click on the link below, or copy into a browser address bar to create your own login and begin playing.', $user['username'], $user['email']);
?>
<section class="row">
	
	<div class="medium-9 medium-push-3 columns">
		
		<h2>Invite a friend</h2>
		<p>
		To invite a friend to take part in the game, add their email address in the form below. You can also, optionally, edit the default message that will form the body of the email.
		</p>
		<p>
		Once you've clicked 'send', the recipient will receive an email inviting them to visit the site and verify their details to create an account.
		</p>

		<form action="/users/invite" method="post" accept-charset="utf-8" id="invite_form">
			<fieldset>
				<label for="email">Email address:
					<input name="data[Invite][email]" type="email" id="email" required />
				</label>
				<label for="message">Message body:
					<textarea name="data[Invite][message]" id="message" cols="50" rows="6" required><?php echo $msg; ?></textarea>
				</label>
				<label for="copy">Click to receive a copy of the email:
					<input type="checkbox" name="data[Invite][copy]" value="1" id="copy" />
				</label>
			</fieldset>
			<input type="submit" class="tiny button" value="Send" />
		</form>
		
	</div>
	
	<div class="medium-3 medium-pull-9 columns">
		
		<h4>Invitees</h4>
		
		<?php if (empty($invitees)): ?>
			<p>No invites yet!</p>
		<?php else: ?>
		
		<table id="invites">
			<tbody>
			<?php foreach ($invitees as $i): ?>
				<tr>
					<td class="<?php echo ($i['User']['validated'] == 1) ? 'confirmed' : 'unconfirmed'; ?>"><?php echo $i['User']['email'] ?></td>
				</tr>
			<?php endforeach; ?>	
			</tbody>
		</table>
		<p class="orphan-center">Confirmed players are in green, unconfirmed in red.</p>
		
		<?php endif; ?>
		
	</div>

</section>
