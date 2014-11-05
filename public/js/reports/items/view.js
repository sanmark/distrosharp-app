function loadCustomers(url, token) {

	$(document).on('change', '#route', function () {
		var routeId = SanmarkJsHelper.Input.get($(this).val());
		$('#customer').find('option').remove();
		$('#customer').append(
			$('<option value=""></option>')
			.text('All Customers')
			);
		$.post(
			url,
			{
				_token: token,
				routeId: routeId
			},
		function (data) {
			$.each(data, function (index, customer) {
				$('#customer').append(
					$('<option value=""></option>')
					.attr('value', customer.id)
					.text(customer.name)
					);
			});
		}
		);
	});

	$(document).ready(function () {
		$('#route').change();
	});
}
function validateDates() {
	$("#from_date, #to_date").change(function () {

		var from_date = SanmarkJsHelper.Input.get('#from_date');
		var to_date = SanmarkJsHelper.Input.get('#to_date');

		var from_dateJScript = document.getElementById('from_date');
		var to_dateJScript = document.getElementById('to_date');

		if (!from_date && to_date) {
			from_dateJScript.setCustomValidity("Please select a date");
		}else if (from_date && !to_date) {
			to_dateJScript.setCustomValidity("Please select a date");
		}
		else{
			to_dateJScript.setCustomValidity("");
			from_dateJScript.setCustomValidity("");
		}

	});
}