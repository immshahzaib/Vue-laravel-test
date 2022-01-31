@extends('layouts.app')

@section('content')

    <edit-employee :req="req" id="{{ $id }}" :base_url="req_url"></edit-employee>

@endsection

