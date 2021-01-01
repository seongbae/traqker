@extends('canvas::admin.layouts.app')

@section('title', __('Team'))
@section('content')

    @include('teams.menus')

<div class="row">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-body">
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



                                </div>
                                <a href="{{ route('projects.create')."?team=".$team->id}}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New Project</a>
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
                                        @if (Auth::user()->canManageTeam($team))
                                        <div class="d-flex justify-content-between m-2">
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
                                            <div>
                                                <span class="text-muted text-sm">{{$member->pivot->access}}</span>
                                            </div>
                                        </div>
                                        @endif
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
                            <div>
                            <a href="#" id="addNewMember" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New Member</a>
                            </div>
                        </div>
                        @if (count($team->pendingInvitations) > 0)
                            <div class="row ">
                                <div class="col-md-2 text-secondary">

                                </div>
                                <div class="col-md-10 text-secondary">
                                    Pending Invitation
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Title</th>
                                            <th>Access</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($team->pendingInvitations as $invitation)
                                            <tr>
                                                <td>{{$invitation->email}} (invited)</td>
                                                <td></td>
                                                <td class="text-center">

                                                </td>
                                                <td>
                                                    <form action="{{ route('invitation.remove', ['invitation'=>$invitation])}}" method="POST" class="d-inline"  onSubmit="if(!confirm('Are you sure?')){return false;}">
                                                        @csrf
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <button type="submit" name="submit" class="btn btn-link text-secondary p-1" >
                                                            <i class="far fa-trash-alt "></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card  no-gutters" id="team-info">
            <div class="card-header">
                <div class="float-left">
                    {{ $team->name }}
                </div>
                <div class="float-right">
                    <div class="dropdown ">
                        <button class="btn btn-outline btn-xs" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            @if ($team->quicklink)
                                <a class="dropdown-item" href="#" onclick="$('#quicklink_team_{{ $team->id }}_form').submit();">Remove Quicklink</a>

                                <form method="post" action="{{ route('quicklinks.destroy', ['quicklink'=>$team->quicklink]) }}" id="quicklink_team_{{ $team->id }}_form" class="d-none">
                                    @csrf
                                    @method('delete')
                                </form>
                            @else
                                <a class="dropdown-item" href="#" onclick="$('#quicklink_team_{{ $team->id }}_form').submit();">Add to Quicklink</a>

                                <form method="post" action="{{ route('quicklinks.store') }}" id="quicklink_team_{{ $team->id }}_form" class="d-none">
                                    @csrf
                                    <input type="hidden" name="linkable_id" value="{{$team->id}}">
                                    <input type="hidden" name="linkable_type" value="{{get_class($team)}}">
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body" style="padding:0;">

                <div class="list-group list-group-flush">
                    <div class="list-group-item">
                    </div>
                    <div class="list-group-item">
                        Created by {{ $team->user->name }} on {{ $team->created_at->format('Y-m-d') }}
                    </div>
                    <div class="list-group-item">
                        Members<br>
                        <div class="my-2">
                            @foreach($team->members as $member)
                                <img src="/storage/{{ $member->photo }}" alt="{{ $member->name }}" title="{{ $member->name }}" class="rounded-circle profile-small mr-1" >
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="updateMember" tabindex="-1" role="dialog" aria-labelledby="updateMemberModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="POST" action="{{route('teams.addMember', ['team'=>$team])}}">
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
                          <option value="member" selected>member</option>
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
          $('#updateMember #email').attr('readonly', true); // mark it as read only
          $('#updateMember #email').css('background-color' , '#DEDEDE'); // change the background color
          $('#updateMember #access').val($(this).data('access'));
          $('#updateMember #title').val($(this).data('title'));
          $('#modalLabel').text('Edit Member');
          $('#updateMember').modal('show');
        });

        $(document).on("click", "#addNewMember", function () {
            $('#updateMember #user_id').val("");
            $('#updateMember #email').val("");
            $('#updateMember #email').attr('readonly', false); // mark it as read only
            $('#updateMember #email').css('background-color' , '#FFFFFF'); // change the background color
            $('#updateMember #access').find('option[value="member"]').attr("selected",true);
            $('#updateMember #title').val("");
            $('#modalLabel').text('Add Member');
            $('#updateMember').modal('show');
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
