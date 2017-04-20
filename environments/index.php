<?php
/**
 * The manifest of files that are local to specific environment.
 * This file returns a list of environments that the application
 * may be installed under. The returned data must be in the following
 * format:
 *
 * ```php
 * return [
 *     'environment name' => [
 *         'path' => 'directory storing the local files',
 *         'skipFiles'  => [
 *             // list of files that should only copied once and skipped if they already exist
 *         ],
 *         'setWritable' => [
 *             // list of directories that should be set writable
 *         ],
 *         'setExecutable' => [
 *             // list of files that should be set executable
 *         ],
 *         'setCookieValidationKey' => [
 *             // list of config files that need to be inserted with automatically generated cookie validation keys
 *         ],
 *         'createSymlink' => [
 *             // list of symlinks to be created. Keys are symlinks, and values are the targets.
 *         ],
 *     ],
 * ];
 * ```
 */
return [
    'Development' => [
        'path' => 'dev',
        'setWritable' => [
            'common/runtime',
            'ucenter/runtime',
            'ucenter/web/assets',
            'oa/runtime',
            'oa/web/assets',
            'api/runtime',
            'api/web/assets',
            'login/runtime',
            'login/web/assets',
            'test/runtime',
            'test/web/assets',
            'yun/runtime',
            'yun/web/assets',
            'clientResource/runtime',
            'clientResource/web/assets',
            'merchantResource/runtime',
            'merchantResource/web/assets',
        ],
        'setExecutable' => [
            'yii',
            'yii_test',
        ],
        'setCookieValidationKey' => [
            'ucenter/config/main-local.php',
            'oa/config/main-local.php',
            'api/config/main-local.php',
            'login/config/main-local.php',
            'test/config/main-local.php',
            'yun/config/main-local.php',
            'clientResource/config/main-local.php',
            'merchantResource/config/main-local.php',
        ],
    ],
    'Production' => [
        'path' => 'prod',
        'setWritable' => [
            'common/runtime',
            'ucenter/runtime',
            'ucenter/web/assets',
            'oa/runtime',
            'oa/web/assets',
            'api/runtime',
            'api/web/assets',
            'login/runtime',
            'login/web/assets',
            'yun/runtime',
            'yun/web/assets',
            'clientResource/runtime',
            'clientResource/web/assets',
            'merchantResource/runtime',
            'merchantResource/web/assets',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'ucenter/config/main-local.php',
            'oa/config/main-local.php',
            'api/config/main-local.php',
            'login/config/main-local.php',
            'yun/config/main-local.php',
            'clientResource/config/main-local.php',
            'merchantResource/config/main-local.php',
        ],
    ],
    'Test' => [
        'path' => 'test',
        'setWritable' => [
            'common/runtime',
            'ucenter/runtime',
            'ucenter/web/assets',
            'oa/runtime',
            'oa/web/assets',
            'api/runtime',
            'api/web/assets',
            'login/runtime',
            'login/web/assets',
            'yun/runtime',
            'yun/web/assets',
            'clientResource/runtime',
            'clientResource/web/assets',
            'merchantResource/runtime',
            'merchantResource/web/assets',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'ucenter/config/main-local.php',
            'oa/config/main-local.php',
            'api/config/main-local.php',
            'login/config/main-local.php',
            'yun/config/main-local.php',
            'clientResource/config/main-local.php',
            'merchantResource/config/main-local.php',
        ],
    ],
];
