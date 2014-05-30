$(function() {

	var times = $('#goals input[name*=time]');

	times.on('focus input', function() {
		var n = $(this).parent().next().find('input');
		if ($(this).val() == 45 || $(this).val() == 90) {
			n.attr('disabled', null);
		} else {
			n.attr('disabled', 'disabled');
		}
	});

	$('#EditGoal').on('submit', function(e) {
		//e.preventDefault();
	})

})

