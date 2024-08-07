@extends('layouts.master')

@section('content')
    <div class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card mb-5">
                        <div class="card-header">
                            <h2 class="font-weight-bold mb-0">Avatar</h2>
                        </div>
                        <div class="card-body">
                            @include('profiles.partials.avatar-update')
                        </div>
                    </div>
                    <div class="card mb-5">
                        <div class="card-header">
                            <h2 class="font-weight-bold mb-0">Profile</h2>
                        </div>
                        <div class="card-body">
                            @include('profiles.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="card mb-5">
                        <div class="card-body">
                            @include('profiles.partials.update-password-form')
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
