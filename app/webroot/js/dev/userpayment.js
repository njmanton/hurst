$(function() {

	$('#payments button').on('click', function() {
		var uid = $(this).data('uid');
		var t = $(this).parent();

		$.ajax({
			type: 'post',
			url: '/users/payment',
			data: { id: uid },
			dataType: 'json',
			beforeSend: function() {
				t.removeClass('error').html('<img src="/img/ajax-loader.gif" />');
			},
			statusCode: {
				404: function() {
					t.html('<span class="error">Couldn\'t contact server</span>');
				},
				500: function() {
					t.html('<span class="error">Server returned an error.</span>');
				}
			},
			success: function(response) {
				console.log(response);
				if (response['success'] === true) {
					t.html('&#x2714;').removeClass('error');
				} else {
					t.html(response['msg']).addClass('error');
				}
			}
		})
	});

})
