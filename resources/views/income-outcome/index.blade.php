@extends('layouts.fullwidth')

@section('content')
<div class="card">
    <div class="card-header-flex">
        <div class="card-header-text">
            <i class="fas fa-sign-{{ $viewType == 'income' ? 'in' : 'out' }}-alt"></i>
            {{ __(ucfirst($viewType)) }}
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
                    {{ __('Add multiple ' . $viewType . 's') }}
                </button>
            </div>
            <div class="col-md-4 col-6">
                <a type="button" href="/income/create/one" class="big-button-primary">
                    {{ __('Add single ' . $viewType) }}
                </a>
            </div>
        </div>

        <div class="table-responsive-xl w-100">
            <table class="responsive-table-bordered table-lightmode" id="table-multi-hover">
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
                        <th scope="col" class="h5 font-weight-bold">
                            {{ __('Edit') }}
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr i="0">
                        <th scope="row" rowspan="2" rep="date">2020-06-25</th>
                        <td rowspan="2" rep="title">Some name 1</td>
                        <td rep="amount">2</td>
                        <td rep="price">30.00 PLN</td>
                        <td rep="value">60.00 PLN</td>
                        <td rowspan="2" rep="category">Own</td>
                        <td rep="mean">Cash</td>
                        <td class="py-0 h4" onclick="window.document.location='/{{ $viewType }}/1'" rep="edit">
							<i class="fas fa-edit"></i>
						</td>
                    </tr>
                    <tr i="1">
                        <td rep="amount">1</td>
                        <td rep="price">20.00 PLN</td>
                        <td rep="value">20.00 PLN</td>
                        <td rep="mean">Bank Account</td>
                        <td class="py-0 h4" onclick="window.document.location='/{{ $viewType }}/1'" rep="edit">
							<i class="fas fa-edit"></i>
						</td>
                    </tr>
                    <tr i="2">
                        <th scope="row" rep="date">2020-06-24</th>
                        <td rep="title">Some name 2</td>
                        <td rep="amount">2</td>
                        <td rep="price">20.00 PLN</td>
                        <td rep="value">40.00 PLN</td>
                        <td rep="category">Own</td>
                        <td rep="mean">PayPal</td>
                        <td class="py-0 h4" onclick="window.document.location='/{{ $viewType }}/1'" rep="edit">
							<i class="fas fa-edit"></i>
						</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
