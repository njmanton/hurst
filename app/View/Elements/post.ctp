<?php $dt = new DateTime($post['created']); ?>
<article>
	<h4><?php echo $post['title']; ?></h4>
	<p><?php echo $post['body']; ?></p>
	<small>
		<?php echo __('%s on %s', $poster, $dt->format(DTF)); ?>
		<?php if ($user['id'] == $post['user_id']) echo $this->Form->postLink('Delete', array('controller' => 'posts', 'action' => 'delete', $post['id']), array('confirm' => 'Are you sure?')) . ' | ' . '<a href="/posts/edit/' . $post['id'] . '">Edit</a>'; ?>
	</small> 
	
</article>