<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Database Table
    |--------------------------------------------------------------------------
    |
    | The table that stores notification preferences for each notifiable.
    |
    */

    'table' => 'notification_preferences',

    /*
    |--------------------------------------------------------------------------
    | Default Policy
    |--------------------------------------------------------------------------
    |
    | Applied when no preference row exists for a given channel inside a
    | group. Individual groups may override this value below.
    |
    | Supported: "opt_in", "opt_out"
    |
    */

    'default_policy' => 'opt_in',

    /*
    |--------------------------------------------------------------------------
    | Groups
    |--------------------------------------------------------------------------
    |
    | Define the notification groups used in the application. Each group may
    | declare its own default_policy (overrides the global one above) and a
    | list of channels that cannot be opted out of.
    |
    */

    'groups' => [

        // 'marketing' => [
        //     'default_policy' => 'opt_out',
        // ],
        //
        // 'security' => [
        //     'default_policy' => 'opt_in',
        //     'forced'         => ['mail'],
        // ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Class Map
    |--------------------------------------------------------------------------
    |
    | Maps notification classes to a group when the class cannot be
    | annotated directly (for example, classes shipped by third-party
    | packages). Classes that declare the #[NotificationGroup] attribute
    | take precedence over entries in this map.
    |
    */

    'class_map' => [

        // \App\Notifications\OrderShipped::class => 'orders',

    ],

    /*
    |--------------------------------------------------------------------------
    | Preference Cache
    |--------------------------------------------------------------------------
    |
    | Resolved preferences may be cached per notifiable to avoid hitting
    | the database on every dispatch. Set "store" to null to use the
    | application's default cache store.
    |
    */

    'cache' => [
        'enabled' => true,
        'ttl'     => 300,
        'store'   => null,
    ],

];
