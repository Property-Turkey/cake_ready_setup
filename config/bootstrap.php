<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.8
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/*
 * Configure paths required to find CakePHP + general filepath constants
 */
require __DIR__ . DIRECTORY_SEPARATOR . 'paths.php';

/*
 * Bootstrap CakePHP.
 *
 * Does the various bits of setup that CakePHP needs to do.
 * This includes:
 *
 * - Registering the CakePHP autoloader.
 * - Setting the default application paths.
 */
require CORE_PATH . 'config' . DS . 'bootstrap.php';

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Database\Type\StringType;
use Cake\Database\TypeFactory;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorTrap;
use Cake\Error\ExceptionTrap;
use Cake\Http\ServerRequest;
use Cake\Log\Log;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Routing\Router;
use Cake\Utility\Security;

/*
 * See https://github.com/josegonzalez/php-dotenv for API details.
 *
 * Uncomment block of code below if you want to use `.env` file during development.
 * You should copy `config/.env.example` to `config/.env` and set/modify the
 * variables as required.
 *
 * The purpose of the .env file is to emulate the presence of the environment
 * variables like they would be present in production.
 *
 * If you use .env files, be careful to not commit them to source control to avoid
 * security risks. See https://github.com/josegonzalez/php-dotenv#general-security-information
 * for more information for recommended practices.
*/
// if (!env('APP_NAME') && file_exists(CONFIG . '.env')) {
//     $dotenv = new \josegonzalez\Dotenv\Loader([CONFIG . '.env']);
//     $dotenv->parse()
//         ->putenv()
//         ->toEnv()
//         ->toServer();
// }

/*
 * Read configuration file and inject configuration into various
 * CakePHP classes.
 *
 * By default there is only one configuration file. It is often a good
 * idea to create multiple configuration files, and separate the configuration
 * that changes from configuration that does not. This makes deployment simpler.
 */
try {
    Configure::config('default', new PhpConfig());
    Configure::load('app', 'default', false);
} catch (\Exception $e) {
    exit($e->getMessage() . "\n");
}

/*
 * Load an environment local configuration file to provide overrides to your configuration.
 * Notice: For security reasons app_local.php **should not** be included in your git repo.
 */
if (file_exists(CONFIG . 'app_local.php')) {
    Configure::load('app_local', 'default');
}

/*
 * When debug = true the metadata cache should only last
 * for a short time.
 */
if (Configure::read('debug')) {
    Configure::write('Cache._cake_model_.duration', '+2 minutes');
    Configure::write('Cache._cake_core_.duration', '+2 minutes');
    // disable router cache during development
    Configure::write('Cache._cake_routes_.duration', '+2 seconds');
}

/*
 * Set the default server timezone. Using UTC makes time calculations / conversions easier.
 * Check http://php.net/manual/en/timezones.php for list of valid timezone strings.
 */
date_default_timezone_set(Configure::read('App.defaultTimezone'));

/*
 * Configure the mbstring extension to use the correct encoding.
 */
mb_internal_encoding(Configure::read('App.encoding'));

/*
 * Set the default locale. This controls how dates, number and currency is
 * formatted and sets the default language to use for translations.
 */
ini_set('intl.default_locale', Configure::read('App.defaultLocale'));

/*
 * Register application error and exception handlers.
 */
(new ErrorTrap(Configure::read('Error')))->register();
(new ExceptionTrap(Configure::read('Error')))->register();

/*
 * Include the CLI bootstrap overrides.
 */
if (PHP_SAPI === 'cli') {
    require CONFIG . 'bootstrap_cli.php';
}

/*
 * Set the full base URL.
 * This URL is used as the base of all absolute links.
 */
$fullBaseUrl = Configure::read('App.fullBaseUrl');
if (!$fullBaseUrl) {
    /*
     * When using proxies or load balancers, SSL/TLS connections might
     * get terminated before reaching the server. If you trust the proxy,
     * you can enable `$trustProxy` to rely on the `X-Forwarded-Proto`
     * header to determine whether to generate URLs using `https`.
     *
     * See also https://book.cakephp.org/4/en/controllers/request-response.html#trusting-proxy-headers
     */
    $trustProxy = false;

    $s = null;
    if (env('HTTPS') || ($trustProxy && env('HTTP_X_FORWARDED_PROTO') === 'https')) {
        $s = 's';
    }

    $httpHost = env('HTTP_HOST');
    if (isset($httpHost)) {
        $fullBaseUrl = 'http' . $s . '://' . $httpHost;
    }
    unset($httpHost, $s);
}
if ($fullBaseUrl) {
    Router::fullBaseUrl($fullBaseUrl);
}
unset($fullBaseUrl);


Cache::setConfig(Configure::consume('Cache'));
ConnectionManager::setConfig(Configure::consume('Datasources'));
TransportFactory::setConfig(Configure::consume('EmailTransport'));
Mailer::setConfig(Configure::consume('Email'));
Log::setConfig(Configure::consume('Log'));
Security::setSalt(Configure::consume('Security.salt'));
/*
 * Setup detectors for mobile and tablet.
 * If you don't use these checks you can safely remove this code
 * and the mobiledetect package from composer.json.
 */
ServerRequest::addDetector('mobile', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isMobile();
});
ServerRequest::addDetector('tablet', function ($request) {
    $detector = new \Detection\MobileDetect();

    return $detector->isTablet();
});

/*
 * You can enable default locale format parsing by adding calls
 * to `useLocaleParser()`. This enables the automatic conversion of
 * locale specific date formats. For details see
 * @link https://book.cakephp.org/4/en/core-libraries/internationalization-and-localization.html#parsing-localized-datetime-data
 */
// \Cake\Database\TypeFactory::build('time')
//    ->useLocaleParser();
// \Cake\Database\TypeFactory::build('date')
//    ->useLocaleParser();
// \Cake\Database\TypeFactory::build('datetime')
//    ->useLocaleParser();
// \Cake\Database\TypeFactory::build('timestamp')
//    ->useLocaleParser();
// \Cake\Database\TypeFactory::build('datetimefractional')
//    ->useLocaleParser();
// \Cake\Database\TypeFactory::build('timestampfractional')
//    ->useLocaleParser();
// \Cake\Database\TypeFactory::build('datetimetimezone')
//    ->useLocaleParser();
// \Cake\Database\TypeFactory::build('timestamptimezone')
//    ->useLocaleParser();

// There is no time-specific type in Cake
TypeFactory::map('time', StringType::class);

/*
 * Custom Inflector rules, can be set to correctly pluralize or singularize
 * table, model, controller names or whatever other string is passed to the
 * inflection functions.
 */
//Inflector::rules('plural', ['/^(inflect)or$/i' => '\1ables']);
//Inflector::rules('irregular', ['red' => 'redlings']);
//Inflector::rules('uninflected', ['dontinflectme']);





Configure::write('app_folder',  in_array(env('SERVER_NAME'), ['localhost', 'devzonia.com']) ? '/'.basename(dirname(__DIR__))  : '' );
$isLocal = in_array(env('SERVER_NAME'), ['localhost']) ? true : false;
Configure::write('isLocal',  $isLocal);
Configure::write('protocol', $isLocal ? 'http' : 'https' );
Configure::write('path', Configure::read('protocol').'://'.env('SERVER_NAME').Configure::read('app_folder'));

Configure::write('salt', Security::getSalt());
Configure::write('uid', (md5(env('HTTP_USER_AGENT') .  env('REMOTE_ADDR'))));

Cache::write('stats', [0=>__("disabled"), 1=>__("enabled"), 2=>__('sold')]);
Cache::write('days', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
Cache::write('gender', [1=>__('female'), 2=>__('male')]);
Cache::write('bool', ["0"=>__("no"), "1"=>__("yes")]);

// Cache::write('roles', [ 'admin.root'=>'admin.root', 'admin.admin'=>'admin.admin', 'admin.editor'=>'admin.editor' ]);
Cache::write('AdminRoles', [ 'admin.root'=>'admin.root', 'admin.admin'=>'admin.admin', 'admin.editor'=>'admin.editor' ]);
Cache::write('langs', [1=>'ar', 2=>'en', 3=>'ru']);
Cache::write('langs_ids', ['ar'=>1, 'en'=>2, 'ru'=>3]);
Cache::write('currencies', [4=>'GBP', 1=>'EUR', 2=>'USD', 3=>'TRY']);
Cache::write('currencies_icons', [4=>'£', 1=>'€', 2=>'$', 3=>'₺']);

Cache::write('ROLES', [
	'admin.root'=>[//     (technical top level admin) 
		'categories'=>['create'=>1, 'read'=>1, 'update'=>1, 'delete'=>1, 'allids'=>1],
		'contents'=>['create'=>1, 'read'=>1, 'update'=>1, 'delete'=>1, 'allids'=>1],
		'configs'=>['create'=>1, 'read'=>1, 'update'=>1, 'delete'=>1, 'allids'=>1],
		'users'=>['create'=>1, 'read'=>1, 'update'=>1, 'delete'=>1, 'allids'=>1],
		'logs'=>['create'=>1, 'read'=>1, 'update'=>1, 'delete'=>1, 'allids'=>1],
	],
	'admin.admin'=>[//     admin
		'categories'=>['create'=>0, 'read'=>0, 'update'=>0, 'delete'=>0, 'allids'=>0],
		'contents'=>['create'=>1, 'read'=>1, 'update'=>1, 'delete'=>1, 'allids'=>1],
		'configs'=>['create'=>1, 'read'=>1, 'update'=>0, 'delete'=>0, 'allids'=>0],
		'users'=>['create'=>1, 'read'=>1, 'update'=>1, 'delete'=>1, 'allids'=>1],
		'logs'=>['create'=>0, 'read'=>1, 'update'=>0, 'delete'=>0, 'allids'=>0],
	],
	'admin.editor'=>[//     editor
		'categories'=>['create'=>0, 'read'=>0, 'update'=>0, 'delete'=>0, 'allids'=>0],
		'contents'=>['create'=>1, 'read'=>1, 'update'=>1, 'delete'=>1, 'allids'=>0],
		'configs'=>['create'=>0, 'read'=>0, 'update'=>0, 'delete'=>0, 'allids'=>0],
		'users'=>['create'=>0, 'read'=>1, 'update'=>1, 'delete'=>1, 'allids'=>0],
		'logs'=>['create'=>0, 'read'=>0, 'update'=>0, 'delete'=>0, 'allids'=>0],
	],
]);

$features_parents = [
    117 => 'İnşaat Teknikleri', 
    122 => 'Proje Tipi', 
    123 => 'Site Özellikleri', 
    124 => 'Bina Özellikleri', 
    125 => 'Konut Özellikleri',
];
Cache::write('features_parents', $features_parents);

$features = [
    // categories
    122 => [//'Proje Tipi', 
        245 => 'Daire', 
        246 => 'Villa', 
        247 => 'Rezidans', 
        248 => 'Yazlık', 
        249 => 'Ev-ofis', 
        250 => 'Dağ/kır evi', 
        251 => 'Hazır ev', 
        252 => 'Ofis', 
        253 => 'Dükkan', 
        254 => 'Ünite', 
        369 => 'Yürüyüş parkuru', 
    ],
    // site
    123 => [//'Site Özellikleri', 
        370 => 'Kamelya', 
        371 => 'Plaj', 
        372 => 'Marina', 
        373 => 'Deniz manzarası', 
        374 => 'Göl manzarası', 
        375 => 'Vadi manzarası', 
        376 => 'Dağ manzarası', 
        377 => 'Orman manzarası', 
        378 => 'Havuz manzarası', 
        379 => 'İstanbul boğazı manzarası', 
        380 => 'Haliç manzarası', 
        381 => 'Adalar manzarası', 
        382 => 'Açık otopark', 
        383 => 'Kapalı otopark', 
        384 => 'Şelale', 
        385 => 'Sosyal tesis', 
        386 => 'Merkezi uydu sistemi', 
        387 => 'Süs havuzu', 
        388 => 'Kuaför', 
        389 => 'Güneşlenme terası', 
        390 => 'Vitamin bar', 
        391 => 'Çarşı', 
        392 => 'Mescit', 
        393 => 'Amfi tiyatro', 
        394 => 'Masa tenisi', 
        395 => 'Bilardo', 
        396 => 'Golf alanı', 
        397 => 'Araç Şarj İstasyonu', 
        398 => 'Market', 
        399 => 'Kapalı yüzme havuzu', 
        400 => 'Açılır-kapanır yüzme havuzu', 
        401 => 'Çocuk yüzme havuzu', 
        402 => 'Çocuk oyun alanları', 
        403 => 'Sauna', 
        404 => 'Türk hamamı', 
        405 => 'Fin hamamı', 
        406 => 'Fitness merkezi', 
        407 => 'SPA', 
        408 => 'AVM', 
        409 => 'Mağaza', 
        410 => 'Hastane, revir', 
        411 => 'Kreş', 
        412 => 'Açık yüzme havuzu', 
        413 => 'Kafe', 
        414 => 'Restoran', 
        415 => 'Okul', 
        416 => 'Sinema', 
        417 => 'Güvenlik', 
        418 => 'Kameralı güvenlik', 
        419 => 'Futbol sahası', 
        420 => 'Basketbol sahası', 
        421 => 'Voleybol sahası', 
        422 => 'Tenis kortu', 
        423 => 'İskele', 
        424 => 'Göl/gölet', 
    ],

    // bina
    124 => [//'Bina Özellikleri', 
        425 => 'Su Deposu', 
        426 => 'Hidrofor', 
        427 => 'Jeneratör', 
        428 => 'Yangın merdiveni', 
        429 => 'Asansör', 
        430 => 'Yük asansörü', 
        431 => 'Paratoner', 
        432 => 'Lobi', 
        433 => 'Resepsiyon', 
        434 => 'Sprinkler sistemi', 
    ],

    // kunot 
    125 => [//'Konut Özellikleri',
        435 => 'Ankastre beyaz eşya', 
        436 => 'İntercom sistemi', 
        437 => 'Kombi', 
        438 => 'Yerden ısıtma', 
        439 => 'Merkezi ısıtma', 
        440 => 'Isı pay ölçer', 
        441 => 'Split klima', 
        442 => 'Merkezi klima', 
        443 => 'Güneş enerjisi', 
        444 => 'Elektrikli ısıtma', 
        445 => 'Jeotermal enerji', 
        446 => 'Fransız balkon', 
        447 => 'Ebeveyn banyosu', 
        448 => 'Ebeveyn giyinme odası', 
        449 => 'Çamaşır odası', 
        450 => 'Jakuzi', 
        451 => 'Duşakabin', 
        452 => 'Hilton lavabo', 
        453 => 'Şömine', 
        454 => 'Teras', 
        455 => 'Balkon', 
        456 => 'Veranda', 
        457 => 'Depo/kiler', 
        458 => 'Alarm sistemleri', 
        459 => 'Akıllı ev altyapısı', 
        460 => 'Akıllı ev sistemleri', 
        461 => 'Rezidans hizmetleri', 
        462 => 'Özel yüzme havuzu (villa için)', 
        463 => 'Hizmetli odası', 
        464 => 'Hobi odası', 
        465 => 'Bahçe Kullanımlı', 
    ],

    // insaat teknik
    117 => [//'İnşaat Teknikleri',
        471 => 'Yapı denetimi yapılmış', 
        472 => 'Zemin etüdü yapılmış', 
        473 => 'Deprem yönetmeliğine uygun', 
        474 => 'Yalıtım yönetmeliğine uygun', 
        475 => 'Radye temel', 
        476 => 'Tünel Kalıp', 
        477 => 'Hiking trail', 
        478 => 'Camelia', 
    ],



    // english
    // 479 => 'Outdoor parking', 
    // 480 => 'Closed parking', 
    // 481 => 'Outdoor swimming pool', 
    // 482 => 'Security', 
    // 483 => 'Water Tank', 
    // 484 => 'Elevator', 
    // 485 => 'Camera security', 
    // 486 => 'Master bathroom', 
    // 487 => 'Parents dressing room', 
    // 488 => 'Patio', 
    // 489 => 'Private swimming pool (for villa)', 
    // 490 => 'Garden-Use', 
    // 491 => 'Apartment', 
    // 492 => 'Built-in appliances', 
    // 493 => 'Underfloor heating', 
    // 494 => 'Building inspection done', 
    // 495 => 'Ground survey done', 
    // 496 => 'Earthquake compliant', 
    // 497 => 'Insulation compliant', 
    // 498 => 'Raft earthquake resistant', 
    // 499 => 'Tunnel Formwork', 
    // 500 => 'Children`s playgrounds', 
    // 501 => 'Fitness center', 
    // 502 => 'Laundry room', 
    // 503 => 'Balcony', 
    // 504 => 'Storage/pantry', 
    // 505 => 'Turkish bath', 
    // 506 => 'Cafe', 
    // 507 => 'Football court', 
    // 508 => 'volleyball court', 
    // 509 => 'Generator', 
    // 510 => 'Smart home systems', 
    // 511 => 'C25 üstü beton sınıfı', 
    // 512 => 'Unit', 
    // 513 => 'Pool view', 
    // 514 => 'Pool', 
    // 515 => 'Indoor swimming pool', 
    // 516 => 'Hydrophore', 
    // 517 => 'Fire ladder', 
    // 518 => 'Home-office', 
    // 519 => 'Lobby',
];
Cache::write('features', $features);

$all_features = $features[117] +  $features[122] +  $features[123] +  $features[124]+  $features[125] ; 
Cache::write('all_features', $all_features);

$cities = [
    126 => 'stanbul', 
    127 => 'İstanbul Anadolu', 
    128 => 'İstanbul Avrupa', 
    129 => 'Ankara', 
    130 => 'Izmir', 
    131 => 'Adana', 
    132 => 'Adıyaman', 
    133 => 'Afyonkarahisar', 
    134 => 'Ağrı', 
    135 => 'Aksaray', 
    136 => 'Amasya', 
    137 => 'Antalya', 
    138 => 'Ardahan', 
    139 => 'Artvin', 
    140 => 'Aydın', 
    141 => 'Balıkesir', 
    142 => 'Bartın', 
    143 => 'Batman', 
    144 => 'Bayburt', 
    145 => 'Bilecik', 
    146 => 'Bingöl', 
    147 => 'Bitlis', 
    148 => 'Bolu', 
    149 => 'Burdur', 
    150 => 'Bursa', 
    151 => 'Çanakkale', 
    152 => 'Çankırı', 
    153 => 'Çorum', 
    154 => 'Denizli', 
    155 => 'Diyarbakır', 
    156 => 'Düzce', 
    157 => 'Edirne', 
    158 => 'Elazığ', 
    159 => 'Erzincan', 
    160 => 'Erzurum', 
    161 => 'Eskişehir', 
    162 => 'Gaziantep', 
    163 => 'Giresun', 
    164 => 'Gümüşhane', 
    165 => 'Hakkari', 
    166 => 'Hatay', 
    167 => 'Iğdır', 
    168 => 'Isparta', 
    169 => 'Kahramanmaraş', 
    170 => 'Karabük', 
    171 => 'Karadağ', 
    172 => 'Karaman', 
    173 => 'Kars', 
    174 => 'Kastamonu', 
    175 => 'Kayseri', 
    176 => 'Kırıkkale', 
    177 => 'KKTC', 
    178 => 'Kırklareli', 
    179 => 'Kırşehir', 
    180 => 'Kilis', 
    181 => 'Kocaeli', 
    182 => 'Konya', 
    183 => 'Kütahya', 
    184 => 'Malatya', 
    185 => 'Manisa', 
    186 => 'Mardin', 
    187 => 'Mersin', 
    188 => 'Muğla', 
    189 => 'Muş', 
    190 => 'Nevşehir', 
    191 => 'Niğde', 
    192 => 'Ordu', 
    193 => 'Osmaniye', 
    194 => 'Rize', 
    195 => 'Sakarya', 
    196 => 'Samsun', 
    197 => 'Siirt', 
    198 => 'Sinop', 
    199 => 'Sivas', 
    200 => 'Şanlıurfa', 
    201 => 'Şırnak', 
    202 => 'Tekirdağ', 
    203 => 'Tokat', 
    204 => 'Trabzon', 
    205 => 'Tunceli', 
    206 => 'Uşak', 
    207 => 'Van', 
    208 => 'Yalova', 
    209 => 'Yozgat', 
    210 => 'Zonguldak',
];
Cache::write('cities', $cities);


