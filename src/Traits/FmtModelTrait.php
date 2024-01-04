<?php
namespace Unusualdope\FilamentModelTranslatable\Traits;

use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Unusualdope\FilamentModelTranslatable\Models\FmtLanguage;


trait FmtModelTrait
{
    public static function addTranslatableFieldsToSchema( $schema, Form $form, $merge = true )
    {
        $languages = FmtLanguage::all()->pluck('iso_code', 'id')->toArray();
        $current_language = FmtLanguage::where( 'iso_code', app()->getLocale() )->value('id');

        $object = new self::$model();

        if ($object->isTranslatable() && count( $languages ) > 0) {
            $lang_tabs = [];
            foreach ($languages as $lang_id => $lang_iso_code) {
                $schema_tab = [];
                foreach ($object->getTranslatable() as $field => $translatable) {
                    $method = "Filament\Forms\Components\\" . $translatable['formType'];
                    $new_field = $method::make($field . '_fmtLang_' . $lang_id)
                        ->label($translatable['name'] . " - " . strtoupper($lang_iso_code));
                    if (!empty($translatable['methods']) && count($translatable['methods']) > 0) {
                        foreach ($translatable['methods'] as $method => $params) {
                            if (is_string($params)) {
                                $new_field = $new_field->{$method}($params);
                            } elseif (is_array($params) && count($params) > 0) {
                                call_user_func($new_field->{$method}, $params);
                            }
                        }
                    }

                    array_push( $schema_tab, $new_field);
                }

                $lang_tab = Tabs\Tab::make(strtoupper($lang_iso_code))
                    ->schema($schema_tab);
                array_push($lang_tabs, $lang_tab);
            }
            $tabs = [Tabs::make('Tabs')->columnSpanFull()
                ->tabs($lang_tabs)];
            if ($merge) {
                $schema = array_merge( $schema, $tabs );
            } else {
                return $tabs;
            }

        }

        return $schema;
    }
}
