# Twig extensions

[![Build Status](https://travis-ci.org/vojtasvoboda/oc-twigextensions-plugin.svg?branch=master)](https://travis-ci.org/vojtasvoboda/oc-twigextensions-plugin)
[![Codacy](https://img.shields.io/codacy/c6b23b6527bd407092763cace324ef4a.svg)](https://www.codacy.com/app/vojtasvoboda/oc-twigextensions-plugin)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/g/vojtasvoboda/oc-twigextensions-plugin.svg)](https://scrutinizer-ci.com/g/vojtasvoboda/oc-twigextensions-plugin/?branch=master)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/vojtasvoboda/oc-twigextensions-plugin/blob/master/LICENSE)

Twig extensions plugin for OctoberCMS adds new filter and functions to your templates. No other plugin dependencies. Tested with the latest stable OctoberCMS 3.1.18 on PHP 8.0.

## Versions

There are two versions of this plugin. For October v1/v2 use special branch 1.x. For October 3.1+ use master branch. For old Laravel 5.4 October versions use special branch `laravel54`.

For migrating to October 3.1 you can use special [UPGRADING.md](UPGRADING.md) guide.

## Installation

Install plugin from CMS backend or by Composer:

```
composer require vojtasvoboda/oc-twigextensions-plugin
```

Than you can use newly added filters/functions at your templates:

```
<h1 class="heading">{{ article.heading | ltrim }}</h1>
<p class="created">
	Posted at {{ article.date | strftime('%d.%m.%Y %H:%M:%S') }}
</p>
<p class="perex">
	{{ article.perex | wordwrap(80) }}
</p>
```

## Available functions

[session](https://laravel.com/docs/5.0/session#session-usage), [trans](https://octobercms.com/docs/plugin/localization), [var_dump](http://php.net/manual/en/function.var-dump.php), [template\_from\_string](https://twig.symfony.com/doc/3.x/functions/template_from_string.html)

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

strftime, ltrim, rtrim, strip_tags, var\_dump, wordwrap, revision

Filters and function from twig/intl-extra package (see https://github.com/twigphp/intl-extra):
- internationalized names filters: country_name, currency_name, currency_symbol, language_name, locale_name, timezone_name
- localized formatters filters: format_currency, format_number, format_*_number, format_datetime, format_date, format_time
- function: country_timezones

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

### strip_tags

Strip HTML and PHP tags from a string. In first parameter you can specify allowable tags.

```
{{ '<p><b>Text</b></p>' | strip_tags('<p>') }}
```

This would return:

```
<p>Text</p>
```

### var_dump

Dumps information about a variable.

```
<pre>{{ users | var_dump }}</pre>
```

### wordwrap

Use the wordwrap filter to split your text in lines with equal length.

```
{{ "Lorem ipsum dolor sit amet, consectetur adipiscing" | wordwrap(10) }}
```
This example would print:

```
Lorem ipsu  
m dolor si  
t amet, co  
nsectetur  
adipiscing  
```

The default separator is "\n", but you can easily change that by providing one:

```
{{ "Lorem ipsum dolor sit amet, consectetur adipiscing" | wordwrap(10, "zz\n") }}
```

This would result in:

```
Lorem ipsuzz  
m dolor sizz  
t amet, cozz  
nsectetur zz  
adipiscing  
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

## Removed functions

Functions used in version 1.x for October v1/v2 and removed from this version:

- config() - it's native function now
- env() - it's native function now

## Removed filters

Filters used in version 1.x for October v1/v2 and removed from this version:

- uppercase - use str_upper
- lowercase - use str_lower
- ucfirst - use str_ucfirst
- lcfirst - use str_lcfirst
- str_repeat - it's native filter now
- plural - use str_plural
- truncate - use str_limit
- strpad - use str_pad_both
- str_replace - it's native filter now
- strip_tags - use html_strip
- leftpad - use str_pad_left
- rightpad - use str_pad_right
- rtl - use str_reverse
- shuffle - use `collect(songs).shuffle()`
- time_diff - use `carbon(post.published_at).diffForHumans()`
- localizeddate - use format_date
- localizednumber - use format_number
- localizedcurrency - use format_currency
- mailto - use html_mailto
- var_dump - use dump function
- sortbyfield - use `collect(data).sortBy('age')`

For more info see [UPGRADING.md](UPGRADING.md) guide.

## Contributing

- [ ] New filters *ga* and *gtm* for adding GA or GTM code (Heap Analytics) - {{ 'UA-1234567' | ga }}.
- [ ] Add [cache extension](https://github.com/vojtasvoboda/oc-twigextensions-plugin/issues/11).

**Feel free to send pullrequest!** Please, send Pull Request to master branch.

## License

Twig extensions plugin is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT) same as OctoberCMS platform.
