@extends('starter')
@section('title', 'Edit Kurikulum')
@section('content')

@include('pages.kurikulum.form-kurikulum', [
    'kurikulum' => $kurikulum,
    'mode'=> 'edit'
])

@endsection
