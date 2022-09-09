<?php


namespace Anita\Traits;


trait TranslateField
{

    /**
     * @param string $field
     * @return bool
     */
    public function isTranslationField(string $field)
    {
        return in_array($field, $this->translatedAttributes);
    }

    /**
     * @param string $field
     * @param string $locale
     * @return bool
     */
    public function translationFilled(string $field, string $locale): bool
    {
        return !empty($this->getTranslation($locale)->$field);
    }

}
