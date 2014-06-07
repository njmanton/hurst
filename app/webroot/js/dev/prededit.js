$(function() {

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
