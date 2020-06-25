@extends('_layouts.app')

@section('actions')
    @include('_partials.buttons.action-button', [
        "route" => route('backend.warehouse.index'),
        "title" => __("gui.back_to_list")
    ])
@endsection

@section('title')
    @lang('gui.warehouses')
@endsection

@section('content')
    @livewire('common.input' , ['name' => 'name', 'service' => \App\Services\WarehouseService::class])
@endsection
