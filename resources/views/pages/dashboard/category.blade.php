@extends('layouts.sidenav_layout')

@section('content')
    @include('components.category.categoryList')
    @include('components.category.categoryCreate')
    @include('components.category.categoryUpdate')
    @include('components.category.categoryDelete')
@endsection
