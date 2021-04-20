@extends('layouts.one')

@section('content')
    <div class="container mt-4">
        <h1 id="successHeader">Successfully entered sale!</h1>
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ url($prev_url)  }}">Add another Sale</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/#/admin">Back to Admin</a>
            </li>
        </ul>
    </div>
@endsection