<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use Auth;
use Log;

class Helper
{
    public static function limitText($text, $size=200)
    {
        if (strlen($text)>$size)
        	return substr($text, 0, $size) . '...';
        else
        	return $text;
    }

    public static function transformText($text)
    {
        $str = Helper::convertURLtoLink($text);

        $ctr = 0;
        $content = "";

        foreach(preg_split("/((\r?\n)|(\r\n?))/", $str) as $line){
            if (strpos($line, '[]') !== false) {
                $str = str_replace('[]', '<input type="checkbox" id="listitem" class="task-list-item" name="vehicle1" value="'.$ctr.'">', $line);
                $content = $content . $str.'<br>';
            } else if (strpos($line, '[*]') !== false) {
                $str = str_replace('[*]', '<input type="checkbox" id="listitem" class="task-list-item" name="vehicle1" value="'.$ctr.'" checked>', $line);
                $content = $content . $str.'<br>';
            }
            else {
                $content = $content . $line.'<br>';
            }

            $ctr++;
        }

        //$str = str_replace('[*]', '<input type="checkbox" id="listitem" class="task-list-item" name="vehicle1" value="Bike" checked>', $str);

        return $content;
    }

    public static function convertURLtoLink($text, $target='_blank')
    {
        $url = '@(http(s)?)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
        return preg_replace($url, '<a href="http$2://$4" target="'.$target.'" title="$0">$0</a>', $text);
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
