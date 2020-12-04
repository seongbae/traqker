<?php


namespace App\Services;


use App\Events\TaskComplete;
use Illuminate\Support\Facades\Mail;

class EventNotificationService
{
    /*
     * Projects
     *  Added to project
     *
     * Tasks
     *  Task assigned
     *  Task complete
     *  Task updated
     *  Attachment added
     *
     * Team
     *  Member invited
     *  Member accepted
     *  Member joined
     *
     * Comments
     *  New comment added
     */

    const TEAM_INVITE_SENT = 'team_invite_sent';
    const TEAM_INVITE_ACCEPTED = 'team_invite_accepted';

    const PROJECT_NEW_MEMBER_ADDED = 'project_new_member_added';

    const TASK_ASSIGNED = 'task_assigned';
    const TASK_COMPLETE = 'task_complete';

    const COMMENT_CREATED = 'comment_created';

    public function taskAssigned($users, $event, $model=null)
    {
        event(new TaskAssigned($users, $model));
    }

    public function taskComplete($users, $event, $model=null)
    {
        event(new TaskComplete($users, $model));
    }
}
