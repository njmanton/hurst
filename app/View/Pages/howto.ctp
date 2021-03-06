<?php $this->set('title_for_layout', 'How to play | ' . APP_NAME); ?>
<section class="row">
	<div class="medium-10 medium-centered columns">
		<h2>How to play</h2>
		<h4>Logging in</h4>
		<p>You need to have an account to play Goalmine. You can only get an account through an invitation from an existing player.</p>
		<p>If you have been invited, you should have received an email with an activation code. Click on the link in that email which will take you to the activation page on this website to verify your account. You will need to confirm your email and choose a unique username. Then you will be able to log in and start playing.</p>
		
		<h4>Entry Fee</h4>
		<p>The entry fee is &pound;2.00 per player. You will only appear in the main league once your payment has been registered. To make a payment, send the entry fee via <a href="https://paypal.com">paypal</a> to <strong>nick@mantonbradbury.com</strong>. Please make sure that the transaction is personal, and that you mark it clearly with your Goalmine username, otherwise I can't assign the entry fee.</p>
		<p>You can enter more than once using the same email address, but you will require a unique username for each entry.</p>
		
		<h4>Navigating the site</h4>
		<p>You can view most of the pages without being logged in. Lists of matches (e.g. <a href="/matches/7">Uruguay vs Costa Rica</a>), teams (<a href="/teams/23">Iran</a>), goals and venues (<a href="/venues/9">Cuiabá</a>) can be found at the bottom of each page, along with the help pages. You can also see the current standings in the main league or any user leagues. In general, anywhere you see a match, player, team or venue on a page, you can click on it to see the relevant page.</p>
		<p>Once you've logged in, the top menu bar gives you additional options. You can view our own page, with access to further options, and make or edit your predictions. There are also options for quick access to teams and matches.</p>
		<p>From your own home page (top-left option) you can do the following tasks:
			<ul>
				<li>See a mini league table of current standings.</li>
				<li>Invite a friend to join (see below).</li>
				<li>Submit a request for a new user league.</li>
				<li>Change your account options (password or time zone).</li>
			</ul>
		</p>
		<p>Clicking on another player, in a league or prediction table, will show you their predictions. For the knockout stages you will only be able to see others' predictions after the deadline for that match.</p>
		
		<h4>Making predictions</h4>
		<p>The heart of Goalmine is making predictions on the games of the World Cup. Your predictions can be accessed from the menu bar by clicking <?php echo ($user) ? '<a href="/predictions/">predictions</a>' : 'predictions'; ?>. The box next to your name shows how many outstanding matches there are without a prediction.</p>
		<p>On the predictions screen you can see each available match. A match only becomes available when <strong>both</strong> teams have been decided.</p>
		<p>Available matches have a text box where you can enter a prediction (in the format X-X). Next to the box is a radio button to select your joker. You can play one joker on each group, and one on each knockout phase. The final is automatically a joker match.</p>
		<p>Changes to predictions and jokers are automatically saved whenever you make a change.</p>
		<p>The deadline for each game is <strong>midday</strong> on that day. After the deadline you are no longer able to make or edit any predictions, or change your joker if your joker is on an expired match. However, you will be able to see others' predictions if it is a knockout game.</p>
		
		<h4>Inviting other people</h4>
		<p>You can invite as many new people as you wish. Each new player signing up and paying the entry fee adds &pound;1.60 to the jackpot, and 40pence to the charity pot.</p>
		<p>You can invite a player from the link on your home page. Complete the form with the recipient's email address and optionally personalise the message. They will then receive an invitation email in their inbox.</p>
		<p>The invite page will also show a list of invites you have made, and their status.</p>
		
		<h4>User leagues</h4>
		<p>As with Goalmine for Euro 12, you may now create and join user leagues.</p>
		<p>You can join as many user leagues as you wish. You will still earn the same points for each game, but you may be in a higher or lower position for each league, depending on the other players.</p>
		<p>To join a league, click on the league's page (you can see all user leagues <a href="/leagues">here</a>). User leagues may be public or private. Public leagues can be joined freely, for private leagues (the default) you can request to join and that request will be forwarded to the league organiser to accept/reject.</p>
		<p>You can request your own user league. From your home page, click the '<a href="/leagues/add">create a new user league</a>' link and fill out the form. If your request is accepted then a new user league will be created with you as the organiser.</p>
		
		<h4>So what scores do I pick?</h4>
		<p>Only you can answer that. The <a href="/teams/">teams</a> index page shows the FIFA world rankings for each team, which may give you some idea of the relative strength of two teams.</p>
		<p>From looking at the stats of Goalmine over the last three years, we know that certain results are far less likely to be predicted than actually happen, and vice-versa. The graphic below shows a bubble plot of results and predictions from regular season Goalmine last year, showing the difference between results and predictions (click on the image for a large view). For example, there are a lot more 0-0 draws than predicted, and a lot fewer 2-1 home wins - but will that translate to the World Cup&hellip;?</p>
		<figure>
			<a href="/img/large_bubble.png"><img src="/img/small_bubble.png" alt="Bubble chart of predictions v results"></a>
			<figcaption>Plot of predicted vs actual goals</figcaption>
		</figure>
	</div>
	
</section>