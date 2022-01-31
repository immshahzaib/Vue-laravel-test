@extends('layouts.app')

@section('content')

    <create-company :req="req" :base_url="req_url"></create-company>

@endsection
