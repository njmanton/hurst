$(function() {

	var res = $('#EditGoals #result');
	res.on('input', function() {
		var regex = /\b\d{1,2}-\d{1,2}\b/;
		if (res.val().match(regex)) {
			goals = res.val().split('-');
			var home = goals[0], away = goals[1];

			if ((home == away) && (res.data('mid') > 48)) {
				$('#resolution').show('slow');
			} else {
				$('#resolution').hide();
			}

			// existing goals
			var vishome = $('#teama .scoreLine'), visaway = $('#teamb .scoreLine');
			// get the team ids
			var aid = $('#teama').data('tid'), bid = $('#teamb').data('tid');
			// get the number of goals
			var c = vishome.size(), d = visaway.size();

			// if number of goals is less than existing divs, loop through and delete
			while (c > home) {
				$('#teama .scoreLine:eq(' + (--c) + ')').remove();
			}

			while (d > away) {
				$('#teamb .scoreLine:eq(' + (--d) + ')').remove();
			}

			var div  = '<div class="scoreLine"> ';
			div += '<input required placeholder="scorer" name="data[Goal][*][scorer]" type="text"> ';
			div += '<input class="time normal" name="data[Goal][*][time]" type="number" min="1" max="120">+ ';
			div += '<input class="time" disabled name="data[Goal][*][tao]" type="number" min="0" /> ';
			div += '<input type="hidden" name="data[Goal][*][team_id]" value="#">';
			div += '<select name="data[Goal][*][type]">';
			div += '<option value=""></option>';
			div += '<option value="P">Pen</option>';
			div += '<option value="O">OG</option>';
			div += '</select>';
			div += '</div>';

			// loop through the goals to add, creating a new div and appending it
			for (var x = 0; x < (home - c); x++) {
				var s = div.replace(/\*/g, (x + c)).replace(/#/g, aid);
				$('#teama').append(s);
			}
			for (var y = 0; y < (away - d); y++) {
				var s = div.replace(/\*/g, (y + d + 100)).replace(/#/g, bid);
				$('#teamb').append(s);
			}

		}

	});

	// when the first time input changes or gets focus, check if time added on is allowed (45, 90 or 120')
	$('#EditGoals').on('focus input', '.normal', null, function() {
		var t = parseInt($(this).val());
		var n = $(this).next();
		if ([45, 90, 120].indexOf(t) == -1) {
			n.attr('disabled', 'disabled');
		} else {
			n.attr('disabled', null);
		}
	});

	$('#EditGoals').on('submit', function(e) {
		//e.preventDefault();
	})

})
