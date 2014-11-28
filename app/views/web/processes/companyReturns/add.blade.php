@extends('web._templates.template')

@section('body')
<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Add Company Returns</h3>
	</div>
	<div class="panel-body">

		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('date', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::input ( 'datetime-local','date_time',$currentDateTime, array('class' => 'form-control','required'=>true,'step'=>'1'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('vendor', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('vendor_id',$vendorsList, null, array('tabindex'=> '1','class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('return_number', 'Return Number', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::text('return_number',null, array('tabindex'=> '2','class' => 'form-control','required'=>true))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('from_stock', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('from_stock',$stocksList,null, array('tabindex'=> '3','class' => 'form-control','required'=>true))}}
				<div id="stock_select_msg"></div>
			</div>
		</div>

		<br>
		<br> 

		<div class="form-group" style="margin-bottom: 3px;"  id="scrollTopSales">
			<div class="col-sm-10 col-sm-offset-2"> 
				<div class="row" id="top-item-lable">
					<div class="col-sm-8">
						<div class="row">
							<div class="col-sm-2"><b>Code</b> <span>(F1)</span></div>
							<div class="col-sm-5"><b>Item Name</b> <span>(F2)</span></div>
							<div class="col-sm-3 text-right"><b>Buying Price</b><span>(F3)</span></div>
							<div class="col-sm-2 text-right"><b>Quantity</b><span>(F4)</span></div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="row">
							<div class="col-sm-6 text-right"><b>Line Total</b></div>
							<div class="col-sm-6 text-right">&nbsp</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-10 col-sm-offset-2">
				<div class="row">					
					<div class="col-sm-8"> 
						<div class="row"> 
							<div class="col-sm-2">
								{{Form::text('txtItemCode', null, array('id' => 'txtItemCode', 'class' => 'form-control', 'autocomplete' => 'off'))}}
								<div id="dublicate-error-message"></div>
								{{Form::input('hidden','txtItemId', null, array('id' => 'txtItemId'))}}
							</div>

							<div class="col-sm-5">
								{{Form::text('txtItemName', null, array('id' => 'txtItemName', 'class' => 'form-control', 'autocomplete' => 'off','onClick'=>'this.select();'))}}
								<img src="../../images/loading_small.gif" style="display: none; position: absolute; margin: -26px 0px 0px 165px;" id="loader-img">
								<ul id="item_list_f_return" class="item-list-main-bar"> </ul> 
							</div> 

							<div class="col-sm-3">
								{{Form::input('number','txtPrice', null, array('id' => 'txtPrice', 'class' => 'form-control text-right empty cal_line_tot', 'step'=>'0.01'))}}

							</div> 

							<div class="col-sm-2">
								{{Form::input('number','txtQuantity', null, array('id' => 'txtQuantity', 'class' => 'form-control text-right empty cal_line_tot' , 'step'=>'0.01'))}}
							</div>

						</div>
					</div>

					<div class="col-sm-4">
						<div class="row">
							<div class="col-sm-6">
								{{Form::input('number','txtReturnLineTot', null, array('id' => 'txtReturnLineTot', 'class' => 'form-control text-right empty', 'disabled', 'step'=>'0.01'))}} 
							</div>

							<div class="col-sm-6"> 
								<div class="btn btn-primary pull-right" id="add-new-company-return" style="margin: 0px;">Add</div>  
							</div> 
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-10 col-sm-offset-2">
				<div class="row">					
					<div class="col-sm-12" id="table-company-return-list"> </div>
				</div>
			</div>   
		</div> 

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				{{Form::input('hidden', 'current_edit_sales_id',null, ['id'=>'current_edit_sales_id'])}}
				{{Form::submit('Submit', array( 'class' => 'btn btn-primary pull-right'))}}
			</div>
		</div>
		{{Form::close()}}
	</div>
</div>
{{Form::input('hidden', 'current_edit_sale_id', NULL, ['id'=>'current_edit_sale_id'])}}
<script src="/js/processes/companyReturns/add.js"></script>
<script src="/js/processes/companyReturns/add-companyReturns.js"></script>
<script>
addCompanyReturnRow();
autoloadItemListForReturn("{{csrf_token()}}");
selectCompanyReturnItem("{{csrf_token()}}");
editCompanyReturnItem();
deleteCompanyReturnItem();
validateIsFilledFields();
</script>
@stop