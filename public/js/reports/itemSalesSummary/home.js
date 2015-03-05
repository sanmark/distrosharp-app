$("#targrt").submit(function (event) {

	$('#error-mess').empty();

	var from_date = $('#from_date').val();
	var to_date = $('#to_date').val();

	var dateGap = daydiff(parseDate(from_date), parseDate(to_date));

	if (dateGap < 30) {
		return true;
	} else {

		var errorMes = '';
		errorMes += '<div class="alert alert-danger alert-dismissible" role="alert">';
		errorMes += '<button type="button" class="close" data-dismiss="alert">';
		errorMes += '<span aria-hidden="true">×</span>';
		errorMes += '<span class="sr-only">Close</span></button>';
		errorMes += '<ul> <li>Maximum date range is 31 dates.</li> </ul>';
		errorMes += '</div>';

		$('#error-mess').append(errorMes);


		return false;
	}



});

function parseDate(str) {
	var mdy = str.split('-');
	return new Date(mdy[0], mdy[1] - 1, mdy[0]);
}

function daydiff(first, second) {
	return (second - first) / (1000 * 60 * 60 * 24);
}


