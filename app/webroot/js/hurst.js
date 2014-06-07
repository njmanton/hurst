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
;$(function() {

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

;$(function() {

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
;$(function() {

	var ml = $('#matchlists'), tl = $('#teamlists');

		$('#showml').on('click', function() {
			tl.hide();
			if (ml.css('display') == 'none') {
				ml.show();
			} else {
				ml.hide();
			}
		});

		$('#showtl').on('click', function() {
			ml.hide();
			if (tl.css('display') == 'none') {
				tl.show();
			} else {
				tl.hide();
			}
		});

});$(function() {

	$('#predIndexForm :text').on('change', function() {

		var box = $(this);
		var row = box.closest('td');
		$.ajax({
			dataType: 'json',
			type: 'POST',
			async: false, // async this operation to make sure a prediction id is passed back before any jokers are set
			data: {
				mid: box.data('mid'),
				pid: box.data('pid'),
				pred: box.val()
			},
			url: '/predictions/update',
			beforeSend: function() {
				row.removeClass('ajaxChange');
			},
			success: function(response) {
				if (response) {
					row.addClass('ajaxChange');
					box.data('pid', response);
				}
			}
		});

	}).on('blur', function() {

		var re = /\b\d{1,2}-\d{1,2}\b/;
		if ($(this).val().match(re) || $(this).val() == '') {
			$(this).removeClass('score_val_error');
		} else {
			$(this).addClass('score_val_error');
		}

	});

	$('#predIndexForm :radio').on('click', function() {
		var radio_selectors = ':radio[name="' + $(this).attr('name') + '"]';
		var radios = [];
		var row = $(this).closest('td');

		$(radio_selectors).each(function(index) {
			if (!$(this).attr('disabled')) {
				radios.push($(this).prev().data('pid'));
			}
		})

		$.ajax({
			type: 'POST',
			dataType: 'JSON',
			url: '/predictions/updatej',
			data: {
				sel: $(this).prev().data('pid'),
				pids: radios
			},
			beforeSend: function() {
				row.removeClass('ajaxChange');
			},
			success: function(response) {
				row.addClass('ajaxChange');
			}
		});

	});

})
;$(function() {

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

;$(function() {

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
				if (response['success'] === true) {
					t.html('&#x2714;').removeClass('error');
				} else {
					t.html(response['msg']).addClass('error');
				}
			}
		})
	});

})
;$(function(){

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
