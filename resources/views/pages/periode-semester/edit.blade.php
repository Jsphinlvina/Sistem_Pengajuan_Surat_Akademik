@extends('starter')
@section('title', 'Edit Periode Semester')
@section('content')

@include('pages.periode-semester.form-periode', [
    'periodeSemester' => $periodeSemester,
    'mode'=> 'edit'
])

@endsection
