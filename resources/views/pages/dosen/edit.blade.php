@extends('starter')
@section('title', 'Edit Data Dosen')

@section('content')
@include('pages.dosen.form-dosen', [
    'dosen' => $dosen,
    'mode' => 'edit'
])
@endsection
