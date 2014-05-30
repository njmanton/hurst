<?php $this->set('title_for_layout', 'Options'); ?>

<section class="medium-8 medium-centered columns">

	<h2>Options</h2>
	<p>Use this form to change your current password or time-zone</p>
	<form action="/users/options" method="post" id="UserOptions" data-abide>
		<div>
			<label for="OptionsEmail">Email *
				<input autocomplete="off" required name="data[User][email]" type="email" id="OptionsEmail" value="<?php echo $user['email']; ?>" />
			</label>
		</div>
		<fieldset>
			<div>
				<label for="OptionsPwd">Current Password *
					<input name="data[User][password]" type="password" id="OptionsPwd" autocomplete="off" />
				</label>
			</div>
			<div>
				<label for="OptionsNew">New Password (min 6 chars)
					<input pattern=".{6,}" name="data[User][newpwd]" type="password" id="OptionsNew" />
				</label>
			</div>
			<div>
				<label for="OptionsRpt">Repeat New Password
					<input data-equalto="OptionsNew" pattern=".{6,}" name="data[User][rptpwd]" type="password" id="OptionsRpt" />
				</label>
			</div>
		</fieldset>
		<div>
			<label for="tz">Time Zone (GMT) [<span data-tooltip class="has-tip tip-right radius" title="Select your timezone. e.g. BST is GMT+1. This will show kickoffs in your local time">?</span>]
				<select name="data[User][utc_offset]" id="tz">
				<?php for($x = -11; $x < 12; $x++): ?>
					<option <?php if ($x == $user['utc_offset']) { echo 'selected'; } ?> value="<?php echo $x; ?>"><?php if ($x > 0) { echo '+'; } echo $x; ?></option>
				<?php endfor; ?>
				</select>
			</label>
		</div>
		<div>
			<input class="button tiny" type="submit" value="Update" id="OptionsSubmit" />
		</div>
	</form>
</section>


