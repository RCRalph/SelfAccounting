@extends('layouts.fullwidth')

@section('content')
<div class="card">
    <div class="card-header-flex">
        <div class="card-header-text">
            <i class="fas fa-file-invoice-dollar"></i>
            {{ __('Summary') }}
        </div>
        <div class="d-flex">
            <div class="h4 my-auto mr-3">{{ __('Currency:') }}</div>
            <select class="form-control">
                <option>PLN</option>
                <option>EUR</option>
            </select>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="mx-auto mb-3 col-md-12 col-lg-6 offset-lg-3">
                <div class="card">
                    <div class="card-header">
                        <div class="m-auto text-center font-weight-bold h2">
                            {{ __('Sum') }}
                        </div>
                    </div>

                    <div class="card-body text-center h2 my-auto">125.00 EUR</div>
                </div>
            </div>
        </div>

        <div class="table-responsive-xl w-100">
            <table class="responsive-table-hover table-lightmode">
                <thead>
                    <tr>
                        <th scope="col" class="h3 font-weight-bold">{{ __('Category') }}</th>
                        <th scope="col" class="h3 font-weight-bold">{{ __('Balance') }}</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <th scole="row" class="h5 my-auto font-weight-bold">Bank Account</th>
                        <td class="h5 my-auto">1234.56 PLN</td>
                    </tr>
                    <tr>
                        <th scole="row" class="h5 my-auto font-weight-bold">Cash</th>
                        <td class="h5 my-auto">1234.56 PLN</td>
                    </tr>
                    <tr>
                        <th scole="row" class="h5 my-auto font-weight-bold">Savings</th>
                        <td class="h5 my-auto">1234.56 PLN</td>
                    </tr>
                    <tr>
                        <th scole="row" class="h5 my-auto font-weight-bold">PayPal</th>
                        <td class="h5 my-auto">1234.56 PLN</td>
                    </tr>
                    <tr>
                        <th scole="row" class="h5 my-auto font-weight-bold">Steam</th>
                        <td class="h5 my-auto">1234.56 PLN</td>
                    </tr>
                    <tr>
                        <th scole="row" class="h5 my-auto font-weight-bold">Refunds</th>
                        <td class="h5 my-auto">1234.56 PLN</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
