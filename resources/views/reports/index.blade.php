@extends('canvas::admin.layouts.app')

@section('title', __('Process Pay'))
@section('content')
<div class="row my-2">
    <div class="col-md">
        <h1>@yield('title')</h1>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <form method="GET" action="/reports" id="form">
        <div class="row">
            <!-- <div class="col-lg-3">
                Task Status
                <select name="status" class="form-control" multiple>
                    <option value="created">Created</option>
                    <option value="active">Active</option>
                    <option value="complete">Complete</option>
                </select>
            </div>
            <div class="col-lg-3">
                Projects
                <select name="status" class="form-control" multiple>
                    @foreach(Auth::user()->myProjects as $project)
                    <option value="{{$project->id}}">{{$project->name}}</option>
                    @endforeach
                </select>
            </div> -->
            <div class="col-lg-3">
                <input type="text" class="form-control" id="reportrange" name="reportrange" value="" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" />
                
            </div>
            <div class="col-lg-3">
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
    <form action="/sendpayment" method="POST" role="form" class="w-100">
    @csrf
    <div class="card-body">
        <div class="table-responsive">
          <table class="table m-0">
            <thead>
            <tr>
              <th>Time Spent</th>
              <th>Name</th>
              <th class="text-right"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($userHour['tasks'] as $task)
            <tr>
                <td>{{$task->total_hours}}<input type="hidden" name="task_ids[]" value="{{$task->id}}"></td>
                <td>{{$task->name}}</td>
                <td class="text-right">{{$task->completed_on}}</td>
            </tr>
            @endforeach
            </tbody>
          </table>
        </div>
        
    </div>
    <div class="card-footer text-muted d-flex">
        Time Spent: {{$userHour['total_hours']}}
        Total Payout: ${{$userHour['total_pay']}}
        
        <div class="ml-auto text-right">
            
                <input type="hidden" value="{{$userHour['user']->id}}" name="payee_id">
                <input type="hidden" value="{{Auth::id()}}" name="payer_id">
                <input type="hidden" value="{{$userHour['total_pay']}}" name="amount">
                @if($userHour['total_pay']>0)
                <button action="submit" class="btn btn-primary" onclick="return confirm('Are you sure?');" {{ $userHour['user']->canReceivePaymentFrom(Auth::user()) ? '' : 'disabled'}}>Pay ${{$userHour['total_pay']}}</button>
                @endif
                
        </div>
        
  </div>
  </form>
</div>
@endforeach
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        //$('#daterange').daterangepicker();

        // $("#daterange").daterangepicker({
        //   forceUpdate: true,
        //   callback: function(startDate, endDate, period){
        //     var title = startDate.format('L') + ' – ' + endDate.format('L');
        //     $(this).val(title);
        //     alert(title);
        //   }
        // });
    });
</script>
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