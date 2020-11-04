@extends('canvas::admin.layouts.app')

@section('content')

   {!! Form::model($page, [
            'method' => 'POST',
            'route' => ['admin.pages.update', $page->id],
            'class'=>'form-horizontal'
            ]) !!}
     @method('PUT')
    @include('canvas::admin.pages._partials.form', ['pageTitle'=>'Edit Page', 'submitButtonText' => __('Save'), 'formsize'=>6])

    {!! Form::close() !!}

    @if (isset($page))
    <form action="/admin/pages/{{ $page->id }}" method="POST" id="deletePageForm" style="display:inline;">
        {{ csrf_field() }}
        @method('DELETE')
       
    </form>
    @endif

@stop

@push('scripts')

<script>
	function deletePage()
	{
		if(confirm("Are you sure?"))
			document.getElementById("deletePageForm").submit();
		else
			return false;
	}
</script>

@endpush