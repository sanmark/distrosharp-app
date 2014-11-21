function displayBalance()
{ 
		var sub = SanmarkJsHelper.Input.get('#subTotal');
		var cash = SanmarkJsHelper.Input.get('#cash_payment');
		var cheque = SanmarkJsHelper.Input.get('#cheque_payment');
		$('#balance').val((sub - cash - cheque ? sub - cash - cheque : 0).toFixed(2)); 
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



function setMethodToEnter() {

	$(document).on("keypress", 'form', function (e) {

		var code = e.keyCode || e.which;
		var target_id = e.target.id;

		if (code === 13) {

			if (target_id === 'txtPrice' || target_id === 'txtQuantity' || target_id === 'txtFreeQuantity' || target_id === 'txtExpireDate' || target_id === 'txtPurchaseLineTot' || target_id === 'txtBatchNumber') {
				jQuery('#add-new-purchase').click();
			}

			e.preventDefault();
			return false;
		}

	});

}

function clearError() {

	$("input").removeClass("duplicate-error");
}

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
		
		$('#txtQuantity').focus();
		e.preventDefault();
	} 
	if (e.which === 115) { 
		
		$('#txtFreeQuantity').focus();
		e.preventDefault();
	}  
});
 