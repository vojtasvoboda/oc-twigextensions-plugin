<?php namespace VojtaSvoboda\TwigExtensions\Classes;

use App;
use October\Rain\Translation\Translator;

class TimeDiffTranslator extends Translator
{
    public function trans($id, array $parameters = [], $domain = 'messages', $locale = null)
    {
        return $this->get('vojtasvoboda.twigextensions::lang.' . $id, $parameters, $locale);
    }

    public function transChoice($id, $number, array $parameters = [], $domain = 'messages', $locale = null)
    {
        return $this->choice('vojtasvoboda.twigextensions::lang.' . $id, $number, $parameters, $locale);
    }
}
