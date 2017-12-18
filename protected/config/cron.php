<?php
return array_replace_recursive(
 array(
    // У вас этот путь может отличаться. Можно подсмотреть в config/main.php.
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'Cron',

    'preload'=>array('log'),
    
    'import'=>array(
		'application.components.*',
		'application.models.*',
    ),
	// Копирование yiic.php и console.php было сделано ради
    // перенаправления журнала для cron в отдельные файлы:
    'components'=>array(
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
                    'logFile'=>'cron.log',
					'levels'=>'error, warning',
				),
				array(
					'class'=>'CFileLogRoute',
					'logFile'=>'cron_trace.log',
					'levels'=>'trace',
				),
			),
		),

        // Соединение с СУБД
		'db'=>array(
			'class'=>'CDbConnection',
			// …
		),
    ),
    'params'=>array(
	    'ALLOWED_COUNTRIES'=>array('ru','en'),
	  ),

    ),
     (file_exists(__DIR__ . '/overrides/environment.php') ? require(__DIR__ . '/overrides/environment.php') : array()),
    (file_exists(__DIR__ . '/overrides/local.php') ? require(__DIR__ . '/overrides/local.php') : array())
);

