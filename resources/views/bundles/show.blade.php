@extends('layouts.fullmidwidth')

@section('content')
    <div class="{{ $pageData["darkmode"] ? "dark-card" : "card" }}">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-box-open"></i>
                {{ $bundle->title }}
            </div>
        </div>

        <div class="card-body">
            @if (count($images))
                <div class="d-flex justify-content-center align-items-center w-100" style="max-height: 400px;">
                    <div id="image-gallery" class="carousel slide w-75" data-ride="carousel">
                        <ol class="carousel-indicators carousel-{{ $pageData["darkmode"] ? 'dark' : 'light' }}mode">
                            @foreach ($images as $index => $image)
                                <li
                                    data-target="#image-gallery"
                                    data-slide-to="{{ $index }}"
                                    class="{{ $index == 0 ? "active" : "" }}"
                                ></li>
                            @endforeach
                        </ol>

                        <div class="carousel-inner">
                            @foreach ($images as $index => $image)
                                <div style="height: 375px;" class="carousel-item {{ $index == 0 ? "active" : "" }}">
                                    <img
                                        class="bundle-image"
                                        src="{{ $image }}"
                                        style="object-fit: cover;"
                                        alt="Slide {{ $index + 1 }}"
                                    >
                                </div>
                            @endforeach
                        </div>

                        <a class="carousel-control-prev" href="#image-gallery" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon carousel-{{ $pageData["darkmode"] ? 'dark' : 'light' }}mode" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>

                        <a class="carousel-control-next" href="#image-gallery" role="button" data-slide="next">
                            <span class="carousel-control-next-icon carousel-{{ $pageData["darkmode"] ? 'dark' : 'light' }}mode" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>

                <hr class="mt-5 hr-{{ $pageData["darkmode"] ? 'dark' : 'light' }}mode">
            @endif

            <div class="row">
                @if ($hasBundle)
                    <div class="col-sm-4 offset-sm-4">
                        <bundle-toggle
                            enable="{{ $bundleEnabled }}"
                            id="{{ $bundle->id }}"
                        ></bundle-toggle>
                    </div>
                @else
                    <div class="col-sm-4 offset-sm-2">
                        <a href="#" type="button" class="big-button-primary">
                            Buy now - €{{ $bundle->price }}
                        </a>
                    </div>

                    <div class="col-sm-4">
                        <premium-bundle-toggle
                            premium="{{ $isPremium }}"
                            enable="{{ $hasBundlePremium }}"
                            id="{{ $bundle->id }}"
                        ></premium-bundle-toggle>
                    </div>
                @endif
            </div>

            <div class="description-long">
                {{ Markdown::parse($bundle->description) }}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/bundle-show.js') }}" defer></script>
@endsection
