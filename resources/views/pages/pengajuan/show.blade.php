@extends('starter')
@section('title', 'Detail Pengajuan')

@section('content')
    @include('pages.pengajuan.form-pengajuan', [
        'mode' => 'show',
        'optionSelect' => false,
        'template' => $template,
        'formFields' => $formFields,
        'showFields' => $showFields,
        'pengajuan' => $pengajuan
    ])
@endsection
