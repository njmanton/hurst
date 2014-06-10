<section id="adminview">
	<h2>Users - admin view</h2>

	<label for="val">Validated Only: 
		<input type="checkbox" id="val" />
	</label>
	<label for="paid">Paid Only: 
		<input type="checkbox" id="paid" />
	</label>
	<table>
		<thead>
			<tr>
				<th>id</th>
				<th>email</th>
				<th>username</th>
				<th>referrer</th>
				<th>last login</th>
				<th>validated</th>
				<th>paid</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($users as $u): ?>
			<tr class="<?php if ($u['User']['validated']) { echo 'val '; } if ($u['User']['paid']) { echo 'paid'; } ?>">
				<td><?php echo $u['User']['id']; ?></td>
				<td><?php echo $u['User']['email']; ?></td>
				<td><?php echo ($u['User']['validated'] == 1) ? __('<a href="/users/%s">%s</a>', $u['User']['id'], $u['User']['username']) : '&nbsp;'; ?></td>
				<td><?php echo ($u['Referer']['username']) ?: 'root'; ?></td>
				<td><?php echo ($u['User']['lastlogin']) ? date(DTF, strtotime($u['User']['lastlogin'])) : '&nbsp;'; ?></td>
				<td><?php echo $u['User']['validated']; ?></td>
				<td><?php echo $u['User']['paid']; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

</section>

<script>
	$(function() {

		$('#adminview input').on('click', function() {
			var id = $(this).attr('id');
			if ($(this).is(':checked')) {
				$('table .' + id).show();
			} else {
				$('table .' + id).hide();
			}
		})

	});
</script>