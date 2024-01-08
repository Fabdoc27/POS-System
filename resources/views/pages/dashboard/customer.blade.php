@extends('layouts.sidenav_layout')

@section('content')
    @include('components.customer.customerList')
    @include('components.customer.customerCreate')
    @include('components.customer.customerUpdate')
    @include('components.customer.customerDelete')
@endsection
