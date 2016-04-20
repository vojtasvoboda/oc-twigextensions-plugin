<?php

namespace VojtaSvoboda\TwigExtensions\Tests;

use App;
use Carbon\Carbon;
use Config;
use PluginTestCase;
use Twig_Environment;

require_once __DIR__ . '/../vendor/autoload.php';

class PluginTest extends PluginTestCase
{
    /**
     * Return Twig environment
     * 
     * @return Twig_Environment
     */
    private function getTwig()
    {
        return App::make('twig.environment');
    }

    public function testTemplateFromStringFunction()
    {
        $twig = $this->getTwig();

        $template = "{% set name = 'John' %}";
        $template .= '{{ include(template_from_string("Hello {{ name }}")) }}';

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), 'Hello John');
    }

    public function testTruncateFilterForFive()
    {
        $twig = $this->getTwig();

        $template = "{{ 'Gordon Freeman' | truncate(5) }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), 'Gordo...');
    }

    public function testTruncateFilterForDefault()
    {
        $twig = $this->getTwig();

        $template = "{{ 'Lorem ipsum dolor sit amet, consectetur adipiscing elit' | truncate }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), 'Lorem ipsum dolor sit amet, co...');
    }

    public function testTruncateFilterWithSeparator()
    {
        $twig = $this->getTwig();

        $template = "{{ 'Gordon Freeman' | truncate(5, false, '-') }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), 'Gordo-');
    }

    public function testWordWrapFilter()
    {
        $twig = $this->getTwig();

        $template = "{{ 'Lorem ipsum dolor sit amet, consectetur adipiscing elit' | wordwrap(10) }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), "Lorem ipsu\nm dolor si\nt amet, co\nnsectetur \nadipiscing\n elit");
    }

    public function testShuffleFilter()
    {
        $twig = $this->getTwig();

        $template = "{{ [1, 2, 3] | shuffle }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->setExpectedException('Twig_Error_Runtime', 'Array to string conversion');
        $twigTemplate->render([]);
    }

    public function testShuffleFilterForeach()
    {
        $twig = $this->getTwig();

        $template = "{% for i in [1, 2, 3] | shuffle %}{{ i }}{% endfor %}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals(strlen($twigTemplate->render([])), 3);
    }

    public function testTimeDiffFunction()
    {
        $twig = $this->getTwig();

        $now = Carbon::now()->subMinute();
        $template = "{{ '" . $now->format('Y-m-d H:i:s') . "' | time_diff }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), '1 minute ago');
    }

    public function testStrftimeFunction()
    {
        $twig = $this->getTwig();

        $template = "{{ '2016-03-24 23:05' | strftime('%d.%m.%Y %H:%M:%S') }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), '24.03.2016 23:05:00');
    }

    public function testUppercaseFunction()
    {
        $twig = $this->getTwig();

        $template = "{{ 'Jack' | uppercase }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), 'JACK');
    }

    public function testLowercaseFunction()
    {
        $twig = $this->getTwig();

        $template = "{{ 'JACK' | lowercase }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), 'jack');
    }

    public function testUcfirstFunction()
    {
        $twig = $this->getTwig();

        $template = "{{ 'jack' | ucfirst }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), 'Jack');
    }

    public function testLcfirstFunction()
    {
        $twig = $this->getTwig();

        $template = "{{ 'JACK' | lcfirst }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), 'jACK');
    }

    public function testLtrimFunction()
    {
        $twig = $this->getTwig();

        $template = "{{ ' jack' | ltrim }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), 'jack');
    }

    public function testRtrimFunction()
    {
        $twig = $this->getTwig();

        $template = "{{ 'jack ' | rtrim }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), 'jack');
    }

    public function testStrRepeatFunction()
    {
        $twig = $this->getTwig();

        $template = "{{ ' best' | str_repeat(3) }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), ' best best best');
    }

    public function testPluralFunction()
    {
        $twig = $this->getTwig();

        $template = "{{ 'mail' | plural(count) }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), 'mails');
    }

    public function testStrpadFunction()
    {
        $twig = $this->getTwig();

        $template = "{{ 'test' | strpad(10) }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), '   test   ');
    }

    public function testLeftpadFunction()
    {
        $twig = $this->getTwig();

        $template = "{{ 'test' | leftpad(7) }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), '   test');
    }

    public function testRightpadFunction()
    {
        $twig = $this->getTwig();

        $template = "{{ 'test' | rightpad(7, 'o') }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), 'testooo');
    }

    public function testConfigFunction()
    {
        $twig = $this->getTwig();

        $key = 'app.custom.key';
        $value = 'test value';
        Config::set($key, $value);
        $template = "{{ config('" . $key . "') }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), $value);
    }
}
