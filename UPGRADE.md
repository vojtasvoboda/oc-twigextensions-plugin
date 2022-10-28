# Upgrading to October CMS v3.1

This guide can be used to help migrate from `VojtaSvoboda.TwigExtensions`. It involves replacing some Twig functions with the native syntax.

Beginning from October CMS v3.1.17, some new functions were added to the core that replaced some functions provided by this plugin, and many of the functions were already available in the core. Use the following document to find the latest syntax for each extension function.

## Updated Functions

### config

The `{{ config(...) }}` Twig function is native in October v3.1.17+

### env

The `{{ env(...) }}` Twig function is native in October v3.1.17+

### session

You can still use `{{ session('my.session.key') }}` or use native October v3.1.17+ function:

`{{ this.session.get('my.session.key') }}` to access session variables.

### trans

You can still use function `{{ trans('acme.blog::lang.app.name') }}` or use native October v3.1.17+ filter:

`{{ 'acme.blog::lang.app.name'|trans }}` to access translations.

### var_dump

You can still use function `{{ var_dump(users) }}` or filter `{{ users | var_dump }}` or use native October v3.1.17+ function:

`{{ dump(users) }}` to dump information about a variable. Properties are "clickable" to expand.

### strftime

You can still use filter `{{ article.date | strftime('%d.%m.%Y %H:%M:%S') }}` or use the `carbon()` function to build a carbon object, combined with `formatLocalized`, available in October v3.1.17+

```twig
{{ carbon(article.date).formatLocalized('%d.%m.%Y %H:%M:%S') }}
```

## Updated Filters

### uppercase

Use the `str_upper` filter or just `upper`:

```twig
{{ 'Jack'|str_upper }}
```

### lowercase

Use the `str_lower` filter or just `lower`:

```twig
{{ 'JACK'|str_lower }}
```

### ucfirst

Use the `str_ucfirst` filter

```twig
{{ 'jack'|str_ucfirst }}
```

### lcfirst

Use the `str_lcfirst` filter

```twig
{{ 'Jack'|str_lcfirst }}
```

### ltrim

You can still use function `{{ ' jack' | ltrim }}` or use the native `trim` Twig filter: https://twig.symfony.com/doc/2.x/filters/trim.html

### rtrim

You can still use function `{{ ' jack' | rtrim }}` or use the native `trim` Twig filter: https://twig.symfony.com/doc/2.x/filters/trim.html

### str_repeat

Use the `str_repeat` filter

```twig
I'm the {{ 'best '|str_repeat(3) }}!
```

### plural

Use the `str_plural` filter

```twig
You have {{ count }} new {{ 'mail'|str_plural(count) }}
```

### truncate

Use the `str_limit` filter

```twig
{{ "Hello World!"|str_limit(5) }}
{{ "Hello World!"|str_limit(5, '...') }}
```

### strpad

Use the `str_pad_both` filter

```
{{ 'xxx'|str_pad_both(7, 'o') }}
```

### str_replace

Use the `str_replace` filter

```
{{ 'Alice'|str_replace('Alice', 'Bob') }}
```

### strip_tags

You can still use function `{{ '<p><b>Text</b></p>' | strip_tags('<p>') }}` or use the `html_strip` October filter

```
{{ '<p><b>Text</b></p>'|html_strip }}
```

### leftpad

Use the `str_pad_left` filter

```
{{ 'xxx'|str_pad_left(5, 'o') }}
```

### rightpad

Use the `str_pad_right` filter

```
{{ 'xxx'|str_pad_right(5, 'o') }}
```

### rtl

Use the `str_reverse` filter

```
{{ 'Hello world!'|str_reverse }}
```

### shuffle

Use the `collect` function with `shuffle` method

```
{{ collect(songs).shuffle() }}
```

### time_diff

Use the `carbon()` function with `diffForHumans`

```
{{ carbon(post.published_at).diffForHumans() }}
```

### localizeddate

Use `{{ post.published_at | format_date('medium') }}` instead.

See https://twig.symfony.com/doc/3.x/filters/format_date.html

### localizednumber

Use `{{ 42.42 | format_number }}` instead.

See https://twig.symfony.com/doc/3.x/filters/format_number.html

### localizedcurrency

Use `{{ '1000000' | format_currency('EUR') }}` instead.

See https://twig.symfony.com/doc/3.x/filters/format_currency.html

### mailto

Use the `html_mailto` filter

```
{{ 'octobercms@gmail.tld'|html_mailto }}
```

### var_dump

You can use var_dump filter or use the `dump()` native OctoberCMS function

```
{{ dump(users) }}
```

### sortbyfield

Use `collect()` with `sort`

```twig
collect(data).sortBy('age')
collect(data).sortByDesc('age')
```

For example:

```twig
{% set data = [{'name': 'David', 'age': 31}, {'name': 'John', 'age': 28}] %}

{% for item in collect(data).sortBy('age') %}
    {{ item.name }}&nbsp;
{% endfor %}
```
