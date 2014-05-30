<?php $invites = $this->requestAction('users/invites'); ?>
<section class="smalltable">
	<p>Here are all your invites. Those that have confirmed are shown in <span>green</span>.</p>	
	<?php if (count($invites)): ?>
	<table class="league">
		<tbody>
			<?php foreach ($invites as $k=>$i): ?>
			<tr>
				<td class="<?php echo ($i == 0) ? 'unconf' : 'conf' ; ?>"><?php echo $k; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php else: ?>
	<p>No invites sent yet.</p>
	<?php endif; ?>
</section>