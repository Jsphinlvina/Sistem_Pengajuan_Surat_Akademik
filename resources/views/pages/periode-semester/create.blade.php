@extends('starter')
@section('title', 'Tambah Periode Semester')
@section('content')

@include('pages.periode-semester.form-periode', [
    'mode'=>'create'
])

@endsection
