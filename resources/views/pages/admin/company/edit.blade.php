@extends('layouts.app')

@section('content')

    <edit-company :req="req" id="{{ $id }}" :base_url="req_url"></edit-company>

@endsection
