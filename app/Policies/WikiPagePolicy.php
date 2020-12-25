<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WikiPage;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Team;
use Illuminate\Database\Eloquent\Model;

class WikiPagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user, Model $wikiable)
    {
        return $wikiable->contains($user);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WikiPage  $wikiPage
     * @return mixed
     */
    public function view(User $user, WikiPage $wikiPage)
    {
        return $wikiPage->wikiable()->contains($user);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user, Model $wikiable)
    {
        return $wikiable->contains($user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WikiPage  $wikiPage
     * @return mixed
     */
    public function update(User $user, WikiPage $wikiPage)
    {
        return $wikiPage->wikiable()->contains($user);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WikiPage  $wikiPage
     * @return mixed
     */
    public function delete(User $user, WikiPage $wikiPage)
    {
        $hasManagerialAccess = false;
        foreach($wikiPage->wikiable->members as $member)
        {
            if ($member == $user && $member->hasManagerialAccess($wikiPage->wikiable))
                $hasManagerialAccess = true;
        }

        return $wikiPage->created_user_id === $user->id || $hasManagerialAccess;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WikiPage  $wikiPage
     * @return mixed
     */
    public function restore(User $user, WikiPage $wikiPage)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WikiPage  $wikiPage
     * @return mixed
     */
    public function forceDelete(User $user, WikiPage $wikiPage)
    {
        //
    }
}
