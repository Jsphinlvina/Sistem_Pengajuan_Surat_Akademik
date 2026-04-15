@extends('starter')
@section('title', 'Pengajuan Surat')

@section('content')
    @include('pages.pengajuan.form-pengajuan', [
        'mode' => 'create',
        'optionSelect' => false,
        'template' => $template,
        'formFields' => $formFields,
        'showFields' => $showFields,
    ])
@endsection
