<?php namespace VojtaSvoboda\TwigExtensions;

use App;
use Backend;
use Carbon\Carbon;
use Snilius\Twig\SortByFieldExtension;
use System\Classes\PluginBase;
use Twig_Extension_StringLoader;
use Twig_Extensions_Extension_Array;
use Twig_Extensions_Extension_Date;
use Twig_Extensions_Extension_Intl;
use Twig_Extensions_Extension_Text;
use VojtaSvoboda\TwigExtensions\Classes\TimeDiffTranslator;

/**
 * Twig Extensions Plugin.
 *
 * @see http://twig.sensiolabs.org/doc/extensions/index.html#extensions-install
 */
class Plugin extends PluginBase
{
    /**
     * @var boolean Determine if this plugin should have elevated privileges.
     */
    public $elevated = true;

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
        $this->app->singleton('time_diff_translator', function ($app) {
            $loader = $app->make('translation.loader');
            $locale = $app->config->get('app.locale');
            $translator = $app->make(TimeDiffTranslator::class, [$loader, $locale]);
            $translator->setFallback($app->config->get('app.fallback_locale'));

            return $translator;
        });
    }

    /**
     * Add Twig extensions.
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
        $filters = [];
        $functions = [];

        // init Twig
        $twig = $this->app->make('twig.environment');

        // add String Loader functions
        $functions += $this->getStringLoaderFunctions($twig);

        // add Config function
        $functions += $this->getConfigFunction();

        // add Env function
        $functions += $this->getEnvFunction();

        // add Session function
        $functions += $this->getSessionFunction();

        // add Trans function
        $functions += $this->getTransFunction();

        // add var_dump function
        $functions += $this->getVarDumpFunction();

        // add Text extensions
        $filters += $this->getTextFilters($twig);

        // add Intl extensions if php5-intl installed
        if (class_exists('IntlDateFormatter')) {
            $filters += $this->getLocalizedFilters($twig);
        }

        // add Array extensions
        $filters += $this->getArrayFilters();

        // add Time extensions
        $filters += $this->getTimeFilters($twig);

        // add Sort by Field extensions
        $filters += $this->getSortByField();

        // add Mail filters
        $filters += $this->getMailFilters();

        // add PHP functions
        $filters += $this->getPhpFunctions();

        // add File Version filter
        $filters += $this->getFileRevision();

        return [
            'filters'   => $filters,
            'functions' => $functions,
        ];
    }

    /**
     * Returns String Loader functions.
     *
     * @param \Twig_Environment $twig
     *
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
     * Returns Text filters.
     *
     * @param \Twig_Environment $twig
     *
     * @return array
     */
    private function getTextFilters($twig)
    {
        $textExtension = new Twig_Extensions_Extension_Text();
        $textFilters = $textExtension->getFilters();

        return [
            'truncate' => function ($value, $length = 30, $preserve = false, $separator = '...') use ($twig, $textFilters) {
                $callable = $textFilters[0]->getCallable();
                return $callable($twig, $value, $length, $preserve, $separator);
            },
            'wordwrap' => function ($value, $length = 80, $separator = "\n", $preserve = false) use ($twig, $textFilters) {
                $callable = $textFilters[1]->getCallable();
                return $callable($twig, $value, $length, $separator, $preserve);
            }
        ];
    }

    /**
     * Returns Intl filters.
     *
     * @param \Twig_Environment $twig
     *
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
     * Returns Array filters.
     *
     * @return array
     */
    private function getArrayFilters()
    {
        $arrayExtension = new Twig_Extensions_Extension_Array();
        $arrayFilters = $arrayExtension->getFilters();

        return [
            'shuffle' => function ($array) use ($arrayFilters) {
                $callable = $arrayFilters[0]->getCallable();
                return $callable($array);
            }
        ];
    }

    /**
     * Returns Date filters.
     *
     * @param \Twig_Environment $twig
     *
     * @return array
     */
    private function getTimeFilters($twig)
    {
        $translator = $this->app->make('time_diff_translator');
        $timeExtension = new Twig_Extensions_Extension_Date($translator);
        $timeFilters = $timeExtension->getFilters();

        return [
            'time_diff' => function ($date, $now = null) use ($twig, $timeFilters) {
                $callable = $timeFilters[0]->getCallable();
                return $callable($twig, $date, $now);
            }
        ];
    }

    /**
     * Returns Sort by Field filters.
     *
     * @return array
     */
    private function getSortByField()
    {
        $extension = new SortByFieldExtension();
        $filters = $extension->getFilters();

        return [
            'sortbyfield' => function ($array, $sort_by = null, $direction = 'asc') use ($filters) {
                $callable = $filters[0]->getCallable();
                return $callable($array, $sort_by, $direction);
            }
        ];
    }

    /**
     * Returns mail filters.
     *
     * @return array
     */
    private function getMailFilters()
    {
        return [
            'mailto' => function ($string, $link = true, $protected = true, $text = null, $class = "") {
                return $this->hideEmail($string, $link, $protected, $text, $class);
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
            'uppercase' => function ($string) {
                return mb_convert_case($string, MB_CASE_UPPER, "UTF-8");
            },
            'lowercase' => function ($string) {
                return mb_convert_case($string, MB_CASE_LOWER, "UTF-8");
            },
            'ucfirst' => function ($string) {
                return ucfirst($string);
            },
            'lcfirst' => function ($string) {
                return lcfirst($string);
            },
            'ltrim' => function ($string, $charlist = " \t\n\r\0\x0B") {
                return ltrim($string, $charlist);
            },
            'rtrim' => function ($string, $charlist = " \t\n\r\0\x0B") {
                return rtrim($string, $charlist);
            },
            'str_repeat' => function ($string, $multiplier = 1) {
                return str_repeat($string, $multiplier);
            },
            'plural' => function ($string, $count = 2) {
                return str_plural($string, $count);
            },
            'strpad' => function ($string, $pad_length, $pad_string = ' ') {
                return str_pad($string, $pad_length, $pad_string, $pad_type = STR_PAD_BOTH);
            },
            'leftpad' => function ($string, $pad_length, $pad_string = ' ') {
                return str_pad($string, $pad_length, $pad_string, $pad_type = STR_PAD_LEFT);
            },
            'rightpad' => function ($string, $pad_length, $pad_string = ' ') {
                return str_pad($string, $pad_length, $pad_string, $pad_type = STR_PAD_RIGHT);
            },
            'rtl' => function ($string) {
                return strrev($string);
            },
            'str_replace' => function ($string, $search, $replace) {
                return str_replace($search, $replace, $string);
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
     * Works like the config() helper function.
     *
     * @return array
     */
    private function getConfigFunction()
    {
        return [
            'config' => function ($key = null, $default = null) {
                return config($key, $default);
            },
        ];
    }

    /**
     * Works like the env() helper function.
     *
     * @return array
     */
    private function getEnvFunction()
    {
        return [
            'env' => function ($key, $default = null) {
                return env($key, $default);
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
     * Create protected link with mailto:
     *
     * @param string $email Email to render.
     * @param bool $link If email should be rendered as link.
     * @param bool $protected If email should be protected.
     * @param string $text Link text. Render email by default.
     *
     * @see http://www.maurits.vdschee.nl/php_hide_email/
     *
     * @return string
     */
    private function hideEmail($email, $link = true, $protected = true, $text = null, $class = "")
    {
        // email link text
        $linkText = $email;
        if ($text !== null) {
            $linkText = $text;
        }

        // if we want just unprotected link
        if (!$protected) {
            return $link ? '<a href="mailto:' . $email . '">' . $linkText . '</a>' : $linkText;
        }

        // turn on protection
        $character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
        $key = str_shuffle($character_set);
        $cipher_text = '';
        $id = 'e' . rand(1, 999999999);
        for ($i = 0; $i < strlen($email); $i += 1) {
            $cipher_text .= $key[strpos($character_set, $email[$i])];
        }
        $script = 'var a="' . $key . '";var b=a.split("").sort().join("");var c="' . $cipher_text . '";var d=""; var cl="'.$class.'";';
        $script .= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
        $script .= 'var y = d;';
        if ($text !== null) {
            $script .= 'var y = "'.$text.'";';
        }
        if ($link) {
            $script .= 'document.getElementById("' . $id . '").innerHTML="<a class=\""+cl+"\" href=\\"mailto:"+d+"\\">"+y+"</a>"';
        } else {
            $script .= 'document.getElementById("' . $id . '").innerHTML=y';
        }
        $script = "eval(\"" . str_replace(array("\\", '"'), array("\\\\", '\"'), $script) . "\")";
        $script = '<script type="text/javascript">/*<![CDATA[*/' . $script . '/*]]>*/</script>';

        return '<span id="' . $id . '">[javascript protected email address]</span>' . $script;
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
