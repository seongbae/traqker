<div class="list-group list-group-flush">
    <x-canvas-input name="hours" type="number" :value="old('hours', $hour->hours ?? '')" autofocus="true" />
    <x-canvas-input name="description" :value="old('description', $hour->description ?? '')" />
    <x-canvas-select name="project_id" label="Project" :options="$projects" :value="old('project_id', $hour->project_id ?? '')" />
</div>
