<?php
/**
 * @see https://github.com/Edujugon/PushNotification
 */

return [
    'gcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'My_ApiKey',
    ],
    'fcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'AAAAimkxl8E:APA91bFt7jY6Jx8qbX_0VIs9Ap2JlvONA4lb2mxRdm_IFpFLZl11KsD-GAIA4eoCOrzO5ozHvLmi005XWOGn-b3nu8Qq9TzRrEMSJBTTtd281UCsoOnMmgz3xXXdTg2EgWRDGtyMm4ib',
    ],
    'apn' => [
        'certificate' => __DIR__ . '/iosCertificates/apns-dev-cert.pem',
        'passPhrase' => 'secret', //Optional
        'passFile' => __DIR__ . '/iosCertificates/yourKey.pem', //Optional
        'dry_run' => true,
    ],
];
