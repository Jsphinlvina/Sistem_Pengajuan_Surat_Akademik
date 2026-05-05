@extends('starter')
@section('title', 'Edit Program Studi')

@section('content')
@include('pages.program-studi.form-program-studi', [
    'programStudis' => $programStudi,
    'mode' => 'edit'
])
@endsection
