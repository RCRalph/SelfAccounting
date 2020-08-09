@extends('layouts.app-card')

@section('card-content')
<div class="card-header-flex">
    <div class="card-header-text">
        <i class="fas fa-sign-in-alt"></i>
        {{ __('Income') }}
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
    <div class="row mb-3">
        <div class="col-md-4 col-6 offset-md-2">
            <button class="big-button-primary">
                {{ __('Add multiple incomes') }}
            </button>
        </div>
        <div class="col-md-4 col-6">
            <button class="big-button-primary">
                {{ __('Add single income') }}
            </button>
        </div>
    </div>

    <div class="table-responsive-xl w-100">
        <table class="responsive-table">
            <thead>
                <tr>
                    <th scope="col" class="h5 font-weight-bold">
                        {{ __('Date') }}
                    </th>
                    <th scope="col" class="h5 font-weight-bold">
                        {{ __('Title') }}
                    </th>
                    <th scope="col" class="h5 font-weight-bold">
                        {{ __('Amount') }}
                    </th>
                    <th scope="col" class="h5 font-weight-bold">
                        {{ __('Price') }}
                    </th>
                    <th scope="col" class="h5 font-weight-bold">
                        {{ __('Value') }}
                    </th>
                    <th scope="col" class="h5 font-weight-bold">
                        {{ __('Category') }}
                    </th>
                    <th scope="col" class="h5 font-weight-bold">
                        {{ __('Mean') }}
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <th scope="row" class="td-centered">2020-06-25</td>
                    <td class="td-centered">Some name 1</td>
                    <td class="td-centered">2</td>
                    <td class="td-centered">20.00 PLN</td>
                    <td class="td-centered">40.00 PLN</td>
                    <td class="td-centered">Own</td>
                    <td class="td-centered">Bank Account</td>
                </tr>
                <tr>
                    <th scope="row" class="td-centered">2020-06-25</td>
                    <td class="td-centered">Some name 1</td>
                    <td class="td-centered">2</td>
                    <td class="td-centered">20.00 PLN</td>
                    <td class="td-centered">40.00 PLN</td>
                    <td class="td-centered">Own</td>
                    <td class="td-centered">Bank Account</td>
                </tr>
                <tr>
                    <th scope="row" class="td-centered">2020-06-25</td>
                    <td class="td-centered">Some name 1</td>
                    <td class="td-centered">2</td>
                    <td class="td-centered">20.00 PLN</td>
                    <td class="td-centered">40.00 PLN</td>
                    <td class="td-centered">Own</td>
                    <td class="td-centered">Bank Account</td>
                </tr>

            </tbody>
        </table>
    </div>
</div>
@endsection
