@extends('canvas::admin.layouts.app')

@section('title', __('Task'))
@section('content')

    <div class="container" id="task-detail">
        <div class="card">
            <div class="card-header">
                <div class="float-left">Task: {{ $task->name }}</div>
                <div class="float-right text-muted small">
                    Created by {{ $task->owner->name }}
                    @if ($task->project_id)
                        in <a href="{{route('projects.show', ['project'=>$task->project])}}">{{$task->project->name}}</a>
                    @endif

                    @include('tasks.menus')
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="row">
                                <div class="col-md-3 text-secondary">
                                    Description
                                </div>
                                <div class="col-md">
                                   {!! nl2br($task->description) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="row">
                                <div class="col-md-3 text-secondary">
                                    Priority
                                </div>
                                <div class="col-md">
                                    {{ ucfirst($task->priority)}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="row">
                                <div class="col-md-3 text-secondary">
                                    Status
                                </div>
                                <div class="col-md">
                                    <div class="btn-group">
                                        <button id="task-status" type="button" class="btn btn-info dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ $task->status }}
                                        </button>
                                        <div class="dropdown-menu status-list">
                                            @foreach(config('traqker.statuses') as $status)
                                            <a class="dropdown-item" href="#">{{$status}}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="row">
                                <div class="col-md-3 text-secondary">
                                    Assigned to
                                </div>
                                <div class="col-md">
                                   @if (count($task->users) > 0)
                                        @include('partials.task_assigned',['users'=>$task->users])
                                   @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="row">
                                <div class="col-md-3 text-secondary">
                                    Attachments
                                </div>
                                <div class="col-md" >
                                    <div id="attachments">

                                    </div>
                                    <div id="dropzone">
                                        <form class="dropzone needsclick" id="demo-upload" action="/attachments" enctype="multipart/form-data">
                                            @csrf
                                            <div class="dz-message needsclick">
                                                <span class="note needsclick">Drop files here</span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="row">
                                <div class="col-md-2 text-secondary">
                                    Start on
                                </div>
                                <div class="col-md">
                                   {{ $task->start_on }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="row">
                                <div class="col-md-2 text-secondary">
                                    Due on
                                </div>
                                <div class="col-md">
                                    @if ($task->due_on)
                                    {{ \Carbon\Carbon::parse($task->due_on_day_end)->format('Y-m-d') }} ({{ \Carbon\Carbon::parse($task->due_on_day_end)->diffForHumans() }})
                                   @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($task->completed_on)
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="row">
                                <div class="col-md-2 text-secondary">
                                    Completed on
                                </div>
                                <div class="col-md">
                                   {{ $task->completed_on }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="row">
                                <div class="col-md-2 text-secondary">
                                    Estimate
                                </div>
                                <div class="col-md">
                                    @if ($task->estimate_hour)
                                        {{ $task->estimate_hour }} hours
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (count($task->hours) > 0)
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="row">
                                <div class="col-md-2 text-secondary">
                                    Time Spent
                                </div>
                                <div class="col-md">
                                   @foreach($task->hours as $hour)
                                     {{ $hour->hours }} hours on {{ \Carbon\Carbon::parse($hour->worked_on)->format('m/d/Y')}} ({{$hour->user->name}})
                                     @if (Auth::id()==$hour->user_id)
                                     <a href="/hour/delete/{{$hour->id}}" onclick="return confirm('Are you sure?')"><i class="fas fa-minus-circle"></i></a>
                                     @endif
                                     <br>
                                   @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @comments(['model' => $task])
            </div>
        </div>
    </div>
<!-- Modal -->
<div class="modal fade" id="addTimeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="POST" action="/hours">
            @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Time</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>"/>
            <input type="number" name="hours" focused /> hours
            @if ($task->status != 'complete')
            <div class="custom-control custom-switch pt-2">
                <input type="checkbox" class="custom-control-input" id="mark_as_complete" name="mark_as_complete" value="1">
                <label class="custom-control-label" for="mark_as_complete">Mark as complete</label>
            </div>
            @endif
          </div>
          <div class="modal-footer">
            <input type="hidden" name="task_id" value="{{$task->id}}"/>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
    </div>
  </div>
</div>
<div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="POST" action="/tasks/{{$task->id}}/status">
            @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Update Status</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-sm-6">
                    <select name="status" class="form-control">
                        <option value="created" {{ ($task->status == 'created') ? 'selected':''}}>Created</option>
                        <option value="active" {{ ($task->status == 'active') ? 'selected':''}}>Active</option>
                        <option value="complete" {{ ($task->status == 'complete') ? 'selected':''}}>Complete</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <input type="number" name="hours" placeholder="Hour (optional)" class="form-control"/>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="task_id" value="{{$task->id}}"/>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
    </div>
  </div>
</div>
<div class="modal fade" id="availabilityModal" tabindex="-1" role="dialog" aria-labelledby="availabilityModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div id="dp"></div>
    </div>
  </div>
</div>
@endsection

@push('scripts')

<script src="/js/daypilot-all.min.js?v=2018.2.232" type="text/javascript"></script>
<script  src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/dropzone.js"></script>

<script type="text/javascript">

    Dropzone.autoDiscover = false;
    var uploadedDocumentMap = {}

    var myDropzone = new Dropzone("#dropzone",{
        maxFilesize: 10,  // 3 mb
        acceptedFiles: ".jpeg,.jpg,.png,.pdf",
        url: "/attachments",
        data: {
            task_id: {{$task->id}}
        },
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        success: function (file, response) {
            file.previewElement.innerHTML = "";
            $('#attachments').append('<div class="attachment"><a href="/download/'+response.id+'">'+ response.label+'</a> ' +
                '<form action="/attachments/'+response.id+'" method="POST" style="display:inline;">' +
                '<input type="hidden" name="_token" value="'+$('meta[name="csrf-token"]').attr('content')+'">' +
                '<input type="hidden" name="_method" value="DELETE">' +
                '<button type="submit" class="btn btn-outline text-muted" onclick="return confirm(\'Are you sure?\');"><i class="far fa-trash-alt fa-xs"></i></button>' +
                '</form></div>')
            uploadedDocumentMap[file.name] = response.name;
        },
        removedfile: function (file) {
            file.previewElement.remove()
            var name = ''
            if (typeof file.file_name !== 'undefined') {
                name = file.file_name
            } else {
                name = uploadedDocumentMap[file.name]
            }
            $('form').find('input[name="document[]"][value="' + name + '"]').remove()
        },
        error: function () {
            console.log('error');
        },
        init: function () {
            @if(isset($task) && $task->attachments)
            var files =
            {!! json_encode($task->attachments) !!}
                for (var i in files) {
                var file = files[i]
                //this.options.addedfile.call(this, file)
                //file.previewElement.classList.add('dz-complete')
                //$('form').append('<input type="hidden" name="document[]" value="' + file.file_name + '">')
                $('#attachments').append('<div class="attachment"><a href="/download/'+file.id+'">'+ file.label+'</a> ' +
                    '<form action="/attachments/'+file.id+'" method="POST" style="display:inline;">' +
                        '<input type="hidden" name="_token" value="'+$('meta[name="csrf-token"]').attr('content')+'">' +
                        '<input type="hidden" name="_method" value="DELETE">' +
                        '<button type="submit" class="btn btn-outline text-muted" onclick="return confirm(\'Are you sure?\');"><i class="far fa-trash-alt fa-xs"></i></button>' +
                    '</form></div>')
            }
            @endif
        }
    });

    myDropzone.on("sending", function(file, xhr, formData) {
        formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
        formData.append("task_id", {{$task->id}});
    });

    $(document).ready(function(){

        $('.status-list a').on('click', function() {
            $('#task-status').html($(this).html());

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    status: $(this).html()
                },
                type: 'PUT',
                url: '/tasks/{{$task->id}}/status'
            });
        });

        $(document).on("click", ".availabilityCalendar", function () {
            var dp = new DayPilot.Calendar("dp");

            // view
            dp.startDate = "2020-05-03";
            dp.viewType = "Week";
            dp.heightSpec = "Full";
            dp.headerDateFormat = "dddd";
            dp.init();

             var id = $(this).data('id');

          $.ajax({
             url: '/user/'+id+'/availability/',
             type: 'get',
             dataType: 'json',
             success: function(response){

                var dt = "2020-05-03T";

                if(response['data'] != null){
                   len = response['data'].length;

                   if(len > 0){
                     for(var i=0; i<len; i++){
                      //console.log(response['data'][i]);

                      switch(response['data'][i].dayofweek) {
                        case 0:
                          // code
                          break;
                        case 1:
                          dt = "2020-05-04T";
                          break;
                        case 2:
                          dt = "2020-05-05T";
                          break;
                        case 3:
                          dt = "2020-05-06T";
                          break;
                        case 4:
                          dt = "2020-05-07T";
                          break;
                        case 5:
                          dt = "2020-05-08T";
                          break;
                        case 6:
                          dt = "2020-05-09T";
                          break;
                        default:
                          // code block
                      }

                      var e = new DayPilot.Event({
                          start: new DayPilot.Date(dt+response['data'][i].start),
                          end: new DayPilot.Date(dt+response['data'][i].end),
                          id: response['data'][i].id,
                          text: response['data'][i].name,

                      });
                      dp.events.add(e);
                    }
                  }
                }

                dt = "2020-05-03T";
                var today = new Date();
                //alert(today.dayofweek);
                switch(today.getDay()) {
                    case 0:
                      // code
                      break;
                    case 1:
                      dt = "2020-05-04T";
                      break;
                    case 2:
                      dt = "2020-05-05T";
                      break;
                    case 3:
                      dt = "2020-05-06T";
                      break;
                    case 4:
                      dt = "2020-05-07T";
                      break;
                    case 5:
                      dt = "2020-05-08T";
                      break;
                    case 6:
                      dt = "2020-05-09T";
                      break;
                    default:
                      // code block
                }

                var now = dt+addZeroBefore(today.getHours()) + ":" + addZeroBefore(today.getMinutes())+ ":" + addZeroBefore(today.getSeconds());
                var e = new DayPilot.Event({
                      start: new DayPilot.Date(now),
                      end: new DayPilot.Date(now).addMinutes(10),
                      id: "",
                      text: "Now",
                      barColor: "red"
                  });

                //alert(today.getDay());
                dp.events.add(e);
            }
          });
          $('#availabilityModal').modal('show');
        });

    });

    function addZeroBefore(n) {
      return (n < 10 ? '0' : '') + n;
    }



</script>
@endpush
