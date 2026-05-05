@extends('starter')
@section('title', 'Tambah Kurikulum')
@section('content')

@include('pages.kurikulum.form-kurikulum', [
    'mode'=>'create'
])

@endsection
