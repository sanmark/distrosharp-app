function displayBalance()
{
	$(document).on('change keyup', '.saleDetail', function () {
		var sub = SanmarkJsHelper.Input.get('#subTotal');
		var cash = SanmarkJsHelper.Input.get('#cash_payment');
		var cheque = SanmarkJsHelper.Input.get('#cheque_payment');
		$('#balance').val((sub - cash - cheque ? sub - cash - cheque : 0).toFixed(2));
	});
}

function displayIsCompletelyPaid()
{
	$(document).on('change keyup', '.saleDetail', function () {
		var balance = SanmarkJsHelper.Input.get('#balance');
		if (balance <= 0) {
			$('.myCheckbox').attr('checked', 'checked');
		} else {
			$('.myCheckbox').removeAttr('checked');
		}
	});
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