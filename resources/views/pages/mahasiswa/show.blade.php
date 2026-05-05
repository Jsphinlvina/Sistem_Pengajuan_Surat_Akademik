@extends('starter')
@section('title', 'Detail Mahasiswa')

@section('content')
    @include('pages.mahasiswa.form-mahasiswa', [
        'mahasiswa' => $mahasiswa,
        'mode' => 'show',
    ])
@endsection
