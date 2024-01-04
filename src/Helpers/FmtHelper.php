<?php

namespace Unusualdope\FilamentModelTranslatable\Helpers;

use Unusualdope\FilamentModelTranslatable\Models\FmtLanguage;

class FmtHelper
{
    public static function saveWithLang( $record, $data, $is_update = false )
    {
        if ( method_exists( $record, 'getTranslatable' ) &&
            $record->isTranslatable() )
        {
            $translatables = $record->getTranslatable() ;
            $lang_model = $record->getLangModel();
            $lang_foreign_key = $record->getLangForeignKey();

            $languages = FmtLanguage::getLanguages();
            $default_language = FmtLanguage::getDefaultLanguage();

            foreach ( $languages as $language_id => $language_iso_code ) {

                $new_lang_model = new $lang_model();

                if ($is_update && !empty( $lang_content_id = $record->where('language_id', $language_id)
                    ->andWhere($lang_foreign_key, $record->id)->get()->value('id') ) ) {
                    $new_lang_model->id = $lang_content_id;
                }

                $new_lang_model->$lang_foreign_key = $record->id;
                $new_lang_model->language_id = $language_id;
                foreach ( $translatables  as $translatable => $options ) {
                    if ( !empty( $data[$translatable . '_fmtLang_' . $language_id])){
                        $new_lang_model->$translatable = $data[$translatable . '_fmtLang_' . $language_id];
                    }
                }
                $new_lang_model->save();
                unset( $new_lang_model );

            }

        }
    }
}
