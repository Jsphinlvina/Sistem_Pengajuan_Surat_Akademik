@extends('starter')
@section('title', 'Tambah User')

@section('content')
    @include('pages.user.form-user', [
        'mode' => 'create',
        'programStudis' => $programStudis,
        'optionSelect' => false
    ])
@endsection
