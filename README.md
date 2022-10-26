# Twig extensions

[![Build Status](https://travis-ci.org/vojtasvoboda/oc-twigextensions-plugin.svg?branch=master)](https://travis-ci.org/vojtasvoboda/oc-twigextensions-plugin)
[![Codacy](https://img.shields.io/codacy/c6b23b6527bd407092763cace324ef4a.svg)](https://www.codacy.com/app/vojtasvoboda/oc-twigextensions-plugin)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/g/vojtasvoboda/oc-twigextensions-plugin.svg)](https://scrutinizer-ci.com/g/vojtasvoboda/oc-twigextensions-plugin/?branch=master)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/vojtasvoboda/oc-twigextensions-plugin/blob/master/LICENSE)

Twig extensions plugin for OctoberCMS adds new filter and functions to your templates. No other plugin dependencies.

Tested with the latest stable OctoberCMS build 420 (with Laravel 5.5). For Laravel 5.4 use special branch `laravel54`.

## Installation

Install plugin from CMS backend or by Composer:

```
composer require vojtasvoboda/oc-twigextensions-plugin:dev-master
```

Than you can use newly added filters/functions at your templates:

```
<h1 class="heading">{{ article.heading | uppercase }}</h1>
<p class="created">
	Posted at {{ article.date | strftime('%d.%m.%Y %H:%M:%S') }}
</p>
<p class="perex">
	{{ article.perex | str_limit(80) }}
</p>
```

## Available functions

[session](https://laravel.com/docs/5.0/session#session-usage), [trans](https://octobercms.com/docs/plugin/localization), [var_dump](http://php.net/manual/en/function.var-dump.php), [template\_from\_string](http://twig.sensiolabs.org/doc/functions/template_from_string.html)

### session

Function move the functionality of the Laravel `session()` helper function to Twig.

```
{{ session('my.session.key') }}
```
The example would output the value currently stored in `my.session.key`.
See [more about the Laravel session helper function here](https://laravel.com/docs/5.0/session#session-usage).

### trans

Function move the functionality of the Laravel `trans()` helper function to Twig.

```
{{ trans('acme.blog::lang.app.name') }}
```
The example would output a value stored in a localization file of an imaginary blog plugin.
See [more about localization in October CMS here](https://octobercms.com/docs/plugin/localization).

### var_dump

Dumps information about a variable. Can be also used as filter.

```
<pre>{{ var_dump(users) }}</pre>
```

### template\_from\_string

Function loads a template from a string.

```
{% set name = 'John' %}
{{ include(template_from_string("Hello {{ name }}")) }}
{{ include(template_from_string("Hurry up it is: {{ "now"|date("m/d/Y") }}")) }}
```

## Available filters

strftime, ltrim, rtrim, str_replace, strip_tags, rtl, time\_diff,
localizeddate, localizednumber, localizedcurrency, var\_dump, revision, sortbyfield

### strftime

Format a local time/date according to locale settings.

```
Posted at {{ article.date | strftime('%d.%m.%Y %H:%M:%S') }}
```

The example would output *Posted at 04.01.2016 22:57:42*. See [more format parameters](http://php.net/manual/en/function.strftime.php#refsect1-function.strftime-parameters).

### ltrim

Strip whitespace (or other characters) from the beginning of a string.

```
Hello I'm {{ ' jack' | ltrim }}
```

The example would output *Hello I'm jack* without whitespaces from the start.

### rtrim

Strip whitespace (or other characters) from the end of a string.

```
Hello I'm {{ 'jack ' | rtrim }}
```

The example would output *Hello I'm jack* without whitespaces from the end.

### str_replace

Replace all occurrences of the search string with the replacement string.

```
{{ 'Alice' | str_replace('Alice', 'Bob') }}
```

This would return:

```
Bob
```

### strip_tags

Strip HTML and PHP tags from a string. In first parameter you can specify allowable tags.

```
{{ '<p><b>Text</b></p>' | strip_tags('<p>') }}
```

This would return:

```
<p>Text</p>
```

### localizeddate

Use the localizeddate filter to format dates into a localized string representating the date. Note that **php5-intl extension**/**php7-intl extension** has to be installed!

```
{{ post.published_at | localizeddate('medium', 'none', locale) }}
```

The localizeddate filter accepts strings (it must be in a format supported by the strtotime function), DateTime instances, or Unix timestamps.

#### Arguments
- date_format: The date format. Choose one of these formats:
    - 'none': IntlDateFormatter::NONE
    - 'short': IntlDateFormatter::SHORT
    - 'medium': IntlDateFormatter::MEDIUM
    - 'long': IntlDateFormatter::LONG
    - 'full': IntlDateFormatter::FULL
- time_format: The time format. Same formats possible as above.
- locale: The locale used for the format. If NULL is given, Twig will use Locale::getDefault()
- timezone: The date timezone
- format: Optional pattern to use when formatting or parsing. Possible patterns are documented in the ICU user guide.

### localizednumber

Use the localizednumber filter to format numbers into a localized string representating the number. Note that **php5-intl extension** has to be installed!

```
{{ product.quantity | localizednumber }}
```

Internally, Twig uses the PHP NumberFormatter::create() function for the number.

#### Arguments

- style: Optional date format (default: 'decimal'). Choose one of these formats:
    - 'decimal': NumberFormatter::DECIMAL
    - 'currency': NumberFormatter::CURRENCY
    - 'percent': NumberFormatter::PERCENT
    - 'scientific': NumberFormatter::SCIENTIFIC
    - 'spellout': NumberFormatter::SPELLOUT
    - 'ordinal': NumberFormatter::ORDINAL
    - 'duration': NumberFormatter::DURATION
- type: Optional formatting type to use (default: 'default'). Choose one of these types:
    - 'default': NumberFormatter::TYPE_DEFAULT
    - 'int32': NumberFormatter::TYPE_INT32
    - 'int64': NumberFormatter::TYPE_INT64
    - 'double': NumberFormatter::TYPE_DOUBLE
    - 'currency': NumberFormatter::TYPE_CURRENCY
- locale: The locale used for the format. If NULL is given, Twig will use Locale::getDefault()

### localizedcurrency

Use the localizedcurrency filter to format a currency value into a localized string. Note that **php5-intl extension** has to be installed!

```
{{ product.price | localizedcurrency('EUR') }}
```

#### Arguments

- currency: The 3-letter ISO 4217 currency code indicating the currency to use.
- locale: The locale used for the format. If NULL is given, Twig will use Locale::getDefault()

### var_dump

Dumps information about a variable.

```
<pre>{{ users | var_dump }}</pre>
```

### revision
 
Force the browser to reload cached modified/updated asset files.
You can provide a format parameter so that the prepended timestamp get converted accordingly to the PHP date() function.

#### usage
```
<img src="{{ 'assets/images/image_file.jpg' | theme | revision("m.d.y.H.i.s") }}" alt="an image" />
```

Will return something like
```
<img src="https://www.example.com/themes/my-theme/assets/image_file.png?12.03.16.04.52.38" alt="An image" />
```

See: https://github.com/vojtasvoboda/oc-twigextensions-plugin/issues/25  

https://stackoverflow.com/questions/32414/how-can-i-force-clients-to-refresh-javascript-files  

http://php.net/manual/en/function.date.php  

## Contributing

- [ ] Fix time_diff unit test, which pass at local machine, but fails at TravisCI.
- [ ] Convert PHP functions and custom code to the Twig_Extension classes.
- [ ] Create Twig_Extension loader and load all extensions and filters as Twig_Extension automatically from config.
- [ ] New filters *ga* and *gtm* for adding GA or GTM code (Heap Analytics) - {{ 'UA-1234567' | ga }}.
- [ ] Add [cache extension](https://github.com/vojtasvoboda/oc-twigextensions-plugin/issues/11).

**Feel free to send pullrequest!** Please, send Pull Request to master branch.

## License

Twig extensions plugin is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT) same as OctoberCMS platform.
