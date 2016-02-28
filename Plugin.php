<?php namespace VojtaSvoboda\TwigExtensions;

use App;
use Backend;
use Carbon\Carbon;
use System\Classes\PluginBase;

/**
 * Twig Extensions Plugin
 * - add more Twig filters to your template
 *
 * @see http://twig.sensiolabs.org/doc/extensions/index.html#extensions-install
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Twig Extensions',
            'description' => 'Add more Twig filters to your templates.',
            'author'      => 'Vojta Svoboda',
            'icon'        => 'icon-plus',
        ];
    }

    /**
     * Add Twig extensions
     *
     * @see Text extensions http://twig.sensiolabs.org/doc/extensions/text.html
     * @see Intl extensions http://twig.sensiolabs.org/doc/extensions/intl.html
     * @see Array extension http://twig.sensiolabs.org/doc/extensions/array.html
     * @see Time extension http://twig.sensiolabs.org/doc/extensions/date.html
     *
     * @return array
     */
    public function registerMarkupTags()
    {
        $twig = App::make('twig.environment');
        $filters = [];
        $functions = [];

        // add String Loader functions
        $stringLoader = new \Twig_Extension_StringLoader;
        $stringLoaderFunc = $stringLoader->getFunctions();
        $functions += [
            'template_from_string' => function($template) use ($twig, $stringLoaderFunc) {
                $callable = $stringLoaderFunc['0']->getCallable();
                return $callable($twig, $template);
            }
        ];

        // add Text extensions
        $textExtension = new \Twig_Extensions_Extension_Text;
        $textFilters = $textExtension->getFilters();
        $filters += [
            'truncate' => function($value, $length = 30, $preserve = false, $separator = '...') use ($twig, $textFilters) {
                $callable = $textFilters['0']->getCallable();
                return $callable($twig, $value, $length, $preserve, $separator);
            },
            'wordwrap' => function($value, $length = 80, $separator = "\n", $preserve = false) use ($twig, $textFilters) {
                $callable = $textFilters['1']->getCallable();
                return $callable($twig, $value, $length, $separator, $preserve);
            }
        ];

        // add Intl extensions if php5-intl installed
        if ( class_exists('IntlDateFormatter') ) {
            $intlExtension = new \Twig_Extensions_Extension_Intl;
            $intlFilters = $intlExtension->getFilters();

            $filters += [
                'localizeddate' => function($date, $dateFormat = 'medium', $timeFormat = 'medium', $locale = null, $timezone = null, $format = null) use ($twig, $intlFilters) {
                    $callable = $intlFilters['0']->getCallable();
                    return $callable($twig, $date, $dateFormat, $timeFormat, $locale, $timezone, $format);
                },
                'localizednumber' => function($number, $style = 'decimal', $type = 'default', $locale = null) use ($twig, $intlFilters) {
                    $callable = $intlFilters['1']->getCallable();
                    return $callable($twig, $number, $style, $type, $locale);
                },
                'localizedcurrency' => function($number, $currency = null, $locale = null) use ($twig, $intlFilters) {
                    $callable = $intlFilters['2']->getCallable();
                    return $callable($twig, $number, $currency, $locale);
                }
            ];
        }

        // add Array extensions
        $arrayExtension = new \Twig_Extensions_Extension_Array;
        $arrayFilters = $arrayExtension->getFilters();
        $filters += [
            'shuffle' => function($array) use ($twig, $arrayFilters) {
                $callable = $arrayFilters['0']->getCallable();
                return $callable($twig, $array);
            }
        ];

        // add Time extensions
        $timeExtension = new \Twig_Extensions_Extension_Date;
        $timeFilters = $timeExtension->getFilters();
        $filters += [
            'time_diff' => function($date, $now = null) use ($twig, $timeFilters) {
                $callable = $timeFilters['0']->getCallable();
                return $callable($twig, $date, $now);
            }
        ];

        // add PHP functions
        $filters += [
            'strftime' => function($time, $format = '%d.%m.%Y %H:%M:%S') {
                $timeObj = new Carbon($time);
                return strftime($format, $timeObj->getTimestamp());
            },
            'uppercase' => function($string) {
                return strtoupper($string);
            },
            'lowercase' => function($string) {
                return strtolower($string);
            },
            'ucfirst' => function($string) {
                return ucfirst($string);
            },
            'lcfirst' => function($string) {
                return lcfirst($string);
            },
            'ltrim' => function($string, $charlist = " \t\n\r\0\x0B") {
                return ltrim($string, $charlist);
            },
            'rtrim' => function($string, $charlist = " \t\n\r\0\x0B") {
                return rtrim($string, $charlist);
            },
            'str_repeat' => function($string, $multiplier = 1) {
                return str_repeat($string, $multiplier);
            },
            'plural' => function($string, $count = 2) {
                return str_plural($string, $count);
            }
        ];

        return [
            'filters' => $filters,
            'functions' => $functions
        ];
    }
}
