@extends('layouts.base')
@section('title', 'Checks')
@section('content')

<div class="container-md align-items-center">
    <h1 class="display-4 mt-5">Website: {{ $url->name }}</h1>
    <table class="table table-bordered table-hover mt-3">
        <tr>
            <td>Id</td>
            <td>{{ $url->id }}</td>
        </tr>
        <tr>
            <td>Name</td>
            <td>{{ $url->name }}</td>
        </tr>
        <tr>
            <td>Created at</td>
            <td>{{ $url->created_at }}</td>
        </tr>
    </table>
    <h2 class="display-4 mt-5">Checks</h2>
    <form method="post" action="{{ route('urls.checks.store', ['url' => $url->id ]) }}">
        @csrf
        <button type="submit" class="btn btn-primary my-3">Run check</button>
    </form>
    <table class="table table-bordered table-hover my-3">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Response code</th>
                <th scope="col">h1</th>
                <th scope="col">Title</th>
                <th scope="col">Description</th>
                <th scope="col">Created at</th>
            </tr>
        </thead>
        <tbody>
            @foreach($checks as $check)
                <tr>
                    <td>{{ $check->id }} </td>
                    <td>{{ $check->status_code }} </td>
                    <td><div class="d-inline-block  text-truncate" style="max-width: 150px;"> {{ $check->h1 }} </div></td>
                    <td><div class="d-inline-block  text-truncate" style="max-width: 150px;"> {{ $check->title }} </div></td>
                    <td><div class="d-inline-block  text-truncate" style="max-width: 150px;"> {{ $check->description }} </div></td>
                    <td>{{ $check->created_at }} </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
