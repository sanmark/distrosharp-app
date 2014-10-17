@extends('web._templates.template')

@section('body')
<h2>Imbalance Stock</h2>
{{Form::open()}}
{{Form::label('imbalance_stock')}}
{{Form::select('imbalance_stock', $stocksForHtmlSelect, $imbalanceStockId)}}
{{Form::submit('Submit')}}
{{Form::close()}}
@stop