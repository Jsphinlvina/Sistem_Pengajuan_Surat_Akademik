@extends('starter')
@section('title', 'Tambah Template Surat')

@section('content')
    @include('pages.template-surat.form-template-surat', [
        'mode' => 'create',
        'optionSelect' => false
    ])
@endsection
