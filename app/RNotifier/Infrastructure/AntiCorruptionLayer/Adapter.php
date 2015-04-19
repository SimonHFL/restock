<?php namespace App\RNotifier\Infrastructure\AntiCorruptionLayer;


use App\RNotifier\Infrastructure\Transformers\Translator;

class Adapter {

    function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function toEntityParameters(array $attributes, $method)
    {
        $dictionary = $this->getDictionary($method, 'Output');

        $entityAttributes = $this->translator->translate($attributes, $dictionary);

        return $entityAttributes;
    }

    public function toApiParameters(Array $attributes, $method)
    {
        $dictionary = $this->getDictionary($method, 'Input');

        $apiParameters = $this->translator->translate($attributes, $dictionary);

        return $apiParameters;
    }

    /**
     * get the dictionary with corresponding method
     * and direction (Input/Output)
     *
     * @param $method
     * @param $direction
     * @return mixed
     */
    private function getDictionary($method, $direction)
    {
        $dictionaryName = $method . $direction . 'Dictionary';
        $dictionary = $this->$dictionaryName;
        return $dictionary;
    }

}