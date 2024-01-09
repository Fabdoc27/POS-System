@extends('layouts.sidenav_layout')

@section('content')
    @include('components.product.productList')
    @include('components.product.productCreate')
    @include('components.product.productUpdate')
    @include('components.product.productDelete')
@endsection
