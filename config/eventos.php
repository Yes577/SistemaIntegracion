<?php

return [
    'qr' => [
        'ttl_hours' => (int) env('EVENT_QR_TTL_HOURS', 24),
        'svg_size' => (int) env('EVENT_QR_SVG_SIZE', 360),
    ],

    'notifications' => [
        'reminder_hours' => (int) env('EVENT_REMINDER_HOURS', 24),
        'queue' => env('EVENT_NOTIFICATION_QUEUE', 'mail'),
    ],
];
