@extends('layouts.fullwidth')

@section('script')
<script src="{{ asset('js/income-outcome.js') }}" defer></script>
@endsection

@section('content')
<income-outcome-component type="{{ $viewType }}"></income-outcome-component>
<!--<div class="{{ $darkmode ? 'dark-card' : 'card'}}">
    <div class="card-body">
        <div class="table-responsive-xl w-100">
            <table id="table-multi-hover" class="responsive-table-bordered table-{{ $darkmode ? 'dark' : 'light'}}mode">                <tbody>
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
</div>-->
@endsection
