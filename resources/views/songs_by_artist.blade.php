@extends('layouts.master')

@section('content')
{{-- <livewire:artists :artistName="$artistName" /> --}}
@livewire('artists', ['artistName' => str_replace(' ', '-', $artistName)])

@endsection
