@extends('canvas::admin.layouts.app')
@section('content')
<div class="card card-info">
	<div class="card-header">
		<h3 class="card-title">Site Settings</h3>
	</div>
	<!-- /.card-header -->
	<!-- form start -->
	<form class="form-horizontal" method="POST" action="/admin/settings" enctype="multipart/form-data">
		@csrf
		<div class="card-body">
			<h5 class="mt-4 mb-2">Site Information</h5>
			<div class="form-group row">
				<label for="site_name" class="col-sm-2 col-form-label">Site Name</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="inputEmail3"  name="site_name" value="{{option('site_name')}}">
				</div>
			</div>
			<div class="form-group row">
				<label for="site_description" class="col-sm-2 col-form-label">Site Description</label>
				<div class="col-sm-10">
					<textarea name="site_description" class="form-control">{{option('site_description')}}</textarea>
				</div>
			</div>
			<div class="form-group row">
				<label for="from_email" class="col-sm-2 col-form-label">From E-mail</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="from_email"  name="from_email" value="{{option('from_email')}}">
				</div>
			</div>
			<div class="form-group row">
				<label for="from_name" class="col-sm-2 col-form-label">From Name</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="from_name"  name="from_name" value="{{option('from_name')}}">
				</div>
			</div>
			<div class="form-group row">
				<label for="notification_email" class="col-sm-2 col-form-label">Notification E-mail</label>
				<small></small>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="notification_email"  name="notification_email" value="{{option('notification_email')}}">
					<span class="help-block"><small></small></span>
				</div>
			</div>
			<div class="form-group row">
				<label for="from_name" class="col-sm-2 col-form-label">Google Analytics ID</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="google_analytics_id"  name="google_analytics_id" value="{{option('google_analytics_id')}}">
				</div>
			</div>
			<div class="form-group row">
				<label for="notification_email" class="col-sm-2 col-form-label">Maintenance Mode</label>
				<small></small>
				<div class="col-sm-10">
					<div class="custom-control custom-switch pt-2">
						<input type="checkbox" class="custom-control-input" id="maintenance_mode" name="maintenance_mode" value="1" {{option('maintenance_mode') == "1" ? "checked" : ""}}>
						<label class="custom-control-label" for="maintenance_mode"></label>
					</div>
				</div>
			</div>
			<hr>
			<h5 class="mt-4 mb-2">Users</h5>
			<div class="form-group row">
				<label for="site_name" class="col-sm-2 col-form-label">Default role</label>
				<div class="col-sm-10">
					<select name="default_role" class="form-control">
						<option></option>
						@foreach($roles as $role)
						<option value="{{$role->id}}" {{ option('default_role') == $role->id ? "selected":"11"}}>{{$role->name}}</option>
						@endforeach
					</select>
				</div>
			</div>
			<hr>
			<h5 class="mt-4 mb-2">Business Information</h5>
			<div class="form-group row">
				<label for="business_name" class="col-sm-2 col-form-label">Business Name</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="business_name"  name="business_name" value="{{option('business_name')}}">
				</div>
			</div>
			<div class="form-group row">
				<label for="business_email" class="col-sm-2 col-form-label">Business E-mail</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="business_email"  name="business_email" value="{{option('business_email')}}">
				</div>
			</div>
			<div class="form-group row">
				<label for="business_phone" class="col-sm-2 col-form-label">Business Phone</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="business_phone"  name="business_phone" value="{{option('business_phone')}}">
				</div>
			</div>
			<div class="form-group row">
				<label for="business_address" class="col-sm-2 col-form-label">Business Address</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="business_address"  name="business_address" value="{{option('business_address')}}">
				</div>
			</div>
			<hr>
			<h5 class="mt-4 mb-2">Advanced Settings</h5>
			<div class="form-group row">
				<label for="resource_rename" class="col-sm-2 col-form-label">Rename Resources</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="resource_rename"  name="resource_rename" value="{{option('resource_rename')}}">
				</div>
			</div>
			<hr>
			<h5 class="mt-4 mb-2">Modules</h5>
			<div class="form-group row">
				<label for="notification_email" class="col-sm-2 col-form-label"></label>
				<small></small>
				<div class="col-sm-10">
				@if (count($modules) > 0)
					@foreach ($modules as $module)
					<div class="custom-control custom-switch pt-2">
						<input type="checkbox" class="custom-control-input" id="modules_{{$module}}" name="modules[]" value="{{$module}}" {{ in_array($module, $enabledModules) ? "checked" : ""}} {{ moduleInstalled($module) ? '': 'disabled'}}>
						<label class="custom-control-label" for="modules_{{$module}}">{{$module}}</label>
						<small>[
						@if (moduleInstalled($module))
						installed | <a href="/admin/modules/uninstall/{{$module}}">uninstall</a>
						@else
						<a href="/admin/modules/install/{{$module}}">install</a>
						@endif
						]</small>
					</div>
					@endforeach
				@else
				No modules found
				@endif
				</div>
			</div>
			
			@foreach($moduleOptionGroups as $group => $moduleOptions)
			@if ($moduleOptions != null)
				<hr>
				<h5 class="mt-4 mb-2">{{$group}} Settings</h5>
				@foreach($moduleOptions as $moduleOption)
				<div class="form-group row">
					<label for="{{$moduleOption['slug']}}" class="col-sm-2 col-form-label">{{$moduleOption['name']}}</label>
					<div class="col-sm-10">
						@if ($moduleOption['type'] == 'checkbox')
						<div class="custom-control custom-switch pt-2">
							<input type="checkbox" class="custom-control-input" id="{{$moduleOption['slug']}}" name="{{$moduleOption['slug']}}" value="1" {{option($moduleOption['slug']) == "1" ? "checked" : ""}}>
							<label class="custom-control-label" for="{{$moduleOption['slug']}}"></label>
						</div>
						@else
						<input type="{{$moduleOption['type']}}" class="form-control" id="{{$moduleOption['slug']}}"  name="{{$moduleOption['slug']}}" value="{{option($moduleOption['slug'])}}">
						@endif
						@if (array_key_exists('helper_text', $moduleOption))
						<span class="help-block"><small>{{$moduleOption['helper_text']}}</small></span>
						@endif
					</div>
				</div>
				@endforeach
			@endif
			@endforeach
		</div>
		<!-- /.card-body -->
		<div class="card-footer">
			<button type="submit" class="btn btn-primary">Save</button>
		</div>
	</form>
</div>
@endsection