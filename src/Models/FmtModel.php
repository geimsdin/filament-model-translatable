<?php

namespace Unusualdope\FilamentModelTranslatable\Models;

use Illuminate\Database\Eloquent\Model;

class FmtModel extends Model
{
    protected $lang_model = '';
    protected $lang_foreign_key = '';

    protected $is_translatable = false;
    protected $translatable = [];

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
//    public function fill(array $attributes)
//    {
//        parent::fill($attributes);
//
//
//        $translations = $this->lang_model::where($this->lang_foreign_key, $this->id);
//        foreach( $translations as $translation ) {
//
//            foreach( $this->translatable as $field_name => $translatable ) {
//                $this->$field_name . '_' . $translation['language_id'] = $translation[$field_name];
//
//                dump($this->$field_name . '_' . $translation['language_id']);
//
//            }
//
//        }
//
//        //dd($this);
//
//        return $this;
//    }

    public function getTranslatable(): array
    {
        return $this->translatable;
    }

    public function getLangModel(): string
    {
        return $this->lang_model;
    }

    public function getLangForeignKey(): string
    {
        return $this->lang_foreign_key;
    }

    public function isTranslatable(): bool
    {
        return $this->is_translatable &&
            ( count($this->translatable) > 0 || count($this->getTranslatable() ) > 0) &&
            !empty($this->lang_model) &&
            !empty($this->lang_foreign_key);
    }

    public function langModel()
    {
        $data = $this->lang_model::where( $this->lang_foreign_key, $this->id)->get()->toArray();
        $result = [];
        foreach ( $data as $fieldname => $value ) {
            dd( $fieldname, $value );
        }
    }

    public function getTranslatableFilamentFields()
    {
        return null;
    }
}
