@extends('canvas::admin.layouts.app')

@section('title', __('Team'))
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    {{ $team->name }}
                    <div class="dropdown float-right  ml-2">
                        <button class="btn btn-outline btn-xs" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#updateMember">Add Member</a>
                            <a class="dropdown-item" href="{{ route('projects.create')."?team=".$team->id}}">Add Project</a>
                            <a class="dropdown-item" href="{{ route('teams.edit', $team->id) }}">Edit</a>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); if (confirm('{{ __('Delete This Project?') }}')) $('#delete_team_{{ $team->id }}_form').submit();">Delete</a>

                            <form method="post" action="{{ route('teams.destroy', $team->id) }}" id="delete_team_{{ $team->id }}_form" class="d-none">
                                @csrf
                                @method('delete')
                            </form>
                        </div>
                    </div>
                </div>

                <div class="float-right">

                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="team-overview-tab" data-toggle="tab" href="#team-overview" role="tab" aria-controls="team-overview" aria-selected="true">Team</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="availability-tab" data-toggle="tab" href="#availability" role="tab" aria-controls="availability" aria-selected="false">Availability</a>
                  </li>
{{--                  @if (Auth::id() == $team->user_id)--}}
{{--                  <li class="nav-item">--}}
{{--                    <a class="nav-link" id="members-tab" data-toggle="tab" href="#members" role="tab" aria-controls="members" aria-selected="false">Members</a>--}}
{{--                  </li>--}}
{{--                  @endif--}}
                </ul>
                <div class="tab-content mt-4" id="myTabContent">
                  <div class="tab-pane fade  show active" id="team-overview" role="tabpanel" aria-labelledby="team-overview-tab">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item py-3">
                                <div class="row">
                                    <div class="col-md-2 text-secondary">
                                        Projects:
                                    </div>
                                    <div class="col-md">
                                        <div class="row">
                                        @foreach($team->projects()->orderBy('name')->get() as $project)
                                            @include('partials.project', ['project'=>$project])
                                        @endforeach

                                            <div class="text-center my-auto mr-2">
                                                <div class="card card-block d-flex" style="height:120px;width:120px;">
                                                    <div class="card-body align-items-center d-flex justify-content-center">
                                                        <a href="{{ route('projects.create')."?team=".$team->id}}"><i class="fas fa-plus"></i> New</a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item py-3">
                                <div class="row team">
                                    <div class="col-md-2 text-secondary">
                                        Members
                                    </div>
                                    @foreach($team->members as $member)
                                    <div class="col-md-2">
                                        <div class="card user-card">
                                            <div class="float-right">
                                                <div class="dropdown">
                                                    <button class="btn btn-outline btn-sm text-muted" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-h"></i>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#updateMember" class="btn btn-link text-secondary p-1" title="Edit" id="editMember" data-id="{{$member->id}}" data-name="{{$member->name}}"  data-email="{{$member->email}}" data-access="{{$member->pivot->access}}" data-title="{{$member->pivot->title}}"  >
                                                            Edit
                                                        </a>
                                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); if (confirm('{{ __('Remove member?') }}')) $('#remove_member_{{ $member->id }}_form').submit();">Remove</a>

                                                        <form action="{{ route('team.remove', ['team'=>$team, 'user'=>$member])}}" id="remove_member_{{$member->id}}_form" method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="_method" value="DELETE">
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-block">
                                                <div class="user-image">
                                                    <img src="/storage/{{$member->photo}}" class="img-radius" alt="User-Profile-Image">
                                                </div>
                                                <h6 class="f-w-600 m-t-25 m-b-10">{{$member->name}}</h6>
                                                <p class="text-muted text-sm">{{$member->pivot->title}}</p>

                                            </div>
                                        </div>
                                      </div>
                                    @endforeach
                                </div>
                            </div>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="availability" role="tabpanel" aria-labelledby="availability-tab">
                    <p>My timezone: {{ Auth::user()->timezone }}</p>
                    <div id="dp"></div>
                  </div>
                  <div class="tab-pane fade" id="members" role="tabpanel" aria-labelledby="members-tab">
                    <table class="table">
                          <thead>
                            <tr>
                              <th></th>
                              <th>Name</th>
                              <th>Title</th>
                              <th>Access</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                          @foreach($team->members as $member)
                          <tr>
                            <td style="width:50px;"><img src="/storage/{{ $member->photo }}" class="profile-medium img-circle"></td>
                            <td>{{$member->name}}</td>
                            <td>{{$member->pivot->title }}</td>
                            <td>{{$member->pivot->access }}</td>
                            <td>
                                <a href="#"  data-toggle="modal" data-target="#updateMember" class="btn btn-link text-secondary p-1" title="Edit" id="editMember" data-id="{{$member->id}}" data-name="{{$member->name}}"  data-email="{{$member->email}}"  data-title="{{$member->pivot->title}}"  >
                                    <i class="far fa-edit "></i>
                                </a>
                                @if($member->id != $team->user_id)

                                @endif
                            </td>
                          </tr>
                          @endforeach
                          @foreach($team->invitations()->whereNull('registered_at')->get() as $invitation)
                          <tr>
                            <td style="width:50px;"></td>
                            <td>{{$invitation->email}} (invited)</td>
                            <td></td>
                            <td class="text-center">

                            </td>
                            <td>
                                <form action="{{ route('invitation.remove', ['invitation'=>$invitation])}}" method="POST" class="d-inline"  onSubmit="if(!confirm('Are you sure?')){return false;}">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" name="submit" class="btn btn-link text-secondary p-1" onClick="return confirm('Are you sure?')">
                                    <i class="far fa-trash-alt "></i></button>
                                </form>
                            </td>
                          </tr>
                          @endforeach
                        </tbody>
                        </table>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="updateMember" tabindex="-1" role="dialog" aria-labelledby="updateMemberModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="POST" action="/team/{{$team->id}}/add">
            @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Add Member</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="row mb-2">
                <div class="col-sm-12">
                        <input type="text" name="email" placeholder="E-mail" class="form-control" id="email" />
                </div>
              </div>
              <div class="row mb-2">
                <div class="col-sm-12">
                    <input type="text" name="title" placeholder="Title (optional)" class="form-control" id="title" />
                </div>
            </div>
              <div class="row ">
                  <div class="col-sm-12">
                      <select name="access" id="access" class="form-control">
                          <option value="owner">owner</option>
                          <option value="manager">manager</option>
                          <option value="member">member</option>
                      </select>
                      <span class="text-muted small">Access: Owner can manage members and projects. Manager can manage projects.</span>
                  </div>
              </div>

          </div>
          <div class="modal-footer">
            <input type="hidden" name="team_id" value="{{$team->id}}"/>
            <input type="hidden" name="user_id" value="" id="user_id" />
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="/js/daypilot-all.min.js?v=2018.2.232" type="text/javascript"></script>

<script>
    $(document).ready(() => {
      let url = location.href.replace(/\/$/, "");

      if (location.hash) {
        const hash = url.split("#");
        $('#myTab a[href="#'+hash[1]+'"]').tab("show");
        url = location.href.replace(/\/#/, "#");
        history.replaceState(null, null, url);
        setTimeout(() => {
          $(window).scrollTop(0);
        }, 400);
      }

      $('a[data-toggle="tab"]').on("click", function() {
        let newUrl;
        const hash = $(this).attr("href");
        if(hash == "#home") {
          newUrl = url.split("#")[0];
        } else {
          newUrl = url.split("#")[0] + hash;
        }
        newUrl += "/";
        history.replaceState(null, null, newUrl);
      });
    });

    $(document).ready(function(){
        $(document).on("click", "#editMember", function () {
          $('#updateMember #user_id').val($(this).data('id'));
          $('#updateMember #email').val($(this).data('email'));
          $('#updateMember #access').val($(this).data('access'));
          $('#updateMember #title').val($(this).data('title'));
          $('#modalLabel').text('Edit Member');
          $('#updateMember').modal('show');
        });

        $('#updateMember').on('hidden.bs.modal', function () {
            $('#modalLabel').text('Add Member');
        });
    });
</script>
<script type="text/javascript">

    var dp = new DayPilot.Calendar("dp");

    // view
    dp.startDate = "2020-05-03";
    dp.viewType = "Week";
    dp.heightSpec = "Full";
    dp.headerDateFormat = "dddd";
    dp.init();

    $.ajax({
         url: '/team/{{$team->id}}/availability/',
         type: 'get',
         dataType: 'json',
         success: function(response){

            if(response['data'] != null){
               var members = response['data'].length;

               if(members > 0){
                    var colorCodes = ['#1066a8','#a85210','#5210a8','#66a810','#10a878','#2c10a8','#a8101a','#a89e10'];
                    var colorCount = 0;
                     for(var i=0; i<members; i++) {

                        var availabilitySet = response['data'][i].availability;
                        //console.log(availabilitySet);

                        for(var j=0; j<availabilitySet.length; j++) {
                                //console.log(availabilitySet[j]);
                                var dt = "2020-05-03T";

                                switch(availabilitySet[j].dayofweek) {
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
                                        break;
                              }
                              // code block
                                var e = new DayPilot.Event({
                                    start: new DayPilot.Date(dt+availabilitySet[j].start),
                                    end: new DayPilot.Date(dt+availabilitySet[j].end),
                                    id: availabilitySet[j].id,
                                    text: response['data'][i].name,
                                    barColor: colorCodes[colorCount]
                                });
                                dp.events.add(e);
                          }
                          colorCount = colorCount+1;
                          if (colorCount>7)
                            colorCount = 0;
                        }
                  }
                }
              }
            });

</script>
@endpush
