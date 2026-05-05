@extends('starter')
@section('title', 'Detail Kurikulum')
@section('content')

@include('pages.kurikulum.form-kurikulum', [
    'kurikulum' => $kurikulum,
    'mode'=>'show'
])


@endsection
