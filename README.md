# Twig extensions

[![Build Status](https://travis-ci.org/vojtasvoboda/oc-twigextensions-plugin.svg?branch=master)](https://travis-ci.org/vojtasvoboda/oc-twigextensions-plugin)
[![HHVM Status](https://img.shields.io/hhvm/vojtasvoboda/oc-twigextensions-plugin/master.svg)](http://hhvm.h4cc.de/package/vojtasvoboda/oc-twigextensions-plugin)
[![Codacy](https://img.shields.io/codacy/c6b23b6527bd407092763cace324ef4a.svg)](https://www.codacy.com/app/vojtasvoboda/oc-twigextensions-plugin)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/g/vojtasvoboda/oc-twigextensions-plugin.svg)](https://scrutinizer-ci.com/g/vojtasvoboda/oc-twigextensions-plugin/?branch=master)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/vojtasvoboda/oc-twigextensions-plugin/blob/master/LICENSE.md)

Twig extensions plugin for OctoberCMS adds new filter and functions to your templates. No other plugin dependencies.

## Installation

Just install VojtaSvoboda.TwigExtensions plugin a you can use new added filters/functions at your templates:

```
<h1 class="heading">{{ article.heading | uppercase }}</h1>
<p class="created">
	Posted at {{ article.date | strftime('%d.%m.%Y %H:%M:%S') }}
</p>
<p class="perex">
	{{ article.perex | truncate(80) }}
</p>
```

## Available functions

[template\_from\_string](http://twig.sensiolabs.org/doc/functions/template_from_string.html)

### template\_from\_string

Function loads a template from a string.

```
{% set name = 'John' %}
{{ include(template_from_string("Hello {{ name }}")) }}
{{ include(template_from_string("Hurry up it is: {{ "now"|date("m/d/Y") }}")) }}
```

### config

Function transports the funtionality of the Laravel `config()` helper function to Twig.

```
{{ config('app.locale') }}
```
The example would output the value currently stored in `app.locale.
See [more about the Laravel helper function here](https://laravel.com/docs/5.0/configuration#accessing-configuration-values).


## Available filters

strftime, uppercase, lowercase, ucfirst, lcfirst, ltrim, rtrim, str_repeat,
plural, truncate, wordwrap, strpad, leftpad, rightpad, shuffle, time_diff,
localizeddate, localizednumber, localizedcurrency

### strftime

Format a local time/date according to locale settings.

```
Posted at {{ article.date | strftime('%d.%m.%Y %H:%M:%S') }}
```

The example would output *Posted at 04.01.2016 22:57:42*. See [more format parameters](http://php.net/manual/en/function.strftime.php#refsect1-function.strftime-parameters).

### uppercase

Make a string uppercase.

```
Hello I'm {{ 'Jack' | uppercase }}
```

The example would output *Hello I'm JACK*.

### lowercase

Make a string lowercase.

```
Hello I'm {{ 'JACK' | lowercase }}
```

The example would output *Hello I'm jack*.

### ucfirst

Make a string's first character uppercase.

```
Hello I'm {{ 'jack' | ucfirst }}
```

The example would output *Hello I'm Jack*.

### lcfirst

Make a string's first character lowercase.

```
Hello I'm {{ 'Jack' | lcfirst }}
```

The example would output *Hello I'm jack*.

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

### str_repeat

Repeat a string.

```
I'm the {{ 'best' | str_repeat(3) }}!
```

The example would output *I'm the best best best!*

### plural

Get the plural form of an English word.

```
You have {{ count }} new {{ 'mail' | plural(count) }}
```

The example would output *You have 1 new mail* or *You have 3 new mails* - depending on mails count.

### truncate

Use the truncate filter to cut off a string after limit is reached.

```
{{ "Hello World!" | truncate(5) }}
```

The example would output *Hello...*, as ... is the default separator.

You can also tell truncate to preserve whole words by setting the second parameter to true. If the last Word is on the the separator, truncate will print out the whole Word.

```
{{ "Hello World!" | truncate(7, true) }}
```

Here *Hello World!* would be printed.

If you want to change the separator, just set the third parameter to your desired separator.

```
{{ "Hello World!" | truncate(7, false, "??") }}
```

This example would print *Hello W??*.

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

### strpad

Pad a string to a certain length with another string from both sides.

```
{{ 'xxx' | strpad(7, 'o') }}
```

This would print:

```
ooxxxoo
```

### leftpad

Pad a string to a certain length with another string from left side.

```
{{ 'xxx' | leftpad(5, 'o') }}
```

This would print:

```
ooxxx
```

### rightpad

Pad a string to a certain length with another string from right side.

```
{{ 'xxx' | rightpad(5, 'o') }}
```

This would print:

```
xxxoo
```

### shuffle

Shuffle an array.

```
{{ songs | shuffle }}
```

or in foreach:

```
{% for fruit in ['apple', 'banana', 'orange'] | shuffle %}
	{{ fruit }}
{% endfor %}
```

### time_diff

Use the time_diff filter to render the difference between a date and now.

```
{{ post.published_at | time_diff }}
```

The example above will output a string like 4 seconds ago or in 1 month, depending on the filtered date.

#### Arguments

- date: The date for calculate the difference from now. Can be a string or a DateTime instance.
- now: The date that should be used as now. Can be a string or a DateTime instance. Do not set this argument to use current date.

#### Translation

To get a translatable output, give a Symfony\Component\Translation\TranslatorInterface as constructor argument. The returned string is formatted as diff.ago.XXX or diff.in.XXX where XXX can be any valid unit: second, minute, hour, day, month, year.

### localizeddate

Use the localizeddate filter to format dates into a localized string representating the date.

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

Use the localizednumber filter to format numbers into a localized string representating the number.

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

Use the localizedcurrency filter to format a currency value into a localized string.

```
{{ product.price | localizedcurrency('EUR') }}
```

#### Arguments

- currency: The 3-letter ISO 4217 currency code indicating the currency to use.
- locale: The locale used for the format. If NULL is given, Twig will use Locale::getDefault()

## Future plans

- [x] Add Unit tests
- [ ] Create backend settings and add checkboxes for each functions/filters group

**Feel free to send pullrequest!**

## Contributing

Please send Pull Request to master branch.

## License

Twig extensions plugin is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT) same as OctoberCMS platform.
