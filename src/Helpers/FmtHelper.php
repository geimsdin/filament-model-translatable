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

                $new_lang_model = $lang_model::where('language_id', $language_id)
                    ->where($lang_foreign_key, $record->id)->first();
                if($new_lang_model){
                    $is_update = true;
                }
                //If the lang model has been defined after the model already has data it has to be considered as a new insertion
                if ( !$is_update || empty($new_lang_model) )
                {
                    $new_lang_model = new $lang_model();
                    $is_update = false;
                }

                $new_lang_model->$lang_foreign_key = $record->id;
                $new_lang_model->language_id = $language_id;
                foreach ( $translatables  as $translatable => $options ) {
                    if ( is_int($translatable) && is_string( $options ) ) {
                        $translatable = $options;
                    }

                    if ( !empty( $data[$translatable . '_fmtLang_' . $language_id])){
                        $new_lang_model->$translatable = $data[$translatable . '_fmtLang_' . $language_id];
                    } elseif(array_key_exists($translatable . '_fmtLang_' . $language_id, $data)){
                        if($data[$translatable . '_fmtLang_' . $language_id] === null){
                            $new_lang_model->$translatable = '';
                        }
                    }
                    $new_lang_model->language_id = $language_id;
                }

                if ( $is_update ) {
                    $new_lang_model->update();
                } else {
                    $new_lang_model->save();
                }
                unset( $new_lang_model );

            }

        }
    }
}
