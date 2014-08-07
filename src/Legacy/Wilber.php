<?php

namespace Nemex\Legacy;

/**
 * Generic guy which will help refactor
 * the project step by step.
 */
class Wilber
{
    private static $twig = null;

    public static function getTwig()
    {
        if (is_null(self::$twig)) {
            self::$twig = self::createTwigInstance(NEMEX_PATH.'template');
        }

        return self::$twig;
    }

    private static function createTwigInstance($templateDirectory)
    {
        $templateLoader = new \Twig_Loader_Filesystem($templateDirectory);
        $options = [
            'debug' => false,
            'charset' => 'utf-8',
            'autoescape' => false
        ];
        $twig = new \Twig_Environment($templateLoader, $options);

        return $twig;
    }
}
