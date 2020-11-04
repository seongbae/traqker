<div class="row">
    <div class="col-lg-6">
        <x-canvas-input name="name" :value="old('name', $task->name ?? '')" />
        <x-canvas-textarea name="description" :value="old('description', $task->description ?? '')" />
        <x-canvas-select name="priority" :options="$priority" :value="old('priority', $task->priority ?? '')" />
        @if (count($users)>0)
        <x-canvas-select name="assigned_to" :options="$users" :value="old('assigned_to', $task->assigned_to ?? '')" />
        @endif
        <x-canvas-select name="project_id" label="Project" :options="$projects" :value="old('project_id', $task->project_id ?? $project_id ?? '')" />
    </div>
    <div class="col-lg-6">
        <x-canvas-input name="start_on" type="date" :value="old('start_on', $task->start_on ?? '')" />
        <x-canvas-input name="due_on" type="date" :value="old('due_on', $task->due_on ?? '')" />
        <x-canvas-input name="estimate_hour" type="number" :value="old('estimate_hour', $task->estimate_hour ?? '')" />

        <x-canvas-file name="files[]" :label="__('Files')" :file-label="__('Choose Files')" id="files" :multiple="true" />
    </div>
    <input type="hidden" name="is_milestone" value="{{ $task != null ? $task->is_milestone : $milestone }}">
</div>
