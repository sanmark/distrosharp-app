@extends('web._templates.template')

@section('body')

<div class="panel panel-default well">
	<div class="panel-heading">
		<h3 class="panel-title">Add Transfer</h3>
	</div>
	<div class="panel-body">
		<p>Transfer Items from stock <b>{{$fromStock->name}}{{Form::hidden('fromStock',$fromStock->id,['id'=>'fromStock'])}}</b> to stock <b>{{$toStock->name}}{{Form::hidden('toStock',$toStock->id,['id'=>'toStock'])}}{{Form::hidden('loadedItems',$LoadedItems,['id'=>'loadedItems'])}}</b>.</p>
		{{Form::hidden('loadedItemNames',$loadedItemNames,['id'=>'loadedItemNames'])}}
		{{Form::open(['class'=>'form-horizontal', 'role'=>'form','id'=>'transferForm'])}}
		<br />

		<div class="form-group">
			{{Form::label('date_time', 'Date and Time', array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-4">
				{{Form::input ( 'datetime-local', 'date_time',$dateTime, array('class' => 'form-control','required'=>true,'step'=>'1'))}}
			</div>
		</div>
		<br/>
		<div class="form-group" id="scrollTopTransfers">
			<div class="col-sm-10 col-sm-offset-2">
				<div class="row transfer-row">
					<div class="col-sm-8">
						<div class="row">
							<div class="col-sm-2"><b>Item Code</b><span>(F1)</span></div>
							<div class="col-sm-6"><b>Item Name</b><span>(F2)</span></div>
							<div class="col-sm-2 text-right"><b>Available</b></div>
							<div class="col-sm-2 text-right item-transfer"><b>Transfer</b><span>(F3)</span></div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="row">
							@if($isUnloaded==1)
							<div class="col-sm-4 text-right"><b>Imbalance Transfer</b></div>
							{{Form::hidden('is_unload',$isUnloaded,['id'=>'isUnload'])}}
							@endif
							<div class="col-sm-4 text-right"><b>Target</b></div>
							<div class="col-sm-4 text-right"><b></b></div>
						</div>
					</div>
				</div>			
			</div>			
		</div>
		<div class="form-group">
			<div class="col-sm-10 col-sm-offset-2">
				<div class="row transfer-row">
					<div class="col-sm-8">
						<div class="row">
							<div class="col-sm-2">
								{{Form::text('txtItemCode', null, array('id' => 'txtItemCode', 'class' => 'form-control', 'autocomplete' => 'off','onClick'=>'this.select();'))}} 
								<div id="dublicate-error-message"></div>
								{{Form::input('hidden','txtItemId', null, array('id' => 'txtItemId'))}}
							</div>
							<div class="col-sm-6 item-name">
								{{Form::text('txtItemName', null, array('id' => 'txtItemName', 'class' => 'form-control', 'autocomplete' => 'off','onClick'=>'this.select();'))}}
								<img src="../../images/loading_small.gif" style="display: none; position: absolute; margin: -27px 3px 0px 77px;" id="loader-img">
								<ul id="item_list_f_transfer" class="item-list-main-bar"> </ul> 
							</div>
							<div class="col-sm-2">
								{{Form::input('number','txtAvailable', null, array('id' => 'txtAvailable', 'class' => 'form-control text-right', 'disabled'))}}
							</div> 
							<div class="col-sm-2 item-transfer">
								{{Form::input('number','txtTransfer', null, array('id' => 'txtTransfer', 'class' => 'form-control text-right'))}}
							</div> 
						</div>
					</div> 
					<div class="col-sm-4">
						<div class="row">
							@if($isUnloaded==1)
							<div class="col-sm-4">
								{{Form::input('number','txtImbalanceTransfer', null, array('id' => 'txtImbalanceTransfer', 'class' => 'form-control text-right empty cal_return_line_tot','disabled', 'step'=>'0.01'))}}
							</div>
							@endif
							<div class="col-sm-4">
								{{Form::input('number','txtTargetQuantity', null, array('id' => 'txtTargetQuantity', 'class' => 'form-control text-right empty cal_return_line_tot','disabled', 'step'=>'0.01'))}}
							</div>
							<div class="col-sm-4"> 
								<div class="btn btn-primary" id="add-new-transfer" style="margin: 0px;">Add</div>  
							</div> 
						</div>
					</div>
				</div>
			</div>
			<br/>
			<br/>
			<br/>
			<div class="form-group">
				<div class="col-sm-10 col-sm-offset-2">
					<div class="row">					
						<div class="col-sm-12" id="table-sales-list"></div>
					</div>
				</div>   
			</div>
			<div class="form-group">
				{{Form::label('description', null, array('class' => 'col-sm-2 control-label'))}}
				<div class="col-sm-4">
					{{Form::textarea('description', null, array('class' => 'form-control'))}}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-4">
					{{Form::input('hidden', 'current_edit_sales_id',null, ['id'=>'current_edit_sales_id'])}}
					{{Form::hidden('submitItems',null,['id'=>'submitItems'])}}
					{{Form::submit('Submit', array('class' => 'btn btn-primary pull-right'))}}

				</div>
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
</div>
@stop
@section('file-footer')
<script src="/js/processes/transfers/add.js"></script>
<script src="/js/processes/transfers/add-transfer.js" type="text/javascript"></script>
<script>
addTransferRow();
autoloadItemForTransfer("{{csrf_token()}}");
selectTransferItem("{{csrf_token()}}");
editTransferItem();
deleteTransferItem();
setMethodToEnter();
validateTransferOnSubmit();
</script>
@stop