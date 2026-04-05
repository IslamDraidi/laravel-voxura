<?php

namespace App\Traits;

trait HasTranslations
{
    public function getTranslatedNameAttribute(): string
    {
        $locale = app()->getLocale();
        $translations = $this->name_translations;

        return $translations[$locale] ?? $translations['en'] ?? $this->name;
    }
}
