<?php namespace VojtaSvoboda\TwigExtensions\Tests;

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

    public function testStrftimeFunction()
    {
        $twig = $this->getTwig();

        $template = "{{ '2016-03-24 23:05' | strftime('%d.%m.%Y %H:%M:%S') }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), '24.03.2016 23:05:00');
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

    public function testStrReplaceFunction()
    {
        $twig = $this->getTwig();

        $template = "{{ 'test' | str_replace('test', 'tset') }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), 'tset');
    }

    public function testStripTagsFunction()
    {
        $twig = $this->getTwig();

        $template = "{{ '<p><b>text</b></p>' | strip_tags('<p>') }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), '<p>text</p>');
    }

    public function testSortByFieldFunction()
    {
        $twig = $this->getTwig();

        // sort by name
        $template = "{% set data = [{'name': 'David', 'age': 31}, {'name': 'John', 'age': 28}] %}";
        $template .= "{% for item in data | sortbyfield('name') %}{{ item.name }}{% endfor %}";
        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), 'DavidJohn');

        // sort by age
        $template = "{% set data = [{'name': 'David', 'age': 31}, {'name': 'John', 'age': 28}] %}";
        $template .= "{% for item in data | sortbyfield('age') %}{{ item.name }}{% endfor %}";
        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), 'JohnDavid');
    }

    public function testMailtoFilter()
    {
        $twig = $this->getTwig();

        // same as mailto(true, true)
        $template = "{{ 'vojtasvoboda.cz@gmail.com' | mailto }}";
        $twigTemplate = $twig->createTemplate($template);
        $this->assertStringNotContainsString('vojtasvoboda.cz@gmail.com', $twigTemplate->render([]));
        $this->assertStringContainsString('mailto:', $twigTemplate->render([]));

        // mailto(false, false) eg. without link and unprotected
        $template = "{{ 'vojtasvoboda.cz@gmail.com' | mailto(false, false) }}";
        $twigTemplate = $twig->createTemplate($template);
        $this->assertStringContainsString('vojtasvoboda.cz@gmail.com', $twigTemplate->render([]));
        $this->assertStringNotContainsString('mailto:', $twigTemplate->render([]));

        // mailto(true, false) eg. with link but unprotected
        $template = "{{ 'vojtasvoboda.cz@gmail.com' | mailto(true, false) }}";
        $twigTemplate = $twig->createTemplate($template);
        $this->assertStringContainsString('vojtasvoboda.cz@gmail.com', $twigTemplate->render([]));
        $this->assertStringContainsString('mailto', $twigTemplate->render([]));

        // mailto(false, true) eg. without link and protected
        $template = "{{ 'vojtasvoboda.cz@gmail.com' | mailto(false, true) }}";
        $twigTemplate = $twig->createTemplate($template);
        $this->assertStringNotContainsString('vojtasvoboda.cz@gmail.com', $twigTemplate->render([]));
        $this->assertStringNotContainsString('mailto', $twigTemplate->render([]));

        // mailto(true, true, 'Let me know') eg. with link, protected and with non-crypted text
        $template = "{{ 'vojtasvoboda.cz@gmail.com' | mailto(false, true, 'Let me know') }}";
        $twigTemplate = $twig->createTemplate($template);
        $this->assertStringNotContainsString('vojtasvoboda.cz@gmail.com', $twigTemplate->render([]));
        $this->assertStringNotContainsString('mailto', $twigTemplate->render([]));
        $this->assertStringContainsString('Let me know', $twigTemplate->render([]));
    }

    public function testVardumpFunction()
    {
        $twig = $this->getTwig();

        $template = "{{ var_dump('test') }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertStringContainsString('string(4) "test"', $twigTemplate->render([]));
    }

    public function testVardumpFilter()
    {
        $twig = $this->getTwig();

        $template = "{{ 'test' | var_dump }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertStringContainsString('string(4) "test"', $twigTemplate->render([]));
    }

    public function testSessionFunction()
    {
        $twig = $this->getTwig();

        session(['my.session.key' => 'test value']);

        $template = "{{ session('my.session.key') }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), 'test value');
    }

    public function testTransFunction()
    {
        $twig = $this->getTwig();
        Config::set('app.locale', 'en');

        $template = "{{ trans('validation.accepted') }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), 'The :attribute must be accepted.');
    }

    public function testTransFunctionWithParam()
    {
        $twig = $this->getTwig();
        Config::set('app.locale', 'en');

        $template = "{{ trans('backend::lang.access_log.hint', {'days': 60}) }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertStringContainsString('60 days', $twigTemplate->render([]));
    }
}
