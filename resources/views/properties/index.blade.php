@extends('layouts.app')

@section('title', 'Properties | '. config('app.name'))

@section('content')

    <livewire:property-list />

@endsection
