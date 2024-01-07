<?php

namespace  Unusualdope\FilamentModelTranslatable\Filament\Resources\Pages;

use Filament\Resources\Pages\CreateRecord;
use Unusualdope\FilamentModelTranslatable\Helpers\FmtHelper;
use Unusualdope\FilamentModelTranslatable\Models\FmtLanguage;

class FmtCreateRecord extends CreateRecord
{

    protected function afterCreate()
    {
        FmtHelper::saveWithLang( $this->record, $this->data, false);
    }

}
