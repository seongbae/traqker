<div class="list-group list-group-flush">
    <x-canvas-input name="name" :value="old('name', $project->name ?? '')" />
    <x-canvas-textarea name="description" :value="old('description', $project->description ?? '')" />
    @if (count(Auth::user()->myTeams)>0)
    <x-canvas-select label="Team" name="team_id" :options="$teams" :value="old('team_id', $project->team_id ?? app('request')->input('team'))" />
    @endif
    @if ($action == 'edit')
   	<x-canvas-manymany label="Members" name="project_user" :options="$users" :value="$data" :deleteLink="$memberDeleteLink"  :additionalFields="$additionalFields" />
   	@endif
    <x-canvas-select label="Parent Project" name="parent_id" :options="$projects" :value="old('parent_id', $project->parent_id ?? '')"  />

</div>
