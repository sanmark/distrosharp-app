function createReport(url, token) {

	$(document).ready(function () {
		getData()
	});

	$('#btn-sub').click(function () {
		getData()
	});


	function getData() {


		$("#btn-sub").prop("disabled", true);
		$("#imgLoader").show();
		$('#tableReports').show();
		$('#emptyMessage').hide();

		var route_id = $('#route_id').val();
		var customer_id = $('#customer_id').val();
		var rep_id = $('#rep_id').val();
		var date_from = $('#date_from').val();
		var date_to = $('#date_to').val();
		var invoice_number = $('#invoice_number').val();

		$.post(
			url,
			{
				_token: token,
				route_id: route_id,
				customer_id: customer_id,
				rep_id: rep_id,
				date_from: date_from,
				date_to: date_to,
				invoice_number: invoice_number

			},
		function (resultjson) {
			var object = $.parseJSON(resultjson);


			if (object.sellingInvoices.length === 0) {
				$('#tableReports').hide();
				$('#emptyMessage').show();
			}

			drowReport(object);
		});
	}


	function drowReport(data) {

		var htmlTable = "";
		var invoiceSubAmountTotal = 0;
		var totalOfDiscountSum = 0;
		var totalAmount = 0;
		var invoiceByCashTotalSum = 0;
		var invoiceByChequeTotalSum = 0;
		var invoiceByCreditTotalSum = 0;
		var creditPaymentsByCash = 0;
		var creditPaymentsByCheque = 0;
		var totalOfInvoiceSum = 0;


		$.each(data.sellingInvoices, function (key, value) {


			var paymentValueByCash = parseFloat(value.paymentValueByCash);
			var paymentValueByCheque = parseFloat(value.paymentValueByCheque);
			var lateCreditPayments = value.lateCreditPayments;
			var invoiceTotal = parseFloat(value.invoiceTotal);
			var discount = parseFloat(value.discount);

			var paidAmount = paymentValueByCash + paymentValueByCheque + discount;

			var invoiceCredit = invoiceTotal - paidAmount;

			var subTotal = paymentValueByCash + paymentValueByCheque + discount + invoiceCredit;

			var total = subTotal - discount;

			var totalCollection = parseFloat(lateCreditPayments.amount_cash) + parseFloat(lateCreditPayments.amount_cheque) + paymentValueByCash + paymentValueByCheque;


			htmlTable += '<tr>';
			htmlTable += '<td>' + value.route + '</td>';
			htmlTable += '<td>' + value.customer + '</td>';
			htmlTable += '<td><a href="../processes/sales/' + value.invoiceNumber + '/edit">' + value.invoiceNumber + '</a></td>';
			htmlTable += '<td class="text-right">' + subTotal.toFixed(2) + '</td>';
			htmlTable += '<td class="text-right">' + discount.toFixed(2) + '</td>';
			htmlTable += '<td class="text-right">' + total.toFixed(2) + '</td>';
			htmlTable += '<td class="text-right">' + paymentValueByCash.toFixed(2) + '</td>';
			htmlTable += '<td class="text-right">' + paymentValueByCheque.toFixed(2) + '</td>';
			htmlTable += '<td class="text-right">' + invoiceCredit.toFixed(2) + '</td>';

			htmlTable += '<td>';

			$.each(value.lateCreditInvoices, function (lateCreditInvoicekey, lateCreditInvoice) {

				if (lateCreditInvoicekey >= 1) {
					htmlTable += ',';
				}

				htmlTable += '<a href="../processes/sales/' + lateCreditInvoice + '/edit">' + lateCreditInvoice + '</a>';

			});

			htmlTable += '</td>';

			htmlTable += '<td class="text-right">' + parseFloat(lateCreditPayments.amount_cash.toFixed(2)) + '</td>';
			htmlTable += '<td class="text-right">' + parseFloat(lateCreditPayments.amount_cheque.toFixed(2)) + '</td>';
			htmlTable += '<td class="text-right">' + totalCollection.toFixed(2) + '</td>';
			htmlTable += '</tr>';

			invoiceSubAmountTotal = invoiceSubAmountTotal + subTotal;
			totalOfDiscountSum = totalOfDiscountSum + discount;
			totalAmount = totalAmount + total;
			invoiceByCashTotalSum = invoiceByCashTotalSum + paymentValueByCash;
			invoiceByChequeTotalSum = invoiceByChequeTotalSum + paymentValueByCheque;
			invoiceByCreditTotalSum = invoiceByCreditTotalSum + invoiceCredit;
			creditPaymentsByCash = creditPaymentsByCash + lateCreditPayments.amount_cash;
			creditPaymentsByCheque = creditPaymentsByCheque + lateCreditPayments.amount_cheque;
			totalOfInvoiceSum = totalOfInvoiceSum + totalCollection;
 
		});


		$('#tableBody').empty();
		$('#tableBody').append(htmlTable);
		$("#imgLoader").hide();
		$("#btn-sub").prop("disabled", false);

		$('#invoiceSubAmountTotal').text(invoiceSubAmountTotal.toFixed(2));
		$('#totalOfDiscountSum').text(totalOfDiscountSum.toFixed(2));
		$('#totalAmount').text(totalAmount.toFixed(2));
		$('#invoiceByCashTotalSum').text(invoiceByCashTotalSum.toFixed(2));
		$('#invoiceByChequeTotalSum').text(invoiceByChequeTotalSum.toFixed(2));
		$('#invoiceByCreditTotalSum').text(invoiceByCreditTotalSum.toFixed(2));
		$('#creditPaymentsByCash').text(creditPaymentsByCash.toFixed(2));
		$('#creditPaymentsByCheque').text(creditPaymentsByCheque.toFixed(2));
		$('#totalOfInvoiceSum').text(totalOfInvoiceSum.toFixed(2));



	}


}