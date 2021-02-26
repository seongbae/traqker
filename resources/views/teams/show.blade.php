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
                                <div class="col-md">

                                    <ul class="flex-container wrap mb-4">
                                        @foreach($team->members as $member)
                                            <li class="flex-item">
                                                <div class="user-card">
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
                                                            <img src="{{$member->photo}}" class="img-radius" alt="User-Profile-Image">
                                                        </div>
                                                        <div class="user-name-title my-2">
                                                        <h6>{{$member->name}}</h6>
                                                        <span class="text-muted text-sm">{{$member->pivot->title}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                        @endforeach
                                    </ul>
                                    <a href="#" id="addNewMember" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New Member</a>
                                </div>
                            </div>
                            @if (count($team->pendingInvitations) > 0)
                                <hr>
                                <div class="row ">
                                    <div class="col-md-2 text-secondary">
                                        Pending Invitation
                                    </div>
                                    <div class="col-md-10 text-secondary">

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
                                                    <td>{{$invitation->email}}</td>
                                                    <td>{{$invitation->title}}</td>
                                                    <td>{{$invitation->access}}</td>
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
                                    <img src="{{ $member->photo }}" alt="{{ $member->name }}" title="{{ $member->name }}" class="rounded-circle profile-small mr-1" >
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

@endpush
