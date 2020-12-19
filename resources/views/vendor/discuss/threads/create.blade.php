@extends('canvas::admin.layouts.app')
@section('content')
<section>
	<div class="container">
		<div class="row">
			<div class="col-md-8 offset-md-2">
				<div class="card">
					<div class="card-header">Create a New Thread</div>
					<div class="card-body">
						@include('discuss::threads._partials.form')
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
