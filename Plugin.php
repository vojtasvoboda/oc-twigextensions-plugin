<?php namespace VojtaSvoboda\TwigExtensions;

use Carbon\Carbon;
use System\Classes\PluginBase;
use Twig\Extension\StringLoaderExtension;
use Twig\Extra\Intl\IntlExtension;

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
            'name' => 'Twig Extensions',
            'description' => 'Add more Twig filters to your templates.',
            'author' => 'Vojta Svoboda',
            'icon' => 'icon-plus',
            'homepage' => 'https://github.com/vojtasvoboda/oc-twigextensions-plugin',
        ];
    }

    /**
     * Add Twig extensions.
     */
    public function registerMarkupTags(): array
    {
        $filters = [];
        $functions = [];

        // add String Loader functions
        $functions += $this->getStringLoaderFunctions();

        // register Intl functions and filters
        if (class_exists('IntlDateFormatter')) {
            $functions += $this->getIntlFunctions();
            $filters += $this->getIntlFilters();
        }

        // add Session function
        $functions += $this->getSessionFunction();

        // add Trans function
        $functions += $this->getTransFunction();

        // add var_dump function
        $functions += $this->getVarDumpFunction();

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
     */
    private function getStringLoaderFunctions(): array
    {
        $stringLoader = new StringLoaderExtension();

        $functions = [];
        foreach ($stringLoader->getFunctions() as $function) {
            $functions[$function->getName()] = $function->getCallable();
        }

        return $functions;
    }

    /**
     * Returns Localized functions.
     */
    private function getIntlFunctions(): array
    {
        $intlExtension = new IntlExtension();

        $functions = [];
        foreach ($intlExtension->getFunctions() as $function) {
            $functions[$function->getName()] = $function->getCallable();
        }

        return $functions;
    }

    /**
     * Returns Localized filters.
     */
    private function getIntlFilters(): array
    {
        $intlExtension = new IntlExtension();

        $filters = [];
        foreach ($intlExtension->getFilters() as $filter) {
            $filters[$filter->getName()] = $filter->getCallable();
        }

        return $filters;
    }

    /**
     * Returns plain PHP functions.
     */
    private function getPhpFunctions(): array
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
            'wordwrap' => function (string $string, int $width = 75, string $break = "\n", bool $cut_long_words = false) {
                return wordwrap($string, $width, $break, $cut_long_words);
            }
        ];
    }

    /**
     * Works like the session() helper function.
     */
    private function getSessionFunction(): array
    {
        return [
            'session' => function ($key = null) {
                return session($key);
            },
        ];
    }

    /**
     * Works like the trans() helper function.
     */
    private function getTransFunction(): array
    {
        return [
            'trans' => function ($key = null, $parameters = []) {
                return trans($key, $parameters);
            },
        ];
    }

    /**
     * Dumps information about a variable.
     */
    private function getVarDumpFunction(): array
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
