@extends('starter')
@section('title', 'Edit Mahasiswa')

@section('content')
    @include('pages.mahasiswa.form-mahasiswa', [
        'mode' => 'edit',
        'mahasiswa' => $mahasiswa,
        'programStudis' => $programStudis,
        'optionSelect' => false
    ])
@endsection
