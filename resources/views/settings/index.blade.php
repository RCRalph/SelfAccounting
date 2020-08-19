@extends('layouts.fullwidth')

@section('script')
<script src="{{ asset('js/settings.js') }}" defer></script>
@endsection

@section('content')
<settings-component darkmode="{{ $darkmode }}"></settings-component>

<!--<div class="{{ $darkmode ? 'dark-card' : 'card'}}">
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" value="Refunds" class="form-text">
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <input type="date" class="form-date" value="2020-07-01">
                                </td>

                                <td>
                                    <input type="date" class="form-date" value="2020-08-31">
                                </td>

                                <td class="times-delete">
                                    <i class="fas fa-times-circle" style="cursor: pointer;"></i>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="text" value="Own" class="form-text">
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <input type="date" class="form-date" disabled>
                                </td>

                                <td>
                                    <input type="date" class="form-date" disabled>
                                </td>

                                <td class="times-delete">
                                    <i class="fas fa-times-circle" style="cursor: pointer;"></i>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="text" value="Presents" class="form-text">
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <input type="date" class="form-date" disabled>
                                </td>

                                <td>
                                    <input type="date" class="form-date" disabled>
                                </td>

                                <td class="times-delete">
                                    <i class="fas fa-times-circle" style="cursor: pointer;"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <hr>

                <div class="row">
                    <div class="col-4">
                        <button class="big-button-primary">
                            {{ __('New category') }}
                        </button>
                    </div>
                    <div class="col-4">
                        <button class="big-button-danger">
                            {{ __('Reset changes') }}
                        </button>
                    </div>

                    <div class="col-4">
                        <button class="big-button-success">
                            {{ __('Save changes') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="{{ $darkmode ? 'dark-card' : 'card'}}">
            <div class="card-header-flex">
                <div class="card-header-text">
                    <i class="fas fa-coins"></i>
                    {{ __('Means of Payment') }}
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive-xl w-100">
                    <table class="responsive-table-hover table-{{ $darkmode ? 'dark' : 'light'}}mode">
                        <thead>
                            <tr>
                                <th scope="col" class="h5 font-weight-bold" data-toggle="tooltip" data-placement="bottom" title="{{ __('The name of your mean of payment') }}">
                                    {{ __('Name') }}
                                </th>
                                <th scope="col" class="h5 font-weight-bold" data-toggle="tooltip" data-placement="bottom" title="{{ __('Use in income') }}">
                                    {{ __('Income') }}
                                </th>
                                <th scope="col" class="h5 font-weight-bold" data-toggle="tooltip" data-placement="bottom" title="{{ __('Use in outcome') }}">
                                    {{ __('Outcome') }}
                                </th>
                                <th scope="col" class="h5 font-weight-bold" data-toggle="tooltip" data-placement="bottom" title="{{ __('Display on charts') }}">
                                    {{ __('Charts') }}
                                </th>
                                <th scope="col" class="h5 font-weight-bold" data-toggle="tooltip" data-placement="bottom" title="{{ __('Count to summary') }}">
                                    {{ __('Summary') }}
                                </th>
                                <th scope="col" class="h5 font-weight-bold" data-toggle="tooltip" data-placement="bottom" title="{{ __('Date from which to add the starting balance') }}">
                                    {{ __('First entry') }}
                                </th>

                                <th scope="col" class="h5 font-weight-bold" data-toggle="tooltip" data-placement="bottom" title="{{ __('Amount which to use for the first entry') }}">
                                    {{ __('Starting balance') }}
                                </th>

                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" value="Bank Account" class="form-text">
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <input type="date" class="form-date" value="2019-06-17">
                                </td>

                                <td>
                                    <input type="number" class="form-number" value="123.54" step=".01">
                                </td>

                                <td class="times-delete">
                                    <i class="fas fa-times-circle" style="cursor: pointer;"></i>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="text" value="Cash" class="form-text">
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <input type="date" class="form-date" value="2019-06-17">
                                </td>

                                <td>
                                    <input type="number" class="form-number" value="665.30" step=".01">
                                </td>

                                <td class="times-delete">
                                    <i class="fas fa-times-circle" style="cursor: pointer;"></i>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="text" value="Savings" class="form-text">
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <label class="switch m-0">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </td>

                                <td>
                                    <input type="date" class="form-date" value="2019-06-17">
                                </td>

                                <td>
                                    <input type="number" class="form-number" value="5000.00" step=".01">
                                </td>

                                <td class="times-delete">
                                    <i class="fas fa-times-circle" style="cursor: pointer;"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <hr>

                <div class="row">
                    <div class="col-4">
                        <button class="big-button-primary">
                            {{ __('New method') }}
                        </button>
                    </div>
                    <div class="col-4">
                        <button class="big-button-danger">
                            {{ __('Reset changes') }}
                        </button>
                    </div>

                    <div class="col-4">
                        <button class="big-button-success">
                            {{ __('Save changes') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>-->
@endsection
