@extends('layouts.app')

@push('head')
 <title>VPN Admin Manager</title>
@endpush

@push('css')
    <link nonce={{ $nonce }} href="{{ module_mix('css/app.css') }}" rel="stylesheet">
@endpush

@section('content')
    @inertia
@endsection

@push('js')
    <script nonce={{ $nonce }} src="{{ module_mix('js/app.js') }}"></script>
@endpush
