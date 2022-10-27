<?php namespace VojtaSvoboda\TwigExtensions;

use App;
use Backend;
use Carbon\Carbon;
use System\Classes\PluginBase;
use Twig_Extension_StringLoader;
use Twig_Extensions_Extension_Intl;

/**
 * Twig Extensions Plugin.
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
            'homepage'    => 'https://github.com/vojtasvoboda/oc-twigextensions-plugin',
        ];
    }

    public function boot()
    {
        // ...
    }

    /**
     * Add Twig extensions.
     *
     * @see Intl extensions http://twig.sensiolabs.org/doc/extensions/intl.html
     * @return array
     */
    public function registerMarkupTags()
    {
        $filters = [];
        $functions = [];

        // init Twig
        $twig = $this->app->make('twig.environment');

        // add String Loader functions
        $functions += $this->getStringLoaderFunctions($twig);

        // add Session function
        $functions += $this->getSessionFunction();

        // add Trans function
        $functions += $this->getTransFunction();

        // add var_dump function
        $functions += $this->getVarDumpFunction();

        // add Intl extensions if php5-intl installed
        if (class_exists('IntlDateFormatter')) {
            $filters += $this->getLocalizedFilters($twig);
        }

        // add PHP functions
        $filters += $this->getPhpFunctions();

        // add File Version filter
        $filters += $this->getFileRevision();

        return [
            'filters' => $filters,
            'functions' => $functions,
        ];
    }

    /**
     * Returns String Loader functions.
     *
     * @param \Twig_Environment $twig
     * @return array
     */
    private function getStringLoaderFunctions($twig)
    {
        $stringLoader = new Twig_Extension_StringLoader();
        $stringLoaderFunc = $stringLoader->getFunctions();

        return [
            'template_from_string' => function ($template) use ($twig, $stringLoaderFunc) {
                $callable = $stringLoaderFunc[0]->getCallable();
                return $callable($twig, $template);
            }
        ];
    }

    /**
     * Returns Intl filters.
     *
     * @param \Twig_Environment $twig
     * @return array
     */
    private function getLocalizedFilters($twig)
    {
        $intlExtension = new Twig_Extensions_Extension_Intl();
        $intlFilters = $intlExtension->getFilters();

        return [
            'localizeddate' => function ($date, $dateFormat = 'medium', $timeFormat = 'medium', $locale = null, $timezone = null, $format = null) use ($twig, $intlFilters) {
                $callable = $intlFilters[0]->getCallable();
                return $callable($twig, $date, $dateFormat, $timeFormat, $locale, $timezone, $format);
            },
            'localizednumber' => function ($number, $style = 'decimal', $type = 'default', $locale = null) use ($twig, $intlFilters) {
                $callable = $intlFilters[1]->getCallable();
                return $callable($number, $style, $type, $locale);
            },
            'localizedcurrency' => function ($number, $currency = null, $locale = null) use ($twig, $intlFilters) {
                $callable = $intlFilters[2]->getCallable();
                return $callable($number, $currency, $locale);
            }
        ];
    }

    /**
     * Returns plain PHP functions.
     *
     * @return array
     */
    private function getPhpFunctions()
    {
        return [
            'strftime' => function ($time, $format = '%d.%m.%Y %H:%M:%S') {
                $timeObj = new Carbon($time);
                return strftime($format, $timeObj->getTimestamp());
            },
            'ltrim' => function ($string, $charlist = " \t\n\r\0\x0B") {
                return ltrim($string, $charlist);
            },
            'rtrim' => function ($string, $charlist = " \t\n\r\0\x0B") {
                return rtrim($string, $charlist);
            },
            'strip_tags' => function ($string, $allow = '') {
                return strip_tags($string, $allow);
            },
            'var_dump' => function ($expression) {
                ob_start();
                var_dump($expression);
                $result = ob_get_clean();

                return $result;
            },
        ];
    }

    /**
     * Works like the session() helper function.
     *
     * @return array
     */
    private function getSessionFunction()
    {
        return [
            'session' => function ($key = null) {
                return session($key);
            },
        ];
    }

    /**
     * Works like the trans() helper function.
     *
     * @return array
     */
    private function getTransFunction()
    {
        return [
            'trans' => function ($key = null, $parameters = []) {
                return trans($key, $parameters);
            },
        ];
    }

    /**
     * Dumps information about a variable.
     *
     * @return array
     */
    private function getVarDumpFunction()
    {
        return [
            'var_dump' => function ($expression) {
                ob_start();
                var_dump($expression);
                $result = ob_get_clean();

                return $result;
            },
        ];
    }

    /**
     * Appends this pattern: ? . {last modified date}
     * to an assets filename to force browser to reload
     * cached modified file.
     *
     * See: https://github.com/vojtasvoboda/oc-twigextensions-plugin/issues/25
     *
     * @return array
     */
    private function getFileRevision()
    {
        return [
            'revision' => function ($filename, $format = null) {
                // Remove http/web address from the file name if there is one to load it locally
                $prefix = url('/');
                $filename_ = trim(preg_replace('/^' . preg_quote($prefix, '/') . '/', '', $filename), '/');
                if (file_exists($filename_)) {
                    $timestamp = filemtime($filename_);
                    $prepend = ($format) ? date($format, $timestamp) : $timestamp;

                    return $filename . "?" . $prepend;
                }

                return $filename;
            },
        ];
    }
}
