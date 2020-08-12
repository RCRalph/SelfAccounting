@extends('layouts.midwidth')

@section('content')
<div class="card">
    <div class="card-header-flex">
        <div class="card-header-text">
            <i class="fas fa-sign-{{ $viewType == 'income' ? 'in' : 'out' }}-alt"></i>
            {{ __('Edit ' . $viewType) }}
        </div>
    </div>

    <div class="card-body">
        <form method="POST" action="/{{ $viewType }}/create/one" enctype="multipart/form-data">
            @csrf

            <div class="form-group row">
                <label for="date" class="col-lg-3 col-form-label text-lg-right">{{ __('Date') }}</label>

                <div class="col-lg-7">
                    <input id="date" type="date" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ old('date') }}" autocomplete="date" autofocus>

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
                    <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" autocomplete="title">

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
                    <input id="amount" type="number" step="0.001" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount') }}" autocomplete="amount">

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
                    <input id="price" type="number" step="0.001" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" autocomplete="price">

                    @error('price')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-lg-3">
                    <select id="currency" class="form-control @error('currency') is-invalid @enderror" name="currency" value="{{ old('currency') }}" autocomplete="currency">
                        <option>PLN</option>
                        <option>EUR</option>
                    </select>

                    @error('currency')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="category" class="col-lg-3 col-form-label text-lg-right">{{ __('Category') }}</label>

                <div class="col-lg-7">
                    <select id="category" class="form-control @error('category') is-invalid @enderror" name="category" value="{{ old('category') }}" autocomplete="category">
                        <option>Own</option>
                        <option>Refunds</option>
                        <option>Other</option>
                    </select>

                    @error('category')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="mean" class="col-lg-3 col-form-label text-lg-right">{{ __('Mean of payment') }}</label>

                <div class="col-lg-7">
                    <select id="mean" class="form-control @error('mean') is-invalid @enderror" name="mean" value="{{ old('mean') }}" autocomplete="mean">
                        <option>Bank Account</option>
                        <option>Cash</option>
                        <option>Savings</option>
                    </select>

                    @error('mean')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </form>

        <hr>

        <div class="row">
            <div class="col-4">
                <button class="big-button-primary">
                    {{ __('Reset changes') }}
                </button>
            </div>

            <div class="col-4">
                <button class="big-button-success">
                    {{ __('Save changes') }}
                </button>
            </div>

            <div class="col-4">
                <button class="big-button-danger">
                    {{ __('Delete') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
