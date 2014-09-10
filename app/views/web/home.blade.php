@extends('web._templates.template')

@section('body')
<div class="row">
	<div class="col-lg-5 col-md-5">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Daily Work Flow</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					{{ Form::open() }}
					{{Form::hidden('submitedForm','dailyWorkFlow')}}
					<div class="form-group">
						{{Form::label('the_date','Select Date', array('class' => 'col-sm-1 col-md-3 control-label'))}}
						<div class="col-sm-6">
							<!-- 1 is the default value, get it from the controller -->
							{{Form::input('date','the_date',$today, array('class' => 'col-sm-1 col-md-3 form-control'))}}

						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-3">
							{{Form::submit('Submit', array('class' => 'btn btn-default pull-right'))}}</td>
						</div>
					</div>

					{{ Form::close() }}
				</div>
				<br>

				<table class="table table-bordered">
					<tr>
						<th>Process</th><th>Description</th>
					</tr>
					<tr>
						<td>Purchase/s</td><td>{{$buyingInvoices}}</td>
					</tr>
					@foreach($transferDetails as $transfer)
					<tr>
						<td>Transfer</td><td>{{$transfer->fromStock->name}} ({{$transfer->fromStock->incharge->username}}) to {{$transfer->toStock->name}} ({{$transfer->toStock->incharge->username}}) </td>

					</tr>
					@endforeach
					@if(!empty($reps))
					@foreach($reps as $rep)
					<tr>
						<td>Sales ({{$rep->first_name}})</td>
						<td>{{$rep->sellingInvoices->count()}}</td>
					</tr>
					@endforeach
					@endif
					<tr>
						<td>Vehicle Summery Verified</td><td>Yes</td>
					</tr>
					<tr>
						<td>Daily Summery Verified</td><td>No</td>
					</tr>
				</table>

			</div>
		</div>

	</div>
</div>
@stop