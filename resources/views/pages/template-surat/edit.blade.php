@extends('starter')
@section('title', 'Edit Template Surat')

@section('content')
    @include('pages.template-surat.form-template-surat', [
        'mode' => 'edit',
        'templateSurat' => $templateSurat,
        'optionSelect' => false
    ])
@endsection
