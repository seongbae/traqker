@extends('canvas::admin.layouts.app')

@section('title', __('Time Report'))
@section('content')
<div class="row my-2">
    <div class="col-md">
        <h1>@yield('title')</h1>
    </div>
{{--    <div class="col-md-auto mb-3 mb-md-0">--}}
{{--        <a href="{{ route('hours.create') }}" class="btn btn-primary">{{ __('Create Hour') }}</a>--}}
{{--    </div>--}}
</div>
<div class="card">
    <div class="card-body">
        <form method="GET" action="/hours" id="form">
            <div class="row">
{{--            <div class="col-lg-3">--}}
{{--                Team--}}
{{--                <select name="status" class="form-control" multiple>--}}
{{--                    <option value="created">Created</option>--}}
{{--                    <option value="active">Active</option>--}}
{{--                    <option value="complete">Complete</option>--}}
{{--                </select>--}}
{{--            </div>--}}
            <div class="col-lg-3">
                Projects
                <select name="projects[]" class="form-control" multiple>
                    @foreach(Auth::user()->projects()->orderBy('name','asc')->get() as $project)
                <option value="{{$project->id}}">{{$project->name}}</option>
                    @endforeach
                </select>
            </div>
                <div class="col-lg-3">
                    Date Range
                    <input type="text" class="form-control mb-3" id="reportrange" name="reportrange" value="" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" />
                    <button type="submit" id="submit" class="btn btn-primary">Run Query</button>
                </div>
            </div>
        </form>
    </div>
</div>
@foreach($userHours as $userHour)
    <div class="card">
        <div class="card-header">
            <h1>{{$userHour['user']->name}}</h1>
        </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table m-0">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time Spent</th>
                            <th>Description</th>
                            <th>Project</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($userHour['hours'] as $hour)
                            <tr>
                                <td>{{$hour->worked_on}}</td>
                                <td>{{$hour->hours}}</td>
                                <td>{{$hour->description}}</td>
                                <td>{{$hour->project->name}}</td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="card-footer text-muted d-flex">
                Total Time Spent: {{$userHour['total_hours']}}

                <div class="ml-auto text-right">


                </div>

            </div>
    </div>
@endforeach
@endsection

@push('scripts')
<script type="text/javascript">
    $(function() {

        // $('#reportrange').data('daterangepicker').setStartDate('03/01/2014');
        // $('#reportrange').data('daterangepicker').setEndDate('03/31/2014');

        var start = '{{$start}}'; //moment().subtract(29, 'days');
        var end ='{{$end}}'; //moment();

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        // $('#submit').click(function(){
        //     console.log(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
        //     var dateRange = $('#reportrange').val();
        //     console.log(dateRange);
        // });

        // var form = document.getElementById('form');
        // form.addEventListener('submit', function(event) {
        //   event.preventDefault();
        // });

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });

        //cb(start, end);

    });
</script>
@endpush

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
