@extends('starter')
@section('title', 'Edit User')

@section('content')
    @include('pages.user.form-user', [
        'mode' => 'edit',
        'user' => $user,
        'programStudis' => $programStudis,
        'optionSelect' => false
    ])
@endsection
