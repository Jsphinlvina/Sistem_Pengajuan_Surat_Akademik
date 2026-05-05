@extends('starter')
@section('title', 'Tambah Data Dosen')

@section('content')
@include('pages.dosen.form-dosen', [
    'mode' => 'create',
])
@endsection
