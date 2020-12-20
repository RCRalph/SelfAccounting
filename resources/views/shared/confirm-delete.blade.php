@extends('layouts.midwidth')

@section('content')
    <div class="{{ $pageData["darkmode"] ? "dark-card" : "card" }}">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-trash"></i>
                Delete profile
            </div>
        </div>

        <div class="card-body">
            <div class="h3 text-center font-weight-bold">
                Are you sure you want to delete this&nbsp;profile?
            </div>

            <div class="row mt-4">
                <div class="offset-sm-2 col-sm-4 col-12 mb-2 mb-sm-0">
                    <a role="button" href="{{ $links["yes"] }}" class="big-button-success">
                        <i class="fas fa-check"></i>
                        Yes
                    </a>
                </div>

                <div class="col-sm-4 col-12 mt-2 mt-sm-0">
                    <a role="button" href="{{ $links["no"] }}" class="big-button-danger">
                        <i class="fas fa-times"></i>
                        No
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
