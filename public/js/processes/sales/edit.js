

function populateCustomersForRoute(csrfToken)
{
	$(document).on('change', '#route_id', function () {
		routeId = SanmarkJsHelper.Input.get('#route_id');
		$('#customer_id').find('option').remove();
		$('#customer_id').append(
			$('<option value=""></option>').text('Select')
			);
		$.post(
			"/entities/customers/ajax/forRouteId", {
				_token: csrfToken,
				routeId: routeId
			},
		function (data) {
			$.each(data, function (index, customer) {
				$('#customer_id').append(
					$('<option></option>')
					.attr('value', customer.id)
					.text(customer.name)
					);
			});
		}
		);
	});
}

$(document).on("keypress", 'form', function (e) {

	var code = e.keyCode || e.which;
	var target_id = event.target.id;
	var current_id = $('#current_edit_sales_id').val();
	var current_return_id = $('#current_edit_return_id').val();

	if (code === 13) {

		if (target_id === 'txt_edit_price_' + current_id || target_id === 'txt_edit_paid_quantity_' + current_id || target_id === 'txt_edit_free_quantity_' + current_id) {

			saveRow(current_id);

		}
		if (target_id === 'txt_edit_good_return_price_' + current_return_id || target_id === 'txt_edit_good_return_quantity_' + current_return_id || target_id === 'txt_edit_company_return_price_' + current_return_id || target_id === 'txt_edit_company_return_quantity_' + current_return_id) {

			saveRowReturn(current_return_id);

		}

		e.preventDefault();
		return false;
	}

});

$('body').on('submit', function (event) {

	var status = true;
	var current_id = $('#current_edit_sales_id').val();
	var current_return_id = $('#current_edit_return_id').val();

	if (current_return_id) {

		$("#txt_edit_good_return_price_" + current_return_id).addClass('duplicate-error');
		$("#txt_edit_good_return_quantity_" + current_return_id).addClass('duplicate-error');
		$("#txt_edit_company_return_price_" + current_return_id).addClass('duplicate-error');
		$("#txt_edit_company_return_quantity_" + current_return_id).addClass('duplicate-error');

		$('html, body').animate({
			scrollTop: $("#txt_edit_good_return_price_" + current_return_id).offset().top + -10
		}, 700);


		setTimeout(function () {
			$("input").removeClass("duplicate-error");
		}, 7000);

		status = false;
	}

	if (current_id) {

		$("#txt_edit_price_" + current_id).addClass('duplicate-error');
		$("#txt_edit_paid_quantity_" + current_id).addClass('duplicate-error');
		$("#txt_edit_free_quantity_" + current_id).addClass('duplicate-error');

		$('html, body').animate({
			scrollTop: $("#txt_edit_price_" + current_id).offset().top + -10
		}, 700);


		setTimeout(function () {
			$("input").removeClass("duplicate-error");
		}, 7000);

		status = false;
	}

	return status;

});
function addZeroIfNull(value) {
	if (!value) {
		value = 0;
	}
	return value;
}

function clearError() {

	$("input").removeClass("duplicate-error");
}

function setMethodToEnter() {

	$(document).on("keypress", 'form', function (e) {
		var code = e.keyCode || e.which;
		var target_id = event.target.id;
		if (code === 13) {
			if (target_id === 'txtPrice' || target_id === 'txtPaidQty' || target_id === 'txtFreeQty' || target_id === 'txtSalesLineTot') {
				jQuery('#add-new-salesl').click();
			}
			if (target_id === 'txtGoodReturnPrice' || target_id === 'txtGRQ' || target_id === 'txtCompanyReturnPrice' || target_id === 'txtCRQ' || target_id === 'txtreturnLineTot') {
				jQuery('#add-new-return').click();
			}
			e.preventDefault();
			return false;
		}

	});

}

function setSubTotal() {

	var txt_sales_total = $('#txt_sales_total').val();
	var txt_return_total = $('#txt_return_total').val();

	var result = txt_sales_total - txt_return_total;

	$('#subTotal').val(result.toFixed(2));

	var discount = $('#discount').val();
	if (!discount.trim()) {
		discount = 0;
	}

	var total = result - parseFloat(discount);

	$('#total').val(total.toFixed(2));

}

function displayBalance()
{
	var sub = SanmarkJsHelper.Input.get('#total');
	var cash = SanmarkJsHelper.Input.get('#cash_payment');
	var cheque = SanmarkJsHelper.Input.get('#cheque_payment');
	var total_payment = SanmarkJsHelper.Input.get('#total_payment');
	var result = (sub - cash - cheque ? sub - cash - cheque : 0) - total_payment;
	$('#balance').val(result.toFixed(2));

}

$(document).on('change keyup keypress', '#cash_payment, #cheque_payment, #discount', function () {

	setSubTotal();
	displayBalance();

});

function setAvailableQuantity(csrfToken) {

	$(document).ready(function () {
		$(".available_quantity").each(function () {
			var text_id = this.id;
			var itemId = this.value;
			var rep_id = $('#rep_id').val();

			$.post(
				"/stocks/ajax/getAvailableQuantity", {
					_token: csrfToken,
					itemId: itemId,
					rep_id: rep_id
				},
			function (data)
			{
				$('#' + text_id).val(data);
				$('#divAvailable_' + itemId).text(data); 

			});
		});
	});


}

function loadCreditInvoicesForCustomer(csrfToken, oldCreditPayments, date, bankSelect)
{
	function getOldCreditPaymentDetails(index, property) {
		if (oldCreditPayments) {
			if (oldCreditPayments[index]) {
				if (oldCreditPayments[index][property]) {
					return oldCreditPayments[index][property];
				}
			}
		} else if (property === "cheque_issued_date") {
			return date;
		}
		return null;
	}

	$(document).on('change', '#customer_id', function () {

		$('#creditPayments').html('');
		var customerId = SanmarkJsHelper.Input.get(this);
		$.post("/entities/customers/ajax/creditInvoices", {
			_token: csrfToken,
			customerId: customerId
		}, function (data) {

			if (data.length > 0) {
				var formGroupTitle = $('<div></div>');
				formGroupTitle
					.attr('class', 'row');
				formGroupTitle.append(
					$('<div></div>')
					.attr('class', 'col -sm-10 col-sm-offset-2')
					.attr('style', 'padding-left: 0; border-top: 1px solid #000; margin-bottom: 20px;')
					.append($('<h4></h4>')
						.attr('class', 'mainTitle')
						.attr('style', 'font-weight: bold;')
						.append('Late Credit Payments')
						)
					);
				$('#creditPayments').append(formGroupTitle);
			}

			$.each(data, function (index, sellingInvoice) {
				var newDiv = $('<div></div>');

				newDiv
					.attr('style', 'padding-bottom: 15px;');

				var formGroupOne = $('<div></div>');
				var formGroupTwo = $('<div></div>');
				var formGroupThree = $('<div></div>');

				formGroupOne
					.attr('class', 'form-group');
				formGroupTwo
					.attr('class', 'form-group');
				formGroupThree
					.attr('class', 'form-group');


				var formGroupOne_Seven = $('<div></div>')
					.attr('class', 'col-sm-7 col-sm-offset-2')
					.append(
						$('<div></div>')
						.attr('class', 'row bgcolor')
						.attr('style', 'padding-top: 15px; border-top-left-radius: 4px;')
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3 text-right')
							.append('Invoice Number :')
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-2')
							.append(sellingInvoice.id)
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-2 text-right')
							.append('Date :')
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.append(sellingInvoice.date_time)
							)
						);

				var formGroupOne_Three = $('<div></div>')
					.attr('class', 'col-sm-3')
					.append(
						$('<div></div>')
						.attr('class', 'row bgcolor')
						.attr('style', 'padding-top: 15px; border-top-right-radius: 4px;')
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-6 text-right')
							.append($('<div></div>')
								.append('Credit Amount :')
								)
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-6')
							.append($('<div></div>')
								.append(sellingInvoice.balance)
								)
							)
						);


				var formGroupTwo_Seven = $('<div></div>')
					.attr('class', 'col-sm-7 col-sm-offset-2')
					.append(
						$('<div></div>')
						.attr('class', 'row bgcolor')
						.attr('style', 'padding-bottom: 2px;')
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.attr('style', 'padding-top: 37px;')
							.append('Bank')
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.attr('style', 'padding-top: 37px;')
							.append('Cheque Number')
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.attr('style', 'padding-top: 37px;')
							.append('Issued Date')
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.attr('style', 'padding-top: 37px;')
							.append('Payable Date')
							)
						);


				var formGroupTwo_Three = $('<div></div>')
					.attr('class', 'col-sm-3')
					.append(
						$('<div></div>')
						.attr('class', 'row bgcolor')
						.attr('style', 'padding-top: 2px;')
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-6 text-right')
							.append($('<label></label>')
								.attr('class', 'control-label')
								.attr('for', 'credit_payments[' + sellingInvoice.id + '][cash_amount]')
								.text('Cash Amount')
								)
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-6')
							.append($('<input/>')
								.attr('type', 'number')
								.attr('class', 'form-control text-right')
								.attr('id', 'credit_payments[' + sellingInvoice.id + '][cash_amount]')
								.attr('name', 'credit_payments[' + sellingInvoice.id + '][cash_amount]')
								.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cash_amount'))
								)
							)
						);


				var formGroupThree_Seven = $('<div></div>')
					.attr('class', 'col-sm-7 col-sm-offset-2')
					.append(
						$('<div></div>')
						.attr('class', 'row bgcolor')
						.attr('style', 'border-bottom-left-radius: 4px;')
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.append($(bankSelect)
								.attr('class', 'form-control')
								.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_bank_id]')
								.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_bank_id]')
								.val(getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_bank_id'))
								)
							)

						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.append($('<input/>')
								.attr('type', 'text')
								.attr('class', 'form-control')
								.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_number]')
								.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_number]')
								.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_number'))
								)
							)

						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.append($('<input/>')
								.attr('type', 'date')
								.attr('class', 'form-control')
								.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_issued_date]')
								.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_issued_date]')
								.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_issued_date'))
								)
							)

						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.append($('<input/>')
								.attr('type', 'date')
								.attr('class', 'form-control')
								.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_payable_date]')
								.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_payable_date]')
								.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_payable_date'))
								)
							)

						);


				var formGroupThree_Three = $('<div></div>')
					.attr('class', 'col-sm-3')
					.append(
						$('<div></div>')
						.attr('class', 'row bgcolor')
						.attr('style', 'border-bottom-right-radius: 4px;')
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-6 text-right')
							.append($('<label></label>')
								.attr('class', 'control-label')
								.attr('for', 'credit_payments[' + sellingInvoice.id + '][cheque_amount]')
								.text('Cheque Amount')
								)
							)

						.append(
							$('<div></div>')
							.attr('class', 'col-sm-6')
							.append($('<input/>')
								.attr('type', 'number')
								.attr('class', 'form-control late_cheque_payment text-right')
								.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_amount]')
								.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_amount]')
								.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_amount'))
								.attr('data-selling-invoice-id', sellingInvoice.id)
								)
							)
						);


				formGroupOne.append(formGroupOne_Seven);
				formGroupOne.append(formGroupOne_Three);
				formGroupTwo.append(formGroupTwo_Seven);
				formGroupTwo.append(formGroupTwo_Three);
				formGroupThree.append(formGroupThree_Seven);
				formGroupThree.append(formGroupThree_Three);

				newDiv.append(formGroupOne);
				newDiv.append(formGroupTwo);
				newDiv.append(formGroupThree);

				$('#creditPayments').append(newDiv);
			});
		}
		);
	});
	
	
	$( document ).ready(function() {
		 
		$('#creditPayments').html('');
		var customerId = $('#customer_id').val();
		$.post("/entities/customers/ajax/creditInvoices", {
			_token: csrfToken,
			customerId: customerId
		}, function (data) {

			if (data.length > 0) {
				var formGroupTitle = $('<div></div>');
				formGroupTitle
					.attr('class', 'row');
				formGroupTitle.append(
					$('<div></div>')
					.attr('class', 'col -sm-10 col-sm-offset-2')
					.attr('style', 'padding-left: 0; border-top: 1px solid #000; margin-bottom: 20px;')
					.append($('<h4></h4>')
						.attr('class', 'mainTitle')
						.attr('style', 'font-weight: bold;')
						.append('Late Credit Payments')
						)
					);
				$('#creditPayments').append(formGroupTitle);
			}

			$.each(data, function (index, sellingInvoice) {
				var newDiv = $('<div></div>');

				newDiv
					.attr('style', 'padding-bottom: 15px;');

				var formGroupOne = $('<div></div>');
				var formGroupTwo = $('<div></div>');
				var formGroupThree = $('<div></div>');

				formGroupOne
					.attr('class', 'form-group');
				formGroupTwo
					.attr('class', 'form-group');
				formGroupThree
					.attr('class', 'form-group');


				var formGroupOne_Seven = $('<div></div>')
					.attr('class', 'col-sm-7 col-sm-offset-2')
					.append(
						$('<div></div>')
						.attr('class', 'row bgcolor')
						.attr('style', 'padding-top: 15px; border-top-left-radius: 4px;')
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3 text-right')
							.append('Invoice Number :')
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-2')
							.append(sellingInvoice.id)
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-2 text-right')
							.append('Date :')
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.append(sellingInvoice.date_time)
							)
						);

				var formGroupOne_Three = $('<div></div>')
					.attr('class', 'col-sm-3')
					.append(
						$('<div></div>')
						.attr('class', 'row bgcolor')
						.attr('style', 'padding-top: 15px; border-top-right-radius: 4px;')
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-6 text-right')
							.append($('<div></div>')
								.append('Credit Amount :')
								)
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-6')
							.append($('<div></div>')
								.append(sellingInvoice.balance)
								)
							)
						);


				var formGroupTwo_Seven = $('<div></div>')
					.attr('class', 'col-sm-7 col-sm-offset-2')
					.append(
						$('<div></div>')
						.attr('class', 'row bgcolor')
						.attr('style', 'padding-bottom: 2px;')
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.attr('style', 'padding-top: 37px;')
							.append('Bank')
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.attr('style', 'padding-top: 37px;')
							.append('Cheque Number')
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.attr('style', 'padding-top: 37px;')
							.append('Issued Date')
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.attr('style', 'padding-top: 37px;')
							.append('Payable Date')
							)
						);


				var formGroupTwo_Three = $('<div></div>')
					.attr('class', 'col-sm-3')
					.append(
						$('<div></div>')
						.attr('class', 'row bgcolor')
						.attr('style', 'padding-top: 2px;')
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-6 text-right')
							.append($('<label></label>')
								.attr('class', 'control-label')
								.attr('for', 'credit_payments[' + sellingInvoice.id + '][cash_amount]')
								.text('Cash Amount')
								)
							)
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-6')
							.append($('<input/>')
								.attr('type', 'number')
								.attr('class', 'form-control text-right')
								.attr('id', 'credit_payments[' + sellingInvoice.id + '][cash_amount]')
								.attr('name', 'credit_payments[' + sellingInvoice.id + '][cash_amount]')
								.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cash_amount'))
								)
							)
						);


				var formGroupThree_Seven = $('<div></div>')
					.attr('class', 'col-sm-7 col-sm-offset-2')
					.append(
						$('<div></div>')
						.attr('class', 'row bgcolor')
						.attr('style', 'border-bottom-left-radius: 4px;')
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.append($(bankSelect)
								.attr('class', 'form-control')
								.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_bank_id]')
								.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_bank_id]')
								.val(getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_bank_id'))
								)
							)

						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.append($('<input/>')
								.attr('type', 'text')
								.attr('class', 'form-control')
								.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_number]')
								.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_number]')
								.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_number'))
								)
							)

						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.append($('<input/>')
								.attr('type', 'date')
								.attr('class', 'form-control')
								.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_issued_date]')
								.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_issued_date]')
								.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_issued_date'))
								)
							)

						.append(
							$('<div></div>')
							.attr('class', 'col-sm-3')
							.append($('<input/>')
								.attr('type', 'date')
								.attr('class', 'form-control')
								.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_payable_date]')
								.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_payable_date]')
								.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_payable_date'))
								)
							)

						);


				var formGroupThree_Three = $('<div></div>')
					.attr('class', 'col-sm-3')
					.append(
						$('<div></div>')
						.attr('class', 'row bgcolor')
						.attr('style', 'border-bottom-right-radius: 4px;')
						.append(
							$('<div></div>')
							.attr('class', 'col-sm-6 text-right')
							.append($('<label></label>')
								.attr('class', 'control-label')
								.attr('for', 'credit_payments[' + sellingInvoice.id + '][cheque_amount]')
								.text('Cheque Amount')
								)
							)

						.append(
							$('<div></div>')
							.attr('class', 'col-sm-6')
							.append($('<input/>')
								.attr('type', 'number')
								.attr('class', 'form-control late_cheque_payment text-right')
								.attr('id', 'credit_payments[' + sellingInvoice.id + '][cheque_amount]')
								.attr('name', 'credit_payments[' + sellingInvoice.id + '][cheque_amount]')
								.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cheque_amount'))
								.attr('data-selling-invoice-id', sellingInvoice.id)
								)
							)
						);


				formGroupOne.append(formGroupOne_Seven);
				formGroupOne.append(formGroupOne_Three);
				formGroupTwo.append(formGroupTwo_Seven);
				formGroupTwo.append(formGroupTwo_Three);
				formGroupThree.append(formGroupThree_Seven);
				formGroupThree.append(formGroupThree_Three);

				newDiv.append(formGroupOne);
				newDiv.append(formGroupTwo);
				newDiv.append(formGroupThree);

				$('#creditPayments').append(newDiv);
			});
		}
		);
	});
}