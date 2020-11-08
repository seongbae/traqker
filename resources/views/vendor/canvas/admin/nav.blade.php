<nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          <li class="nav-item">
              <a class="nav-link {{ (request()->is('dashboard*')) ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt mr-2"></i> <p>{{ __('Dashboard') }}</p></a>
          </li>
          <li class="nav-item">
              <a class="nav-link {{ (request()->is('tasks*')) ? 'active' : '' }}" href="{{ route('tasks.index') }}"><i class="fas fa-tasks mr-2"></i> <p>{{ __('Tasks') }}</p></a>
          </li>
          <li class="nav-item">
              <a class="nav-link {{ (request()->is('projects*')) ? 'active' : '' }}" href="{{ route('projects.index') }}"><i class="fas fa-project-diagram mr-2"></i> <p>{{ __('Projects') }}</p></a>
          </li>
            <li class="nav-item has-treeview {{ (request()->is('teams/*')) ? 'menu-open' : '' }}">
                <a class="nav-link {{ (request()->is('teams*')) ? 'active' : '' }}" href="{{ route('teams.index') }}"><i class="fas fa-users mr-2"></i> <p>{{ __('Teams') }} <i class="right fas fa-angle-left"></i></p></a>

                <ul class="nav nav-treeview ml-2">
                    @foreach(Auth::user()->teams as $team)
                    <li class="nav-item">
                        <a href="{{route('teams.show', ['team'=>$team])}}" class="nav-link {{ (request()->is('teams/'.$team->id)) ? 'active' : '' }}">
                            <i class="far fa-smile nav-icon"></i>
                            <p>{{$team->name}}</p>
                        </a>
                    </li>
                    @endforeach
                    <li class="nav-item">
                        <a href="/teams/create" class="nav-link {{ (request()->is('teams/create')) ? 'active' : '' }}">
                            <i class="fas fa-plus nav-icon"></i>
                            <p>Add New</p>
                        </a>
                    </li>
                </ul>
            </li>
          <li class="nav-item">
              <a class="nav-link {{ (request()->is('hours*')) ? 'active' : '' }}" href="{{ route('hours.index') }}"><i class="fas fa-edit mr-2"></i> <p>{{ __('Time Report') }} </p></a>
          </li>

          <!-- insert menus above here-->

          @if ($moduleMenus)
            @foreach ($moduleMenus as $moduleMenu)
            @if (array_key_exists('group', $moduleMenu))
            <li class="nav-item has-treeview {{ (request()->is($moduleMenu['group']['url'])) ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ (request()->is($moduleMenu['group']['url'])) ? 'active' : '' }}">
                <i class="{{$moduleMenu['group']['icon']}} nav-icon"></i>
                <p>
                  {{$moduleMenu['index']['name']}}
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview ml-2">
                @if ($moduleMenu['index'])
                <li class="nav-item">
                  <a href="/{{$moduleMenu['index']['url']}}" class="nav-link {{ (request()->is($moduleMenu['index']['url'])) ? 'active' : '' }}">
                    <i class="{{$moduleMenu['index']['icon']}} nav-icon"></i>
                    <p>{{$moduleMenu['index']['name']}}</p>
                  </a>
                </li>
                @endif
                @if ($moduleMenu['new'])
                <li class="nav-item">
                  <a href="/{{$moduleMenu['new']['url']}}" class="nav-link {{ (request()->is($moduleMenu['new']['url'])) ? 'active' : '' }}">
                    <i class="fas fa-plus nav-icon"></i>
                    <p>{{$moduleMenu['new']['name']}}</p>
                  </a>
                </li>
                @endif
                @if (array_key_exists('related', $moduleMenu))
                <li class="nav-item">
                  <a href="/{{$moduleMenu['related']['url']}}" class="nav-link {{ (request()->is($moduleMenu['related']['url'])) ? 'active' : '' }}">
                    <i class="{{$moduleMenu['related']['icon']}} nav-icon"></i>
                    <p>{{$moduleMenu['related']['name']}}</p>
                  </a>
                </li>
                @endif
              </ul>
            </li>
            @else
            <li class="nav-item">
              <a href="/{{$moduleMenu['index']['url']}}" class="nav-link {{ (request()->is($moduleMenu['index']['url'])) ? 'active' : '' }}">
                <i class="{{$moduleMenu['index']['icon']}} nav-icon"></i>
                <p>{{$moduleMenu['index']['name']}}</p>
              </a>
            </li>
            @endif
            @endforeach
          @endif
            @if (Auth::user()->can('manage-users'))
          <li class="nav-item has-treeview {{ (request()->is('admin/users*')) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ (request()->is('admin/users*')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-friends"></i>
              <p>
                Users
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview ml-2">
              <li class="nav-item">
                <a href="/users" class="nav-link {{ (request()->is('users')) ? 'active' : '' }}">
                  <i class="fas fa-user-friends nav-icon"></i>
                  <p>Users</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/users/create" class="nav-link {{ (request()->is('users/create')) ? 'active' : '' }}">
                  <i class="fas fa-plus nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/users/roles" class="nav-link {{ (request()->is('users/roles')) ? 'active' : '' }}">
                  <i class="fas fa-user-tag nav-icon"></i>
                  <p>Roles</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview {{ (request()->is('admin/logs*')) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ (request()->is('admin/logs*')) ? 'active' : '' }}">
              <i class="nav-icon far fa-edit"></i>
              <p>
                Logs
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview ml-2">
              <li class="nav-item">
                <a href="/admin/logs/system" class="nav-link {{ (request()->is('admin/logs/system')) ? 'active' : '' }}">
                  <i class="far fa-edit nav-icon" ></i>
                  <p>System</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/logs/activity" class="nav-link {{ (request()->is('admin/logs/activity')) ? 'active' : '' }}">
                  <i class="far fa-edit nav-icon"></i>
                  <p>Activity</p>
                </a>
              </li>
            </ul>
          </li>
          @endif
          @if (Auth::user()->can('manage-site-settings'))
          <li class="nav-item">
            <a href="/settings" class="nav-link {{ (request()->is('settings')) ? 'active' : '' }}">
              <i class="nav-icon fas fa-cogs"></i>
              <p>Settings</p>
            </a>
          </li>
          @endif

        </ul>
      </nav>
