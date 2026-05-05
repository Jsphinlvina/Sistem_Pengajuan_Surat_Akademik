@extends('starter')
@section('title', 'Tambah Program Studi')

@section('content')
@include('pages.program-studi.form-program-studi', [
    'mode' => 'create',
])
@endsection
