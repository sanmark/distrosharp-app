$(document).on('change keyup', '#txtPrice,#txtQuantity', function () {
	var price = SanmarkJsHelper.Input.get('#txtPrice');

	var quantity = SanmarkJsHelper.Input.get('#txtQuantity');
	var lineTotal = Number(quantity) * Number(price);
	$('#txtReturnLineTot').val(lineTotal);
});
$(document).on('change', '#from_stock', function () {
	emptyReturnInputs()
	$('#table-company-return-list').empty();
});
