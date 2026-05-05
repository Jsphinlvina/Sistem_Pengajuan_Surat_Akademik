@extends('starter')
@section('title', 'Tambah Data Mahasiswa')

@section('content')
@include('pages.mahasiswa.form-mahasiswa', [
    'mode' => 'create',
])
@endsection
