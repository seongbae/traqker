<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ $page == '_teams' ? 'active' : '' }}" href="{{ route('teams.show', ['team'=>$team]) }}">Team</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $page == '_availability' ? 'active' : '' }}" href="{{ route('teams.availability', ['team'=>$team]) }}">Availability</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $page == '_discuss' ? 'active' : '' }}" href="{{ route('discuss.index', ['slug'=>$team->slug]) }}">Discuss</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $page == '_settings' ? 'active' : '' }}" href="{{ route('teams.settings', ['team'=>$team]) }}">Settings</a>
    </li>
{{--    <li class="nav-item dropdown">--}}
{{--        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Action</a>--}}
{{--        <div class="dropdown-menu">--}}
{{--            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#updateMember">Add Member</a>--}}
{{--            <a class="dropdown-item" href="{{ route('projects.create')."?team=".$team->id}}">Add Project</a>--}}
{{--            <a class="dropdown-item" href="{{ route('teams.edit', ['team'=>$team]) }}">Edit</a>--}}
{{--            <a class="dropdown-item" href="#" onclick="event.preventDefault(); if (confirm('{{ __('Delete This Project?') }}')) $('#delete_team_{{ $team->id }}_form').submit();">Delete</a>--}}

{{--            <form method="post" action="{{ route('teams.destroy', ['team'=>$team]) }}" id="delete_team_{{ $team->id }}_form" class="d-none">--}}
{{--                @csrf--}}
{{--                @method('delete')--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </li>--}}
</ul>
