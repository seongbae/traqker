<div class="list-group list-group-flush">
    <x-canvas-input name="name" :value="old('name', $project->name ?? '')" autofocus />
    <x-canvas-textarea name="description" :value="old('description', $project->description ?? '')" />
    @if (count(Auth::user()->teamsWithManagerialAccess)>0)
    <x-canvas-select label="Team" name="team_id" :options="$teams" :value="old('team_id', $project->team_id ?? app('request')->input('team'))" />
    @endif
    @if ($action == 'edit')
   	<div class="list-group-item py-3">
        <div class="row">
            <label for="description" class="col-form-label col-md-2">Members</label>
            <div class="col-md tagsinput">
                <input type="text" name="users" class="form-control" id="users">
            </div>
        </div>
    </div>
    @endif
    @if (app('request')->input('parent'))
    <x-canvas-select label="Parent Project" name="parent_id" :options="$projects" :value="old('parent_id', $project->parent_id ?? app('request')->input('parent'))"  />
    @endif
</div>
