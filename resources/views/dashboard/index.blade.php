@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <h1>Dashboard</h1>
    
    {{-- Used three times - chart.js only loaded onec --}}
    <x-chart id="revenue-chart" label="Revenue" color="#1D9E75" :data="$revenueData" />
    <x-chart id="users-chart"   label="New Users" color="#534AB7" :data="$userData" />
    <x-chart id="orders-chart"  label="Orders" color="#E24B4A" :data="$ordersData" />
@endsection