$(function(){

	var u = $('#verifyuid'),
	 		p = $('#verifypwd'),
	 		r = $('#verifyrpt'),
	 		ue = $('#uid_err'),
	 		pe = $('#pwd_err'),
	 		re = $('#rpt_err'),
	 		dup = 0;

	u.on('blur', function() {

		if (u.val().length) {
			$.ajax({
				type: 'GET',
				url: '/users/check/' + u.val(),
				dataType: 'json',
				beforeSend: function() {
					ue.addClass('error').html(' Checking <img src="/img/ajax-loader.gif" alt="ajax" />');
				},
				success: function(response) {
					if (response) {
						ue.addClass('error').html(' That username is already in use');
						dup = 1;
					} else {
						ue.removeClass('error').html('&nbsp;');
						dup = 0;
					}
				}
			});
		}

	});

	p.on('blur', function() {
		if (p.val().length < 5) {
			pe.addClass('error').html(' Password must contain more than five characters');
		}
	}).on('focus', function() {
		pe.removeClass('error').html('&nbsp;');
	});

	r.on('blur', function() {
		if (r.val() != p.val()) {
			re.addClass('error').html(' Passwords don\'t match');
		}
	}).on('focus', function() {
		re.removeClass('error').html('&nbsp;');
	});

	$('#Verify').on('submit', function() {

		var err = false;
		err = ((dup == 1) || (p.val() == '') || (r.val() == '') || (p.val().length < 5) || (p.val() != r.val()));
		return (!err);

	});

});
