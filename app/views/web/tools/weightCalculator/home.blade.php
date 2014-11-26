@extends ('web._templates.template')

@section('body')
<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Weight Calculator</h3>
	</div>
	<div class="panel-body">


		<div class="form-group" style="margin-bottom: 3px;"  id="scrollTop">
			<div class="col-sm-12 col-sm-offset-0"> 
				<div class="row" id="top-item-lable">
					<div class="col-sm-7">
						<div class="row">
							<div class="col-sm-3"><b>Item Code</b> <span>(F1)</span></div>
							<div class="col-sm-5"><b>Item Name</b> <span>(F2)</span></div>
							<div class="col-sm-2 text-right"><b>Unit Weight (g)</b></div>
							<div class="col-sm-2 text-right"><b>Buying Price</b></div>
						</div>
					</div>
					<div class="col-sm-5">
						<div class="row">
							<div class="col-sm-3 text-right"><b>Quantity</b> <span>(F3)</span></div>
							<div class="col-sm-3 text-right"><b>Line Weight (kg)</b> </div>
							<div class="col-sm-3 text-right"><b>Line Total</b></div>
							<div class="col-sm-3 text-right">&nbsp</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-12 col-sm-offset-0">
				<div class="row">					
					<div class="col-sm-7"> 
						<div class="row"> 
							<div class="col-sm-3">
								{{Form::text('txtItemCode', null, array('id' => 'txtItemCode', 'class' => 'form-control', 'autocomplete' => 'off','onClick'=>'this.select();'))}}
								<img src="../../images/loading_small.gif" style="display: none; position: absolute; margin: -26px 0px 0px 125px;" id="loader-img-code">
								<div id="dublicate-error-message"></div>
								{{Form::input('hidden','txtItemId', null, array('id' => 'txtItemId'))}}
							</div>

							<div class="col-sm-5">
								{{Form::text('txtItemName', null, array('id' => 'txtItemName', 'class' => 'form-control', 'autocomplete' => 'off','onClick'=>'this.select();'))}}
								<img src="../../images/loading_small.gif" style="display: none; position: absolute; margin: -26px 0px 0px 246px;" id="loader-img">
								<ul id="item_list" class="item-list-main-bar"> </ul> 
							</div> 

							<div class="col-sm-2">
								{{Form::input('number','txtUnitWeight', null, array('id' => 'txtUnitWeight', 'class' => 'form-control text-right empty cal_return_line_tot', 'readonly'=>TRUE, 'step'=>'0.01'))}}
							</div> 

							<div class="col-sm-2">
								{{Form::input('number','txtBuyingPrice', null, array('id' => 'txtBuyingPrice', 'class' => 'form-control text-right empty cal_return_line_tot',  'readonly'=>TRUE, 'step'=>'0.01'))}}
							</div>

						</div>
					</div>

					<div class="col-sm-5">
						<div class="row">
							<div class="col-sm-3">
								{{Form::input('number','txtQuantity', null, array('id' => 'txtQuantity', 'class' => 'form-control text-right empty cal_return_line_tot', 'min'=>'0'))}}
							</div> 

							<div class="col-sm-3">
								{{Form::input('number','txtLineWeight', null, array('id' => 'txtLineWeight', 'class' => 'form-control text-right empty cal_return_line_tot', 'readonly'=>TRUE,  'step'=>'0.01'))}}
							</div>

							<div class="col-sm-3">
								{{Form::input('number','txtLineTotal', null, array('id' => 'txtLineTotal', 'class' => 'form-control text-right empty', 'readonly'=>TRUE, 'step'=>'0.01'))}} 
							</div>
							<div class="col-sm-3"> 
								<div class="btn btn-primary pull-right" id="add-new-row" style="margin: 0px;">Add</div>  
							</div> 
						</div>
					</div>
				</div>
			</div>
		</div>

		<br><br><br>


		<div class="form-group">
			<div class="col-sm-12 col-sm-offset-0">
				<div class="row">					
					<div class="col-sm-12" id="table-item-list"> </div>
				</div>
			</div>   
		</div>
 
		<div class="form-group" style="margin-bottom: 3px;">
			<div class="col-sm-12 col-sm-offset-0"> 
				<div class="row">
					<div class="col-sm-7"> </div>
					<div class="col-sm-5"><br> 
						<div class="row">
							<div class="col-sm-3 text-right"><b id="total_text"> </b></div>
							<div class="col-sm-3 text-right" > 
								<b id="lineWeightTotal" style="margin: 0px 9px 0px 0px; border-bottom: double 3px;"></b> 
							</div>
							<div class="col-sm-3 text-right"> 
								<b id="subLineTotal" style="margin: 0px 9px 0px 0px; border-bottom: double 3px;"></b> 
							</div>
							<div class="col-sm-3 text-right">&nbsp</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br>


	</div>
</div>
{{Form::input('hidden','current_edit_row_id', null, array('id' => 'current_edit_row_id'))}}
@stop


@section('file-footer')

<script src="/js/tool/weightCalculator/add.js"></script> 

<script>

selectSalesItem("{{csrf_token()}}");
autoloadItemForSales("{{csrf_token()}}");
addItemRow();
setMethodToEnter();
editRow();
deleteSales()


</script>
@stop
