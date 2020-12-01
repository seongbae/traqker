<div class="row">
    <div class="col-lg-6">
        <x-canvas-input name="name" :value="old('name', $task->name ?? '')" :autofocus="true"/>
        <x-canvas-textarea name="description" :value="old('description', $task->description ?? '')" />
        <x-canvas-select name="priority" :options="$priority" :value="old('priority', $task->priority ?? '')" />

        @if (app('request')->input('project'))
            <input type="hidden" name="project_id" value="{{app('request')->input('project')}}">
        @else
            <x-canvas-select name="project_id" label="Project" :options="$projects" :value="old('project_id', $task->project_id ?? $project_id ?? '')" />
        @endif

        <div class="list-group-item py-3">
            <div class="row">
                <label for="description" class="col-form-label col-md-2">Assigned to</label>
                <div class="col-md tagsinput">
                    <input type="text" name="assignees" class="form-control" id="assignees">
                </div>
            </div>
        </div>

    </div>
    <div class="col-lg-6">
        <x-canvas-input name="start_on" type="date" :value="old('start_on', $task->start_on ?? '')" />
        <x-canvas-input name="due_on" type="date" :value="old('due_on', $task->due_on ?? '')" />
        <x-canvas-input name="estimate_hour" type="number" :value="old('estimate_hour', $task->estimate_hour ?? '')" />

        <x-canvas-file name="files[]" :label="__('Files')" :file-label="__('Choose Files')" id="files" :multiple="true" />
    </div>
    <input type="hidden" name="is_milestone" value="{{ $task != null ? $task->is_milestone : $milestone }}">
</div>
