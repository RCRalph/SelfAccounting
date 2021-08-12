@extends('layouts.width8')

@section('content')
    <div class="card">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-box-open"></i>
                {{ $bundle->title }}
            </div>
        </div>

        <div class="card-body">
            @if (count($gallery))
                <div class="glide">
                    <div class="glide__track" data-glide-el="track">
                        <ul class="glide__slides">
                            @foreach ($gallery as $index => $image)
                                <li class="glide__slide my-auto">
                                    <img
                                        class="bundle-image"
                                        src="{{ $image }}"
                                        style="object-fit: cover;"
                                        alt="Slide {{ $index + 1 }}"
                                    >
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="glide__arrows" data-glide-el="controls">
                        <button class="glide__arrow glide__arrow--left" data-glide-dir="<"><</button>
                        <button class="glide__arrow glide__arrow--right" data-glide-dir=">">></button>
                    </div>

                    <div class="glide__bullets" data-glide-el="controls[nav]">
                        @foreach ($gallery as $index => $image)
                            <button class="glide__bullet" data-glide-dir="={{ $index }}"></button>
                        @endforeach
                    </div>
                </div>

                <hr class="hr">
            @endif

            <div class="row">
                @if ($hasBundle)
                    <div class="col-md-4 offset-md-4">
                        <bundle-toggle
                            enable="{{ $bundleEnabled }}"
                            id="{{ $bundle->id }}"
                            directory="{{ $pageData["bundle_info"][$bundle->code]["directory"] }}"
                        ></bundle-toggle>
                    </div>
                @else
                    <div class="col-md-4 offset-md-2 mb-md-0 mb-2">
                        <a href="/payment?bundle={{ $bundle->id }}" type="button" class="big-button-primary">
                            Buy now - €{{ $bundle->price }}
                        </a>
                    </div>

                    <div class="col-md-4">
                        <premium-bundle-toggle
                            premium="{{ $isPremium }}"
                            enable="{{ $hasBundlePremium }}"
                            id="{{ $bundle->id }}"
                            directory="{{ $pageData["bundle_info"][$bundle->code]["directory"] }}"
                        ></premium-bundle-toggle>
                    </div>
                @endif
            </div>

            <div class="description-long">
                {!! Markdown::parse($bundle->description) !!}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ mix('js/bundle-show.js') }}" defer></script>
@endsection
