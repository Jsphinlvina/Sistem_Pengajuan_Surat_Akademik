@extends('starter')
@section('title', 'Edit Data Mahasiswa')

@section('content')
@include('pages.mahasiswa.form-mahasiswa', [
    'mahasiswa' => $mahasiswa,
    'mode' => 'edit'
])
@endsection
