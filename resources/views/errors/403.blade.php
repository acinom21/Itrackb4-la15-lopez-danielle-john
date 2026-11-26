@extends('layouts.app')

@section('title', 'Forbidden')

@section('content')
    <h1>403 | Forbidden</h1>
    <p>You are not allowed to do that.</p>
    <a href="{{ route('books.index') }}">Back to the list</a>
@endsection
