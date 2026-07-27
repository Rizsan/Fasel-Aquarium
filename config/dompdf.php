<?php

return [
    'show_warnings'   => false,
    'orientation'     => 'landscape',
    'defines' => [
        'DOMPDF_ENABLE_REMOTE'     => true,
        'DOMPDF_FONT_HEIGHT_RATIO' => 1.1,
        'DOMPDF_UNICODE_ENABLED'   => true,
        'DOMPDF_ENABLE_PHP'        => false,
        'DOMPDF_DPI'               => 120,
    ],
    'paper' => 'a4',
];
