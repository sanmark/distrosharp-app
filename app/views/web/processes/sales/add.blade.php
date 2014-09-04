@extends('web._templates.template')

@section('body')

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Add Sale</h3>
	</div>
	<div class="panel-body">

		{{Form::open(['class'=>'form-horizontal', 'role'=>'form'])}}
		<br />
		<div class="form-group">
			{{Form::label('date_time', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::input('datetime-local','date_time', null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('customer_id', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::select('customer_id',$customers, null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('printed_invoice_number', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::text('printed_invoice_number', null, array('class' => 'form-control'))}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('discount', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::input('number','discount', null, array('class' => 'form-control'), ['step'=>0.01])}}
			</div>
		</div>
		<div class="form-group">
			{{Form::label('is_completely_paid', null, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-3">
				{{Form::checkbox('is_completely_paid',TRUE,null,array('style'=>'margin-top:10px;'))}}
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<div class="row">
					<div class="col-sm-3">
						<div class="row">
							<div class="col-sm-5"><b>Available</b></div>
							<div class="col-sm-7"><b>Price</b></div>
						</div>
					</div>
					<div class="col-sm-9">
						<div class="row">
							<div class="col-sm-2"><b>Paid Q</b></div>
							<div class="col-sm-2"><b>Free Q</b></div>
							<div class="col-sm-2"><b>GR Price</b></div>
							<div class="col-sm-2"><b>GR Q</b></div>
							<div class="col-sm-2"><b>CR Price</b></div>
							<div class="col-sm-2"><b>CR Q</b></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		@foreach($items as $item)
		<div class="form-group">
			{{Form::label(null, $item->name, array('class' => 'col-sm-2 control-label'))}}
			<div class="col-sm-10">
				<div class="row">

					<div class="col-sm-3">
						<div class="row">
							<div class="col-sm-5">
								<p style="padding-top: 7px;">{{$stockDetails[$item->id]['good_quantity']}}</p>
								{{Form::hidden('items['.$item->id.'][available_quantity]', $stockDetails[$item->id]['good_quantity'])}}
							</div>
							<div class="col-sm-7">
								{{Form::input('number','items['.$item->id.'][price]',$item->current_selling_price, array('class' => 'form-control'),['step'=>0.01])}}
							</div>
						</div>
					</div>

					<div class="col-sm-9">
						<div class="row">
							<div class="col-sm-2">
								{{Form::input('number','items['.$item->id.'][paid_quantity]',NULL, array('class' => 'form-control'))}}
							</div>
							<div class="col-sm-2">
								{{Form::input('number','items['.$item->id.'][free_quantity]',NULL, array('class' => 'form-control'))}}
							</div>
							<div class="col-sm-2">
								{{Form::input('number','items['.$item->id.'][good_return_price]',NULL, array('class' => 'form-control'),['step'=>0.01])}}
							</div>
							<div class="col-sm-2">
								{{Form::input('number','items['.$item->id.'][good_return_quantity]',NULL, array('class' => 'form-control'))}}
							</div>
							<div class="col-sm-2">
								{{Form::input('number','items['.$item->id.'][company_return_price]',NULL, array('class' => 'form-control'),['step'=>0.01])}}
							</div>
							<div class="col-sm-2">
								{{Form::input('number','items['.$item->id.'][company_return_quantity]',NULL, array('class' => 'form-control'))}}
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		@endforeach

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				{{Form::submit('Submit', array('class' => 'btn btn-default pull-right'))}}
			</div>
		</div>

		{{Form::close()}}

	</div>
</div>

@stop