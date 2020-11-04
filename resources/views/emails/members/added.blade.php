@component('mail::message')

You have been added to the following project: **{{$project->name}}**

@component('mail::button', ['url' => config("app.url")])
Go to {{ config('app.name') }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
