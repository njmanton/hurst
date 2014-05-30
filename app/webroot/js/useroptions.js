$(function() {

	var	p = $('#OptionsPwd'),
			n = $('#OptionsNew'),
			r = $('#OptionsRpt'),
			err = 0;

	n.on('blur', function() {
		if ((n.val().length < 6) && (n.val().length > 0)) {
			n.next().html('Password must be greater than 5 characters');
			err = 1;
		} else {
			err = 0;
		}
	}).on('focus', function() {
		n.next().empty();
	})

	r.on('blur', function() {
		if (r.val().length && r.val() != n.val()) {
			r.next().html('Passwords don\'t match');
			err = 1;
		} else {
			err = 0;
		}
	}).on('focus', function() {
		r.next().empty();
	})

	$('#UserOptions').on('submit', function(e) {

		if (err) {
			e.preventDefault();
		}

	});

})

