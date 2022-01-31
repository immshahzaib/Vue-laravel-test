@extends('layouts.app')

@section('content')

    <create-employee :req="req" :base_url="req_url"></create-employee>

@endsection
