<?php

namespace  Unusualdope\FilamentModelTranslatable\Filament\Resources\Pages;

use Dflydev\DotAccessData\Data;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Unusualdope\FilamentModelTranslatable\Helpers\FmtHelper;
use Unusualdope\FilamentModelTranslatable\Models\FmtLanguage;

class FmtCreateRecord extends CreateRecord
{

    private array $translatable_data;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $model = $this->getModel();

        $object = new $model();
        if( !empty($object->originalTitleAttribute) && !empty($object->translatedTitleAttribute) )
        {
            $default_lang = FmtLanguage::getDefaultLanguage();
            $data[$object->originalTitleAttribute] = $data[$object->translatedTitleAttribute . '_fmtLang_' . $default_lang];
        }

        foreach ($data as $field_name => $field_content) {
            if ( strpos($field_name, "_fmtLang_") )
            {
                $this->translatable_data[$field_name] = $field_content;
                unset($data[$field_name]);
            }

        }

        return $data;
    }

    protected function afterCreate(): void
    {
        FmtHelper::saveWithLang( $this->record, $this->translatable_data, false);
    }

}
