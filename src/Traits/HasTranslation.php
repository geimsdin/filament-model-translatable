<?php
namespace Unusualdope\FilamentModelTranslatable\Traits;

use App\Models\Stock\VariantGroupLanguage;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Relations\HasMany;
use JetBrains\PhpStorm\NoReturn;
use phpDocumentor\Reflection\DocBlock\Tags\MethodParameter;
use Unusualdope\FilamentModelTranslatable\Models\FmtLanguage;


trait HasTranslation
{
    protected string $lang_model = __CLASS__ . 'Language';

    protected bool $is_translatable = true;

    public static function addTranslatableFieldsToSchema($field_name = null, $schema = null, $merge = false)
    {
        $languages = FmtLanguage::getLanguages();

        $object = new self();

        if ($object->isTranslatable() && count($languages) > 0) {
            $lang_tabs = [];

            foreach ($languages as $lang_id => $lang_iso_code) {
                $schema_tab = $object->getTranslatableFilamentFields( $field_name )['schema_' . $lang_id];

                $lang_tab = Tabs\Tab::make(strtoupper($lang_iso_code))->extraAttributes(['class' => 'fmt_tab_lang_' . $lang_id])
                    ->schema(
                        $schema_tab
                    );
                $lang_tabs[] = $lang_tab;
            }

            $tabs = [Tabs::make('Tabs')
                ->columns(1)
                ->columnSpanFull()
                ->tabs($lang_tabs)];
            if ($merge) {
                $schema = array_merge($schema, $tabs);
            } else {
                return $tabs[0];
            }

        }

        return $schema;
    }

    public function getLangModel(): string
    {
        if (empty($this->lang_model)) {
            $this->lang_model = __CLASS__ . 'Language';
        }
        return $this->lang_model;
    }

    public function getLangForeignKey(): string
    {
        if( !empty($this->lang_foreign_key)){
            return $this->lang_foreign_key;
        }
        return $this->getForeignKey();
    }

    public function isTranslatable(): bool
    {
        return $this->is_translatable &&
            !empty($this->lang_model) &&
            !empty($this->getLangForeignKey());
    }

    public function langModel()
    {
        $data = $this->lang_model::where($this->getLangForeignKey(), $this->id)->get()->toArray();
        $result = [];
        foreach ($data as $fieldname => $value) {
            dd($fieldname, $value);
        }
    }

    public function setTranslatableFilamentFields(): array
    {
        return [];
    }

    public function getTranslatable()
    {
        $fields = $this->setTranslatableFilamentFields();
        $translatable_fields = [];
        foreach ( $fields as $field ) {

            $translatable_fields[] = $field->getName();
        }
        return $translatable_fields;
    }

    public function getTranslatableFilamentFields( $field_name = null ): array
    {

        $result = [];
        $fields = $this->setTranslatableFilamentFields();
        $languages = FmtLanguage::getLanguages();

        foreach ( $fields as $field ) {
            foreach ( $languages as $lang_id => $lang_iso_code) {
                $lang_iso_code = strtoupper($lang_iso_code);
                $current_field_name = $field->getName();
                $current_field_label = $field->getLabel();

                if ( $field_name == $current_field_name ) {

                    $newName = $current_field_name . '_fmtLang_' . $lang_id;
                    // Clone the field with the new name
                    $clonedField = clone $field;

                    $result['schema_' . $lang_id][] = $clonedField->name($newName)->statePath($newName)
                        ->label($current_field_label . ' - ' . $lang_iso_code);
                }
            }

        }

        return $result;

    }

    public static function getTranslatableFilamentFieldsStatic($field_name = null)
    {
        $object = new self();
        return $object->getTranslatableFilamentFields($field_name);

    }

    public function getFieldTranslation( $field, $lang_id = null )
    {
        if ($lang_id == null) {
            $lang_id = FmtLanguage::getCurrentLanguage();
        }

        return $this->lang_model::where($this->getLangForeignKey(), $lang_id)
            ->where( $this->getLangForeignKey(), $this->id)->pluck($field)->value();
    }

    public function currentLanguage(): HasMany
    {
        $current_language_id = FmtLanguage::getCurrentLanguage();
        $lang_model = $this->lang_model;

        if (class_exists($lang_model)) {
            return $this->hasMany($lang_model)
                ->where('language_id', $current_language_id);
        }

        // Handle the case where the class does not exist
        return $this->hasMany(get_class($this))->whereRaw('1 = 0');
    }

    public function languageData(): HasMany
    {

        return $this->hasMany($this->lang_model);

    }


}
