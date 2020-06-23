@extends('_layouts.app')

@section('actions')
    @include('_partials.buttons.action-button', [
        "route" => route('backend.warehouse.create'),
        "title" => __("gui.add")
    ])
@endsection

@section('title')
    @lang('gui.warehouses')
@endsection


@section('content')
    tes
@endsection
