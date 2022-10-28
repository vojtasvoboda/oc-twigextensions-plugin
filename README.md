# Twig extensions

[![Build Status](https://travis-ci.org/vojtasvoboda/oc-twigextensions-plugin.svg?branch=master)](https://travis-ci.org/vojtasvoboda/oc-twigextensions-plugin)
[![Codacy](https://img.shields.io/codacy/c6b23b6527bd407092763cace324ef4a.svg)](https://www.codacy.com/app/vojtasvoboda/oc-twigextensions-plugin)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/g/vojtasvoboda/oc-twigextensions-plugin.svg)](https://scrutinizer-ci.com/g/vojtasvoboda/oc-twigextensions-plugin/?branch=master)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/vojtasvoboda/oc-twigextensions-plugin/blob/master/LICENSE)

Twig extensions plugin for OctoberCMS adds new filter and functions to your templates. No other plugin dependencies. Tested with the latest stable OctoberCMS 3.1.18 on PHP 8.0.

## Versions

There are two versions of this plugin - 1.x and 2.x. For older October 1.0.x, 1.1.x or 2.x.x use special [branch 1.x](https://github.com/vojtasvoboda/oc-twigextensions-plugin/tree/1.x). For October 3.1+ use master branch. 
For old Laravel 5.4 October's versions use special branch `laravel54`.

For migrating between plugin's version 1 and version 2 you can use special [UPGRADE.md](UPGRADE.md) guide.

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

[session](https://laravel.com/docs/5.0/session#session-usage), [trans](https://octobercms.com/docs/plugin/localization), [var_dump](http://php.net/manual/en/function.var-dump.php), [template\_from\_string](https://twig.symfony.com/doc/3.x/functions/template_from_string.html), [country_timezones](https://twig.symfony.com/doc/3.x/functions/country_timezones.html)

### session

Function moves the functionality of the Laravel `session()` helper function to Twig.

```
{{ session('my.session.key') }}
```
The example would output the value currently stored in `my.session.key`.
See [more about the Laravel session helper function here](https://laravel.com/docs/5.0/session#session-usage).

You can also use OctoberCMS function: `{{ this.session.get('my.session.key') }}`, but it's little bit longer :-)

### trans

Function moves the functionality of the Laravel `trans()` helper function to Twig.

```
{{ trans('acme.blog::lang.app.name') }}
```
The example would output a value stored in a localization file of an imaginary blog plugin.
See [more about localization in October CMS here](https://octobercms.com/docs/plugin/localization).

You can also use trans filter: `{{ 'acme.blog::lang.app.name'|trans }}`.

### var_dump

Dumps information about a variable. Can be also used as a filter.

```
<pre>{{ var_dump(users) }}</pre>
```

You can also use {{ dump(users) }} function to dump information about a variable. Properties are "clickable" to expand.

### template\_from\_string

Function loads a template from a string.

```
{% set name = 'John' %}
{{ include(template_from_string("Hello {{ name }}")) }}
{{ include(template_from_string("Hurry up it is: {{ "now"|date("m/d/Y") }}")) }}
```

### country_timezones

The country_timezones function returns the names of the timezones associated with a given country code:

```twig
{# Europe/Paris #}
{{ country_timezones('FR')|join(', ') }}
```

## Available filters

- PHP functions: strftime, ltrim, rtrim, var\_dump, wordwrap
- custom functions: revision
- internationalized names filters: country_name, currency_name, currency_symbol, language_name, locale_name, timezone_name
- localized formatters filters: format_currency, format_number, format_*_number, format_datetime, format_date, format_time

### strftime

Format a local time/date according to locale settings.

```
Posted at {{ article.date | strftime('%d.%m.%Y %H:%M:%S') }}
```

The example would output *Posted at 04.01.2016 22:57:42*. See [more format parameters](http://php.net/manual/en/function.strftime.php#refsect1-function.strftime-parameters).

You can also use `{{ carbon(article.date).formatLocalized('%d.%m.%Y %H:%M:%S') }}`.

### ltrim

Strip whitespace (or other characters) from the beginning of a string.

```
Hello I'm {{ ' jack' | ltrim }}
```

The example would output *Hello I'm jack* without whitespaces from the start.

You can also use `{{ '  I like Twig.  '|trim(side='left') }}` native Twig filter.

### rtrim

Strip whitespace (or other characters) from the end of a string.

```
Hello I'm {{ 'jack ' | rtrim }}
```

The example would output *Hello I'm jack* without whitespaces from the end.

You can also use `{{ '  I like Twig.  '|trim(side='right') }}` native Twig filter.

### var_dump

Dumps information about a variable.

```
<pre>{{ users | var_dump }}</pre>
```

You can also use `<pre>{{ var_dump(users) }}</pre>` or `{{ dump(users) }}` functions.

### wordwrap

Use the wordwrap filter to split your text into lines with equal length.

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
You can provide a format parameter so that the prepended timestamp gets converted accordingly to the PHP date() function.

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

### country_name

The country_name filter returns the country name given its ISO-3166 two-letter code:

```twig
{# France #}
{{ 'FR'|country_name }}
```

By default, the filter uses the current locale. You can pass it explicitly:

```twig
{# États-Unis #}
{{ 'US'|country_name('fr') }}
```

### currency_name

The currency_name filter returns the currency name given its three-letter code:

```twig
{# Euro #}
{{ 'EUR'|currency_name }}

{# Japanese Yen #}
{{ 'JPY'|currency_name }}
```

By default, the filter uses the current locale. You can pass it explicitly:

```twig
{# yen japonais #}
{{ 'JPY'|currency_name('fr_FR') }}
```

### currency_symbol

The currency_symbol filter returns the currency symbol given its three-letter code:

```twig
{# € #}
{{ 'EUR'|currency_symbol }}

{# ¥ #}
{{ 'JPY'|currency_symbol }}
```

By default, the filter uses the current locale. You can pass it explicitly:

```twig
{# ¥ #}
{{ 'JPY'|currency_symbol('fr') }}
```

### language_name

The language_name filter returns the language name given its two-letter code:

```twig
{# German #}
{{ 'de'|language_name }}
```

By default, the filter uses the current locale. You can pass it explicitly:

```twig
{# allemand #}
{{ 'de'|language_name('fr') }}

{# français canadien #}
{{ 'fr_CA'|language_name('fr_FR') }}
```

### locale_name

The locale_name filter returns the locale name given its two-letter code:

```twig
{# German #}
{{ 'de'|locale_name }}
```

By default, the filter uses the current locale. You can pass it explicitly:

```twig
{# allemand #}
{{ 'de'|locale_name('fr') }}

{# français (Canada) #}
{{ 'fr_CA'|locale_name('fr_FR') }}
```

### timezone_name

The timezone_name filter returns the timezone name given a timezone identifier:

```twig
{# Central European Time (Paris) #}
{{ 'Europe/Paris'|timezone_name }}

{# Pacific Time (Los Angeles) #}
{{ 'America/Los_Angeles'|timezone_name }}
```

By default, the filter uses the current locale. You can pass it explicitly:

```twig
{# heure du Pacifique nord-américain (Los Angeles) #}
{{ 'America/Los_Angeles'|timezone_name('fr') }}
```

### format_currency

The format_currency filter formats a number as a currency:

```twig
{# €1,000,000.00 #}
{{ '1000000'|format_currency('EUR') }}
```

You can pass attributes to tweak the output:

```twig
{# €12.34 #}
{{ '12.345'|format_currency('EUR', {rounding_mode: 'floor'}) }}

{# €1,000,000.0000 #}
{{ '1000000'|format_currency('EUR', {fraction_digit: 4}) }}
```

The list of supported options:

- grouping_used;
- decimal_always_shown;
- max_integer_digit;
- min_integer_digit;
- integer_digit;
- max_fraction_digit;
- min_fraction_digit;
- fraction_digit;
- multiplier;
- grouping_size;
- rounding_mode;
- rounding_increment;
- format_width;
- padding_position;
- secondary_grouping_size;
- significant_digits_used;
- min_significant_digits_used;
- max_significant_digits_used;
- lenient_parse.

By default, the filter uses the current locale. You can pass it explicitly:

```twig
{# 1.000.000,00 € #}
{{ '1000000'|format_currency('EUR', locale='de') }}
```

### format_number and format_*_number

The format_number filter formats a number:

```twig
{{ '12.345'|format_number }}
```

You can pass attributes to tweak the output:

```twig
{# 12.34 #}
{{ '12.345'|format_number({rounding_mode: 'floor'}) }}

{# 1000000.0000 #}
{{ '1000000'|format_number({fraction_digit: 4}) }}
```

The list of supported options:

- grouping_used;
- decimal_always_shown;
- max_integer_digit;
- min_integer_digit;
- integer_digit;
- max_fraction_digit;
- min_fraction_digit;
- fraction_digit;
- multiplier;
- grouping_size;
- rounding_mode;
- rounding_increment;
- format_width;
- padding_position;
- secondary_grouping_size;
- significant_digits_used;
- min_significant_digits_used;
- max_significant_digits_used;
- lenient_parse.

Besides plain numbers, the filter can also format numbers in various styles:

```twig
{# 1,234% #}
{{ '12.345'|format_number(style='percent') }}

{# twelve point three four five #}
{{ '12.345'|format_number(style='spellout') }}

{# 12 sec. #}
{{ '12'|format_duration_number }}
```

The list of supported styles:

- decimal;
- currency;
- percent;
- scientific;
- spellout;
- ordinal;
- duration.

As a shortcut, you can use the format_*_number filters by replacing `*` with a style:

```twig
{# 1,234% #}
{{ '12.345'|format_percent_number }}

{# twelve point three four five #}
{{ '12.345'|format_spellout_number }}
```

You can pass attributes to tweak the output:

```twig
{# 12.3% #}
{{ '0.12345'|format_percent_number({rounding_mode: 'floor', fraction_digit: 1}) }}
```

By default, the filter uses the current locale. You can pass it explicitly:

```twig
{# 12,345 #}
{{ '12.345'|format_number(locale='fr') }}
```

### format_datetime

The format_datetime filter formats a date time:

```twig
{# Aug 7, 2019, 11:39:12 PM #}
{{ '2019-08-07 23:39:12'|format_datetime() }}
```

#### Format

You can tweak the output for the date part and the time part:

```twig
{# 23:39 #}
{{ '2019-08-07 23:39:12'|format_datetime('none', 'short', locale='fr') }}

{# 07/08/2019 #}
{{ '2019-08-07 23:39:12'|format_datetime('short', 'none', locale='fr') }}

{# mercredi 7 août 2019 23:39:12 UTC #}
{{ '2019-08-07 23:39:12'|format_datetime('full', 'full', locale='fr') }}
```

Supported values are: none, short, medium, long, and full.

For greater flexibility, you can even define your own pattern (see the ICU user guide for supported patterns).

```twig
{# 11 oclock PM, GMT #}
{{ '2019-08-07 23:39:12'|format_datetime(pattern="hh 'oclock' a, zzzz") }}
```

#### Locale

By default, the filter uses the current locale. You can pass it explicitly:

```twig
{# 7 août 2019 23:39:12 #}
{{ '2019-08-07 23:39:12'|format_datetime(locale='fr') }}
```

#### Timezone

By default, the date is displayed by applying the default timezone (the one specified in php.ini or declared in Twig -- see below), but you can override it by explicitly specifying a timezone:

```twig
{{ datetime|format_datetime(locale='en', timezone='Pacific/Midway') }}
```

If the date is already a DateTime object, and if you want to keep its current timezone, pass false as the timezone value:

```twig
{{ datetime|format_datetime(locale='en', timezone=false) }}
```

The default timezone can also be set globally by calling setTimezone():

```
$twig = new \Twig\Environment($loader);
$twig->getExtension(\Twig\Extension\CoreExtension::class)->setTimezone('Europe/Paris');
```

### format_date

The format_date filter formats a date. It behaves in the exact same way as the format_datetime filter, but without the time.

### format_time

The format_time filter formats a time. It behaves in the exact same way as the format_datetime filter, but without the date.

## Removed functions

Functions used in plugin's version 1.x for October 1.0.x, 1.1.x, 2.0.x and removed from this version:

- config() - it's native function since October 3.1.17
- env() - it's native function since October 3.1.17

## Removed filters

Filters used in plugin's version 1.x for October 1.0.x, 1.1.x, 2.0.x and removed from this version:

- uppercase - use str_upper or just upper
- lowercase - use str_lower or just lower
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
- sortbyfield - use `collect(data).sortBy('age')` or `collect(data).sortByDesc('age')`

For more info see [UPGRADE.md](UPGRADE.md) guide.

## Contributing

- [ ] Make template_from_string turned off by default and add special checkbox to the backend to allow it.
- [ ] Add missing unit tests.
- [ ] New filters *ga* and *gtm* for adding GA or GTM code (Heap Analytics) - {{ 'UA-1234567' | ga }}.
- [ ] Add [cache extension](https://github.com/vojtasvoboda/oc-twigextensions-plugin/issues/11).

**Feel free to send pullrequest!** Please, send Pull Request to master branch.

## License

Twig extensions plugin is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT) same as OctoberCMS platform.
