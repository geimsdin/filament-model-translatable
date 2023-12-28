<?php

namespace Unusualdope\FilamentModelTranslatable\Models;

use Illuminate\Database\Eloquent\Model;

class FmtModel extends Model
{
    protected $lang_model = '';
    protected $lang_foreign_key = '';

    protected $is_translatable = false;
    protected $translatable = [];

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
            count($this->translatable) > 0 &&
            !empty($this->lang_model) &&
            !empty($this->lang_foreign_key);
    }

    public function langModel()
    {
        $data = $this->lang_model::where( $this->lang_foreign_key, $this->id)->get()->toArray();
        $result = [];
        foreach ( $data as $fieldname => $value ) {

        }
    }
}
