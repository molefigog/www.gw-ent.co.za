@extends('layouts.master')

@section('content')
<br>
<hr>
<div class="row">
    @foreach($albums as $album)
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $album->name }}</h5>
                    <p class="card-text">Released on: {{ $album->release_date }}</p>
                </div>
                <div class="card-footer">
                    <iframe src="https://open.spotify.com/embed/album/{{ $album->id }}" width="100%" height="80" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
                </div>
            </div>
        </div>
    @endforeach
</div>
    @endsection
