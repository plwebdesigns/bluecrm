@extends('layouts.one')

@section('content')
    <div class="container">
        <h1 id="successHeader">Successfully entered sale!</h1>
        <p>Would you like to enter another sale?</p>
        <a class="btn btn-success" href="{{ route('add_sale') }}">Add Sale</a>
    </div>
@endsection