<?php namespace VojtaSvoboda\TwigExtensions\Tests;

use App;
use Config;
use Event;
use PluginTestCase;
use System\Classes\MarkupManager;
use Twig_Environment;
use VojtaSvoboda\TwigExtensions\Plugin;

class PluginTest extends PluginTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // register filters and functions
        $plugin = new Plugin($this->app);
        $plugin->boot();
        $extensions = $plugin->registerMarkupTags();
        $manager = MarkupManager::instance();
        $manager->registerFilters($extensions['filters']);
        $manager->registerFunctions($extensions['functions']);

        // instantiate $twig to force firing system.extendTwig
        $twig = $this->app->make('twig.environment');
        Event::fire('system.extendTwig', [$twig]);
    }

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

    public function testStripTagsFunction()
    {
        $twig = $this->getTwig();

        $template = "{{ '<p><b>text</b></p>' | strip_tags('<p>') }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), '&lt;p&gt;text&lt;/p&gt;');
    }

    public function testWordWrapFilter()
    {
        $twig = $this->getTwig();

        $template = "{{ 'Lorem ipsum dolor sit amet, consectetur adipiscing elit' | wordwrap(10) }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertEquals($twigTemplate->render([]), "Lorem\nipsum\ndolor sit\namet,\nconsectetur\nadipiscing\nelit");
    }

    public function testVardumpFunction()
    {
        $twig = $this->getTwig();

        $template = "{{ var_dump('test') }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertStringContainsString('string(4) &quot;test&quot;', $twigTemplate->render([]));
    }

    public function testVardumpFilter()
    {
        $twig = $this->getTwig();

        $template = "{{ 'test' | var_dump }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertStringContainsString('string(4) &quot;test&quot;', $twigTemplate->render([]));
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
        $this->assertEquals($twigTemplate->render([]), 'The :attribute field must be accepted.');
    }

    public function testTransFunctionWithParam()
    {
        $twig = $this->getTwig();
        Config::set('app.locale', 'en');

        $template = "{{ trans('backend::lang.partial.not_found_name', {'name': 'test'}) }}";

        $twigTemplate = $twig->createTemplate($template);
        $this->assertStringContainsString("The partial &#039;test&#039; is not found.", $twigTemplate->render([]));
    }
}
