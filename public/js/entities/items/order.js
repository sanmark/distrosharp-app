function validateDublicate() {
	
	$("#formItemOrder").submit(function (event) {

		$(".alert").remove();
		$("input").removeClass("duplicate-error")

		var error_status = false;
		var htmlError = "";
		var htmlErrorLi = "";
		var body = $("html, body");

		$("#div-error-message").empty();

		var buyingOrder = $('input[name="buyingOrder[]"]').map(function () {
			return this.value;
		}).get();

		var sellingOrder = $('input[name="sellingOrder[]"]').map(function () {
			return this.value;
		}).get();


		var buyingOrderForCss = $('input[name="buyingOrder[]"]').map(function () {
			return this.value;
		}).get();

		var sellingOrderForCss = $('input[name="sellingOrder[]"]').map(function () {
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
			addErrorClass(buyingOrderForCss, resultsBuyingOrder, "buyingOrderId_");
			htmlErrorLi += "<li>The Buying Invoice Order can not be duplicated.</li>";
			error_status = true;
		}

		for (var i = 0; i < sellingOrder.length - 1; i++) {
			if (sortedSellingOrder[i + 1] === sortedSellingOrder[i]) {
				resultsSellingOrder.push(sortedSellingOrder[i]);
			}
		}

		if (resultsSellingOrder.length !== 0) {
			addErrorClass(sellingOrderForCss, resultsSellingOrder, "sellingOrderId_");
			htmlErrorLi += "<li>The Selling Invoice Order can not be duplicated.</li>";
			error_status = true;
		}

		htmlError += "<div class='alert alert-danger alert-dismissible' role='alert'>";
		htmlError += "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>×</span><span class='sr-only'>Close</span></button>";
		htmlError += "<ul>" + htmlErrorLi + "<ul>";
		htmlError += "</div>";

		if (error_status) {

			body.animate({scrollTop: 75}, '900', 'swing', function () {
			});

			$("#div-error-message").append(htmlError);
			event.preventDefault();
		}

	});

}

function addErrorClass(all, duplicates, id) {

	$.each(all, function (k, v) {

		if (!$.inArray(v, duplicates)) {
			$("#" + id + k).addClass('duplicate-error');
		}
	});

}
 