@extends('starter')
@section('title', 'Tambah Mahasiswa')

@section('content')
    @include('pages.mahasiswa.form-mahasiswa', [
        'mode' => 'create',
        'programStudis' => $programStudis,
    ])
@endsection
