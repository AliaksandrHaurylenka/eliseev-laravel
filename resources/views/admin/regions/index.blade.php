@extends('layouts.app')

@section('breadcrumbs')
    {!! Breadcrumbs::render(); !!}
@endsection

@section('content')
    @include('admin.regions._nav')

    <p><a href="{{ route('admin.regions.create') }}" class="btn btn-success">Add Region</a></p>


    @include('admin.regions._list', ['regions' => $regions])

    {{ $regions->links() }}
@endsection