@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header-flex">
                    <div class="card-header-text">{{ __('Summary') }}</div>
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
                                    <div class="card-header-text text-center font-weight-bold">
                                        {{ __('Sum') }}
                                    </div>
                                </div>

                                <div class="card-body text-center h5">125.00 EUR</div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive-xl w-100">
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th scope="col" class="h5 font-weight-bold">{{ __('Category') }}</th>
                                    <th scope="col" class="h5 font-weight-bold">{{ __('Balance') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <th scole="row">Bank Account</th>
                                    <td>1234.56 PLN</td>
                                </tr>
                                <tr>
                                    <th scole="row">Cash</th>
                                    <td>1234.56 PLN</td>
                                </tr>
                                <tr>
                                    <th scole="row">Savings</th>
                                    <td>1234.56 PLN</td>
                                </tr>
                                <tr>
                                    <th scole="row">PayPal</th>
                                    <td>1234.56 PLN</td>
                                </tr>
                                <tr>
                                    <th scole="row">Steam</th>
                                    <td>1234.56 PLN</td>
                                </tr>
                                <tr>
                                    <th scole="row">Refunds</th>
                                    <td>1234.56 PLN</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
