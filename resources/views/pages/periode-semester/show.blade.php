@extends('starter')
@section('title', 'Detail Periode Semester')
@section('content')

@include('pages.periode-semester.form-periode', [
    'periodeSemester' => $periodeSemester,
    'mode'=>'show'
])


@endsection
