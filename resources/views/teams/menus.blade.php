<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ $page == '_list' ? 'active' : '' }}" href="{{ route('projects.show', ['project'=>$project]) }}">List</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $page == '_board' ? 'active' : '' }}" href="{{ route('projects.show', ['project'=>$project]) }}/board">Board</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $page == '_timeline' ? 'active' : '' }}" href="{{ route('projects.show', ['project'=>$project]) }}/timeline">Timeline</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $page == '_calendar' ? 'active' : '' }}" href="{{ route('projects.show', ['project'=>$project]) }}/calendar">Calendar</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $page == '_files' ? 'active' : '' }}" href="{{ route('projects.show', ['project'=>$project]) }}/files">Files</a>
    </li>
</ul>
