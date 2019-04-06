@extends('layout')

@section('meta')
    <title> Stránka nenájdená. </title>
@stop

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('css/error.css') }}">
@stop

@section('content')
	<h1>Stránka nenájdená.</h1>
@stop