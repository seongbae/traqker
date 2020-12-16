@component('mail::message')

You have been added to a project: {{$project->name}}.

@if ($project->description)
**Description**: {{ $project->description }}<br>
@endif
@if ($project->team)
**Team**: {{ $project->team->name }}<br>
@endif

@component('mail::button', ['url' => route('projects.show',$project)])
Go to Project
@endcomponent

Thank you.<br>
{{ config('app.name') }}
@endcomponent
