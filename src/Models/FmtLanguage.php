<?php

namespace Unusualdope\FilamentModelTranslatable\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FmtLanguage extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'iso_code',
        'is_default',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public static function getLanguages( $cache = true )
    {
        if( Cache::has('fmt_languages' ) && !empty(Cache::get('fmt_languages')) && $cache) {
            return Cache::get('fmt_languages');
        }
        $result = self::all()->pluck('iso_code', 'id')->toArray();
        Cache::set('fmt_languages', $result, 3600);
        return $result;
    }

    public static function getCurrentLanguage(): int
    {
        $locale = app()->getLocale();
        if( Cache::has('fmt_lang_id_' . $locale ) ) {
            return Cache::get('fmt_lang_id_' . $locale);
        }
        $result = self::where( 'iso_code', $locale )->value('id');
        Cache::set('fmt_lang_id_' . $locale, $result);
        return $result;
    }

    public static function getDefaultLanguage()
    {
        if( Cache::has('fmt_lang_default' ) ) {
            return Cache::get('fmt_lang_default' );
        }
        $result = self::where( 'is_default', true )->value('id');
        Cache::set('fmt_lang_default', $result);
        return $result;
    }

    public function save(array $options = [])
    {
        Cache::delete('fmt_lang_default');
        Cache::delete('fmt_lang_id_' . app()->getLocale());
        Cache::delete('fmt_languages' );

        //Only one default is possible
        if( $this->is_default ) {
            self::where('is_default', true)->update(['is_default' => false]);
        }
        return parent::save($options);
    }
}
