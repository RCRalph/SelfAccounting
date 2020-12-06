@extends('layouts.fullmidwidth')

@section('content')
    <div class="{{ $pageData["darkmode"] ? "dark-card" : "card" }}">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-box-open"></i>
                Bundles
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                @forelse($bundles as $bundle)
                    <div class="col-12 col-lg-6 my-2">
                        <a href="/bundles/{{ $bundle->id }}" class="text-decoration-none">
                            <div class="bundle-wrapper-{{ $pageData["darkmode"] ? "dark" : "light" }}">
                                <div class="image" style="background-image: url(/img/bundles/thumbnails/{{ $bundle->thumbnail }});"></div>

                                <div class="title">
                                    {{ $bundle->title }}
                                </div>

                                <div class="description">
                                    {{ Markdown::parse($bundle->short_description) }}
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                <div class="col-12">
                    <h1 class="text-center">Nothing to see here, for now...</h1>
                </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
