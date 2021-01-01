<div class="text-nowrap text-md-right">
    <a href="{{ route('teams.edit', ['team'=>$team]) }}" class="btn {{ !request()->ajax() ? 'btn-primary' : 'btn-link text-secondary p-1' }}" title="{{ __('Edit') }}">
        <i class="far fa-edit {{ !request()->ajax() ? 'fa-fw' : '' }}"></i>
    </a>

    <a href="{{ route('teams.destroy', ['team'=>$team]) }}" class="btn {{ !request()->ajax() ? 'btn-primary' : 'btn-link text-secondary p-1' }}" title="{{ __('Delete') }}"
       onclick="event.preventDefault(); if (confirm('{{ __('Delete This Team?') }}')) $('#delete_team_{{ $team->id }}_form').submit();">
        <i class="far fa-trash-alt {{ !request()->ajax() ? 'fa-fw' : '' }}"></i>
    </a>

    <form method="post" action="{{ route('teams.destroy', $team->id) }}" id="delete_team_{{ $team->id }}_form" class="d-none">
        @csrf
        @method('delete')
    </form>
</div>
