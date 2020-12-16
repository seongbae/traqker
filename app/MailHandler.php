<?php

namespace App;

use App\Models\ReceivedMail;
use BeyondCode\Mailbox\InboundEmail;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use Log;

class MailHandler {
    public function __invoke(InboundEmail $email, $projectSlug) {

        Log::info('MailHandler called...');

        $user = User::where('email', $email->from())->first();
        $project = Project::where('slug',$projectSlug)->first();

        if ($project && $user)
        {
            foreach($project->members as $member)
            {
                if ($member->email == $user->email)
                {
                    $task = new Task;
                    $task->name =  $email->subject();
                    $task->description = $email->text();
                    $task->user_id = $user->id;
                    $task->assigned_to = $user->id;
                    $task->project_id = $project->id;
                    $task->saveQuietly();

                    break;
                }
            }
        }


    }
}
