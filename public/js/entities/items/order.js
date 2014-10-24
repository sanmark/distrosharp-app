function validateDublicate() {


	$("#formItemOrder").submit(function (event) {

		var error_status = false;
		var buyingOrder = [];
		var sellingOrder = [];
		var htmlError = "";
		var htmlErrorLi = "";

		$("#div-error-message").empty();

		buyingOrder = $('input[name="buyingOrder[]"]').map(function () {
			return this.value;
		}).get();

		sellingOrder = $('input[name="sellingOrder[]"]').map(function () {
			return this.value;
		}).get();


		var sortedBuyingOrder = buyingOrder.sort();
		var resultsBuyingOrder = [];
		var sortedSellingOrder = sellingOrder.sort();
		var resultsSellingOrder = [];


		for (var i = 0; i < buyingOrder.length - 1; i++) {
			if (sortedBuyingOrder[i + 1] === sortedBuyingOrder[i]) {
				resultsBuyingOrder.push(sortedBuyingOrder[i]);
			}
		}

		if (resultsBuyingOrder.length !== 0) {
			addErrorClass(buyingOrder, resultsBuyingOrder, "buyingOrderId_");
			htmlErrorLi += "<li>The Buying Invoice Order can not be duplicated.</li>"; 
			error_status = true;
		}

		for (var i = 0; i < sellingOrder.length - 1; i++) {
			if (sortedSellingOrder[i + 1] === sortedSellingOrder[i]) {
				resultsSellingOrder.push(sortedSellingOrder[i]);
			}
		}

		if (resultsSellingOrder.length !== 0) {
			addErrorClass(sellingOrder, resultsSellingOrder, "sellingOrderId_");
			htmlErrorLi += "<li>The Selling Invoice Order can not be duplicated.</li>"; 
			error_status = true;
		}

		htmlError += "<div class='alert alert-danger alert-dismissible' role='alert'>";
		htmlError += "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>×</span><span class='sr-only'>Close</span></button>";
		htmlError += "<ul>"+htmlErrorLi+"<ul>";
		htmlError += "</div>";

		if (error_status) {
			$("#div-error-message").append(htmlError);
			event.preventDefault();

		}

	});



}

function addErrorClass(all, duplicates, id) {

	$(".alert").remove();

	$("input").removeClass("duplicate-error")
	
	$.each(all, function (k, v) {

		if (!$.inArray(v, duplicates)) { 
			$("#" + id + k).addClass('duplicate-error');
		}
	});

}
 