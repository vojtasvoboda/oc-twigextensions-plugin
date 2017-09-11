<?php namespace VojtaSvoboda\TwigExtensions\Classes;

use App;
use October\Rain\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

class TimeDiffTranslator extends Translator implements TranslatorInterface
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
