<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Snappy PDF / Image Configuration
    |--------------------------------------------------------------------------
    |
    | This option contains settings for PDF generation.
    |
    | Enabled:
    |
    |    Whether to load PDF / Image generation.
    |
    | Binary:
    |
    |    The file path of the wkhtmltopdf / wkhtmltoimage executable.
    |
    | Timeout:
    |
    |    The amount of time to wait (in seconds) before PDF / Image generation is stopped.
    |    Setting this to false disables the timeout (unlimited processing time).
    |
    | Options:
    |
    |    The wkhtmltopdf command options. These are passed directly to wkhtmltopdf.
    |    See https://wkhtmltopdf.org/usage/wkhtmltopdf.txt for all options.
    |
    | Env:
    |
    |    The environment variables to set while running the wkhtmltopdf process.
    |
    */

    'pdf' => [
        'enabled' => true,
        'binary'  => env('WKHTML_PDF_BINARY', '/usr/local/bin/wkhtmltopdf'),
        'timeout' => false,
        'options' => [
            'enable-local-file-access' => true,
            'encoding' => 'UTF-8',
            'page-size' => 'A4',
            'orientation' => 'portrait',
            'lowquality' => false,
            'footer-right' => 'SIGMA UKT 1 - Digenerate ' . \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') . ' | Halaman [page] / [topage]',
            'footer-font-size' => 8,
            'footer-spacing' => 2,
            'quiet' => false,
            'load-error-handling' => 'ignore',
            'load-media-error-handling' => 'ignore',
        ],
        'env'     => [],
    ],

    'image' => [
        'enabled' => true,
        'binary'  => env('WKHTML_IMG_BINARY', '/usr/local/bin/wkhtmltoimage'),
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],

];
