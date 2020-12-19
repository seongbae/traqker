<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'user_type' => 'App\Models\User',

    'user_image_field' => 'photo',

    'user_image_path' => '/storage/',

    'default_image' => '/vendor/discuss/default-avatar.png',

    'channel_classes' => [
        'general' => 'btn btn-primary btn-sm',
        'networking-tips' => 'btn btn-secondary btn-sm',
        'best-practices' => 'btn btn-danger btn-sm',
        'feature-request' => 'btn btn-success btn-sm',
        'announcement' => 'btn btn-info btn-sm'
    ],

    'page_count' => 5,

    'view_mode'=>'public' // public = anyone can view. private = user must be logged in.
];
