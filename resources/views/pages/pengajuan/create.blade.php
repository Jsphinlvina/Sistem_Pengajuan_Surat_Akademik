@extends('starter')
@section('title', 'Pengajuan Surat')

@section('content')
    @include('pages.pengajuan.form-pengajuan', [
        'mode' => 'create',
        'mahasiswa' => $mahasiswa,
        'optionSelect' => false
    ])
@endsection
