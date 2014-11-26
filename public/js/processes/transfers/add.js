	$(document).on('change keyup', '#txtTransfer', function () {
		var value = SanmarkJsHelper.Input.get(this);
		
		var available = SanmarkJsHelper.Input.get('#txtAvailable');
		var imbalanceTransfer = Number(available) - Number(value);
		$('#txtImbalanceTransfer').val(imbalanceTransfer);
	});



