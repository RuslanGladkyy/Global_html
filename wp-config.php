<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'Global');

/** Имя пользователя MySQL */
define('DB_USER', 'mysql');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', 'mysql');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'r_DU<8#{6j_Yo-;yFc`d*2wEZ7h7w`NU]5A6O@B5sDD6pa68UXnH/z<gJ}[PE>,:');
define('SECURE_AUTH_KEY',  '4oS5!pK4Dal2:pyjFMUfNL]s@&=)k&n/xpNV/|Kb3|20|lbo:q]qTmXF5X[YfuK6');
define('LOGGED_IN_KEY',    'toUC,Ze ZEKNXcD=-!NV6sLB^D*LloYWRim>$!#3-tMbch*I}c&mNbdh%J~ZfQ:T');
define('NONCE_KEY',        'l&E4wZsT)4(-dXQ[lI-]&.BpGQ9Z;X%Yl1g|!>Z5-9qr[T#R-(x87 03ccz<o;;!');
define('AUTH_SALT',        'GsAZl5J)KcHR#v1>^`#v#QHSju$nFl6m-|#,AxNE@;ejwZKHwBUIc{^bFrfmS*#Q');
define('SECURE_AUTH_SALT', 'nkh~dD::6[><2hbvX-cncD/|r/8F96s|PxP9+T,Dk9%]BhH_9tZA#z{[%.v6a|Zg');
define('LOGGED_IN_SALT',   ';e<;WkXhH<dwqqOOVX?y<6K+3iI 04 #d?Fn&*.ys8Q?j>aOJx&;iE,{^h>Lh9ZP');
define('NONCE_SALT',       'aCVdDA+_wz=W{L#Iv8{>Q*{+apW^cp~SO^>boqtPurW/G2yY[BmU>+7D1iv1M|:%');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 * 
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');
