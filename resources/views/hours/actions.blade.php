<div class="text-nowrap text-md-right">
    <a href="{{ route('hours.show', $hour->id) }}" class="btn {{ !request()->ajax() ? 'btn-primary' : 'btn-link text-secondary p-1' }}" title="{{ __('Show') }}">
        <i class="far fa-eye {{ !request()->ajax() ? 'fa-fw' : '' }}"></i>
    </a>

    <a href="{{ route('hours.edit', $hour->id) }}" class="btn {{ !request()->ajax() ? 'btn-primary' : 'btn-link text-secondary p-1' }}" title="{{ __('Edit') }}">
        <i class="far fa-edit {{ !request()->ajax() ? 'fa-fw' : '' }}"></i>
    </a>

    <a href="{{ route('hours.destroy', $hour->id) }}" class="btn {{ !request()->ajax() ? 'btn-primary' : 'btn-link text-secondary p-1' }}" title="{{ __('Delete') }}"
       onclick="event.preventDefault(); if (confirm('{{ __('Delete This Hour?') }}')) $('#delete_hour_{{ $hour->id }}_form').submit();">
        <i class="far fa-trash-alt {{ !request()->ajax() ? 'fa-fw' : '' }}"></i>
    </a>

    <form method="post" action="{{ route('hours.destroy', $hour->id) }}" id="delete_hour_{{ $hour->id }}_form" class="d-none">
        @csrf
        @method('delete')
    </form>
</div>
