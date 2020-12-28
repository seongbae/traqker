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
        <a class="nav-link {{ $page == '_wiki' ? 'active' : '' }}" href="{{ route('wikipages.index', ['type'=>'teams', 'slug'=>$team->slug]) }}">Wiki</a>
    </li>
    @can('update', $team)
    <li class="nav-item">
        <a class="nav-link {{ $page == '_settings' ? 'active' : '' }}" href="{{ route('teams.settings', ['team'=>$team]) }}">Settings</a>
    </li>
    @endcan
</ul>
