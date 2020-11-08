<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use Auth;

class Helper
{
    public static function limitText($text, $size=200)
    {
        if (strlen($text)>$size)
        	return substr($text, 0, $size) . '...';
        else
        	return $text;
    }

    public static function getCommentUserImage($commenter)
    {
    	$user = Auth::user();

    	if ($user)
    		return '/storage/'.$user->photo;
    	else
    		return 'https://www.gravatar.com/avatar/'. md5($comment->commenter->email ?? $comment->guest_email).'jpg?s=64';
    }
}