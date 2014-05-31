$(function() {

	var ue = $('#erroruid'),
			u  = $('#forgotuid'),
			s  = $('#forgotsubmit'),
			cl = 'alert-box warning';

	u.on('blur', function() {
		if (u.val().length) {
			$.ajax({
				type: 'get',
				dataType: 'json',
				url: '/users/check/' + u.val(),
				beforeSend: function() {
					ue.addClass(cl).html(' Checking <img src="/img/ajax-loader.gif" alt="ajax" />')
				},
				success: function(response) {
					if (response == false) {
						ue.addClass(cl).html('Can\'t find that username');
						s.attr('disabled', 'disabled');
					} else {
						ue.removeClass(cl).empty();
						s.removeAttr('disabled');
					}
				}
			});
		}

	}).on('focus', function() {
		ue.removeClass(cl).empty();
	});

})
