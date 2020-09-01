@extends('layouts.midwidth')

@section('script')
<script src="{{ asset('js/income-outcome-create-one.js') }}" defer></script>

<script>
const categoriesAndMeans = {
    categories: {
        @foreach ($categories as $currency => $categoryList)
            {{ $currency }}: {
                @foreach ($categoryList as $category)
                    {{ $category["id"] }}: "{{ $category['name'] }}",
                @endforeach
            },
        @endforeach
    },
    means: {
        @foreach ($means as $currency => $meanList)
            {{ $currency }}: {
                @foreach ($meanList as $mean)
                    {{ $mean["id"] }}: "{{ $mean['name'] }}",
                @endforeach
            },
        @endforeach
    },
}
</script>
@endsection

@section('content')
<div class="{{ $darkmode ? 'dark-card' : 'card'}}">
    <div class="card-header-flex">
        <div class="card-header-text">
            <i class="fas fa-sign-{{ $viewType == 'income' ? 'in' : 'out' }}-alt"></i>
            {{ __('Add single ' . $viewType) }}
        </div>
    </div>

    <div class="card-body">
        <form method="POST" action="/{{ $viewType }}/store/one" enctype="multipart/form-data">
            @csrf

            <div class="form-group row">
                <label for="date" class="col-lg-3 col-form-label text-lg-right">{{ __('Date') }}</label>

                <div class="col-lg-7">
                    <input id="date" type="date" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ old('date') }}" autofocus>

                    @error('date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="title" class="col-lg-3 col-form-label text-lg-right">{{ __('Title') }}</label>

                <div class="col-lg-7">
                    <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" autocomplete="off" placeholder="Your title here" list="titleList">
                    <datalist id="titleList">
                        @foreach($titles as $title)
                            <option>{{ $title }}</option>
                        @endforeach
                    </datalist>

                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="amount" class="col-lg-3 col-form-label text-lg-right">{{ __('Amount') }}</label>

                <div class="col-lg-7">
                    <input id="amount" type="number" step="0.001" class="form-control @error('amount') is-invalid @enderror value-counting" name="amount" value="{{ old('amount') ?? 1 }}" placeholder="Your amount here">

                    @error('amount')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="price" class="col-lg-3 col-form-label text-lg-right">{{ __('Price') }}</label>

                <div class="col-lg-4">
                    <input id="price" type="number" step="0.001" class="form-control @error('price') is-invalid @enderror value-counting" name="price" value="{{ old('price') }}" placeholder="Your price here">

                    @error('price')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-lg-3">
                    <select id="currency_id" class="form-control @error('currency_id') is-invalid @enderror" name="currency_id" value="{{ old('currency_id') }}">
                        @foreach($currencies as $currency)
                            <option value="{{ $currency->id }}" {{ (old('currency_id') ?? $lastCurrency) == $currency->id ? "selected" : "" }}>{{ $currency->ISO }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="value" class="col-lg-3 col-form-label text-lg-right">{{ __('Value') }}</label>

                <div class="col-lg-7">
                    <input id="value" type="number" class="form-control" disabled value="0">
                </div>
            </div>

            <div class="form-group row">
                <label for="category_id" class="col-lg-3 col-form-label text-lg-right">{{ __('Category') }}</label>

                <div class="col-lg-7">
                    <select id="category_id" class="form-control @error('category_id') is-invalid @enderror" name="category_id">
                        @foreach ($categories[$lastCurrency] ?? [] as $category)
                            <option value="{{ $category['id'] }}" {{ (old('category_id') ?? $lastCategory) == $category['id'] ? "selected" : "" }}>{{ $category['name'] }}</option>
                        @endforeach
                        <option value="null">N / A</option>
                    </select>

                    @error('category_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="mean_id" class="col-lg-3 col-form-label text-lg-right">{{ __('Mean of payment') }}</label>

                <div class="col-lg-7">
                    <select id="mean_id" class="form-control @error('mean_id') is-invalid @enderror" name="mean_id">
                        @foreach ($means[$lastCurrency] ?? [] as $mean)
                            <option value="{{ $mean['id'] }}" {{ (old('mean_id') ?? $lastMean) == $mean['id'] ? "selected" : "" }}>{{ $mean['name'] }}</option>
                        @endforeach
                        <option value="null">N / A</option>
                    </select>

                    @error('mean_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-lg-7 offset-lg-3">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Submit') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
