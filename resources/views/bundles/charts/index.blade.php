@extends('layouts.width8')

@section('content')
    <div class="{{ $pageData["darkmode"] ? "dark-card" : "card" }}">
        <div class="card-header-flex">
            <div class="card-header-text">
                <i class="fas fa-chart-bar"></i>
                Charts
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <a role="button" href="/bundles/charts/presence" class="big-button-opposite btn-lg">
                        Edit chart presence
                    </a>
                </div>
            </div>

            <hr class="{{ $pageData["darkmode"] ? "hr-darkmode-dashed" : "hr-lightmode-dashed" }}">

            @foreach ($charts as $groupIndex => $group)
                <div class="row">
                    @foreach ($group as $chartIndex => $chart)
                        <div class="chart-gallery-wrapper {{ ($chartIndex == count($group) - 1 && count($group) % 2 == 1) ? 'offset-md-3' : '' }}">
                            <a href="/bundles/charts/{{ $chart["directory"] }}" class="text-decoration-none">
                                <div
                                    class="chart-gallery-block"
                                >
                                    <div class="chart-gallery-element">
                                        <div class="chart-title">
                                            {{ $chart["title"] }}
                                        </div>

                                        <div class="chart-description">
                                            {{ $chart["description"] }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                @if ($groupIndex <= count($charts) - 2)
                    <hr class="hr-{{ $pageData["darkmode"] ? 'dark' : 'light' }}mode-dashed">
                @endif
            @endforeach
        </div>
    </div>
@endsection
