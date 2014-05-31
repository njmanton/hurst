$(function() {

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

})