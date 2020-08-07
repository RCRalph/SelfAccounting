@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header-flex">
                    <div class="card-header-text">{{ __('Settings') }}</div>
                </div>

                <div class="card-body">
                    <div class="card mb-3">
                        <div class="card-header h4 text-center">{{ __('Categories') }}</div>
                        <div class="card-body">
                            <div class="table-responsive-xl w-100">
                                <table class="responsive-table">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="h5 font-weight-bold" data-toggle="tooltip" data-placement="bottom" title="{{ __('The name of your category') }}">
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
                                            <th scope="col" class="h5 font-weight-bold" data-toggle="tooltip" data-placement="bottom" title="{{ __('Count from this date') }}">
                                                {{ __('Start date') }}
                                            </th>
                                            <th scope="col" class="h5 font-weight-bold" data-toggle="tooltip" data-placement="bottom" title="{{ __('Count to this date') }}">
                                                {{ __('End date') }}
                                            </th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="text" value="Refunds" class="form-text">
                                            </td>

                                            <td class="td-settings">
                                                <label class="switch m-0">
                                                    <input type="checkbox" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>

                                            <td class="td-settings">
                                                <label class="switch m-0">
                                                    <input type="checkbox" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>

                                            <td class="td-settings">
                                                <label class="switch m-0">
                                                    <input type="checkbox">
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>

                                            <td class="td-settings">
                                                <label class="switch m-0">
                                                    <input type="checkbox" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>

                                            <td class="td-settings">
                                                <input type="date" class="form-date" value="2020-07-01">
                                            </td>

                                            <td class="td-settings">
                                                <input type="date" class="form-date" value="2020-08-31">
                                            </td>

                                            <td class="td-settings times-delete">
                                                <i class="fas fa-times-circle" style="cursor: pointer;"></i>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <input type="text" value="Own" class="form-text">
                                            </td>

                                            <td class="td-settings">
                                                <label class="switch m-0">
                                                    <input type="checkbox" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>

                                            <td class="td-settings">
                                                <label class="switch m-0">
                                                    <input type="checkbox" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>

                                            <td class="td-settings">
                                                <label class="switch m-0">
                                                    <input type="checkbox" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>

                                            <td class="td-settings">
                                                <label class="switch m-0">
                                                    <input type="checkbox">
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>

                                            <td class="td-settings">
                                                <input type="date" class="form-date" disabled>
                                            </td>

                                            <td class="td-settings">
                                                <input type="date" class="form-date" disabled>
                                            </td>

                                            <td class="td-settings times-delete">
                                                <i class="fas fa-times-circle" style="cursor: pointer;"></i>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <input type="text" value="Presents" class="form-text">
                                            </td>

                                            <td class="td-settings">
                                                <label class="switch m-0">
                                                    <input type="checkbox" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>

                                            <td class="td-settings">
                                                <label class="switch m-0">
                                                    <input type="checkbox" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>

                                            <td class="td-settings">
                                                <label class="switch m-0">
                                                    <input type="checkbox" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>

                                            <td class="td-settings">
                                                <label class="switch m-0">
                                                    <input type="checkbox">
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>

                                            <td class="td-settings">
                                                <input type="date" class="form-date" disabled>
                                            </td>

                                            <td class="td-settings">
                                                <input type="date" class="form-date" disabled>
                                            </td>

                                            <td class="td-settings times-delete">
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

                    <div class="card">
                        <div class="card-header">Payment Method</div>
                        <div class="card-body">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
