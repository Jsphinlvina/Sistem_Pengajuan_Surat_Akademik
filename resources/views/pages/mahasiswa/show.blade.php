@extends('starter')
@section('title', 'Detail Mahasiswa')

@section('content')
    @include('pages.mahasiswa.form-mahasiswa', [
        'mode' => 'show',
        'mahasiswa' => $mahasiswa,
    ])
@endsection
