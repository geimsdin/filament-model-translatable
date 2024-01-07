<?php

namespace  Unusualdope\FilamentModelTranslatable\Filament\Resources\Pages;

use Filament\Resources\Pages\EditRecord;
use Unusualdope\FilamentModelTranslatable\Helpers\FmtHelper;
use Unusualdope\FilamentModelTranslatable\Models\FmtLanguage;

class FmtEditRecord extends EditRecord
{
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->record;

        //Double check that we have an id of the main model
        if ( $record->id ) {

            //get the Lang model and his content related to the main model
            $lang_model = (string)$record->getLangModel();
            $lang_contents = $lang_model::where((string)$record->getLangForeignKey(), $record->id)
                ->get()->toArray();

            //retrieve the fields marked as translatables
            $translatables = $record->getTranslatable();

            //cycle through languages and fields to inject the translated data into the form
            foreach ($lang_contents as $lang_content ) {
                foreach ( $translatables as $field => $params) {
                    $data[$field . '_fmtLang_' . $lang_content['language_id']] = $lang_content[$field];
                }
            }
        }

        //Assign the starting lang data to show
        $data['language_id'] = FmtLanguage::getCurrentLanguage();

        return parent::mutateFormDataBeforeFill($data);
    }


    protected function afterSave()
    {
        FmtHelper::saveWithLang( $this->record, $this->data, true);
    }
}
