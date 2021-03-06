<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';
$loader->add('DoctrineExtensions', __DIR__.'/../vendor/beberlei/DoctrineExtensions');

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
#######################
//use Doctrine\Common\Annotations\AnnotationRegistry;
//
//$loader = require __DIR__.'/../vendor/autoload.php';
//$loader->add('DoctrineExtensions', __DIR__.'/../vendor/beberlei/lib/DoctrineExtensions');
//
//// intl
//if (!function_exists('intl_get_error_code')) {
//    require_once __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';
//
//    $loader->add('', __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs');
//}
//
//AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
//
//return $loader;