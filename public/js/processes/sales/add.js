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
					.append($('<div></div>')
						.attr('class','row')
						.attr('style','padding-top:2px')
						.append($('<input/>')
						.attr('class', 'myCheckbox')
						.attr('type', 'checkbox')
						.attr('name', 'credit_payments[' + sellingInvoice.id + '][is_completely_paid]').attr('value','1')).append($('<label></label>').attr('class', 'control-label').attr('style', 'padding-left:10px').text('Is Completely Paid')))
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
								.attr('class', 'form-control text-right ')
								.attr('id', 'credit_payments[' + sellingInvoice.id + '][cash_amount]')
								.attr('name', 'credit_payments[' + sellingInvoice.id + '][cash_amount]')
								.attr('value', getOldCreditPaymentDetails(sellingInvoice.id, 'cash_amount'))
								.attr('step', 'any')
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

function loadPreviousValuesOnUnsuccessfulRedirectBack(oldCustomerId)
{
	$(document).ready(function () {
		$('#route_id').change();
		setTimeout(function () {
			$("#customer_id").val(oldCustomerId);
			$("#customer_id").change();
		}, 2000);
	});
}

function displayIsCompletelyPaid()
{
	$(document).on('change keyup', '.saleDetail', function () {
		var balance = $('#balance').val();
		if (balance <= 0) {
			$('.myCheckbox').attr('checked', 'checked');
		} else {
			$('.myCheckbox').removeAttr('checked');
		}
	});
}

function checkValidit(input)
{
	if ($('#cheque_payment').val().length !== 0) {


		if ($('#cheque_payment_bank_id').val().length === 0) {
			document.getElementById('cheque_payment_bank_id').setCustomValidity("Select the bank.");
		} else {
			document.getElementById('cheque_payment_bank_id').setCustomValidity("");
		}

		if ($('#cheque_payment_cheque_number').val().length === 0) {
			document.getElementById('cheque_payment_cheque_number').setCustomValidity("Ente cheque number.");
		} else {
			document.getElementById('cheque_payment_cheque_number').setCustomValidity("");
		}

		if ($('#cheque_payment_issued_date').val().length === 0) {
			document.getElementById('cheque_payment_issued_date').setCustomValidity("Select the issue date.");
		} else {
			document.getElementById('cheque_payment_issued_date').setCustomValidity("");
		}

		if ($('#cheque_payment_payable_date').val().length === 0) {
			document.getElementById('cheque_payment_payable_date').setCustomValidity("Select the payable date");
		} else {
			document.getElementById('cheque_payment_payable_date').setCustomValidity("");
		}
	} else {
		return;
	}
}

function validateChequeDetails()
{
	$(document).on('change keyup', '#cheque_payment', function () {
		var cheqPay = $('#cheque_payment').val();

		if (cheqPay.length > 0) {
			$("#cheque_payment_bank_id").prop('required', true);
			$("#cheque_payment_cheque_number").prop('required', true);
			$("#cheque_payment_issued_date").prop('required', true);
			$("#cheque_payment_payable_date").prop('required', true);
		} else {
			$("#cheque_payment_bank_id").prop('required', false);
			$("#cheque_payment_cheque_number").prop('required', false);
			$("#cheque_payment_issued_date").prop('required', false);
			$("#cheque_payment_payable_date").prop('required', false);
		}
	});
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
	var result = (sub - cash - cheque ? sub - cash - cheque : 0);
	$('#balance').val(result.toFixed(2));

}

$(document).on('change keyup keypress', '#cash_payment, #cheque_payment, #discount', function () {

	setSubTotal();
	displayBalance();

});

// action on key down
$(document).keydown(function (e) {

	 //alert(e.which);
 
	 
	if (e.which === 112) { 
		
		$('#txtItemCode').focus();
		e.preventDefault();
	} 
	if (e.which === 113) { 
		
		$('#txtItemName').focus();
		e.preventDefault();
	} 
	if (e.which === 114) { 
		
		$('#txtPaidQty').focus();
		e.preventDefault();
	} 
	if (e.which === 115) { 
		
		$('#txtFreeQty').focus();
		e.preventDefault();
	}  
});
 