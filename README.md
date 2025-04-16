# Filament-Model-Translatable (plugin)

## DESCRIPTION
Adds the ability to insert translations in content using a language table.
For example, you can create a main model that stores the object id and the data that is not translatable, on the lang model you have to define the foreign key for the main object(in this case post_id) and the one for the language that has always to be "language_id".
Below an example of model definition in YAML for blueprint package:
```yaml
  Post:
    user_id: string
    postStatus_id: string nullable
    relationships:
      belongsTo: PostStatus, \App\Models\User
      hasMany: PostLanguage

  PostLanguage:
    title: string:160
    content: string nullable
    post_id: unsignedInteger
    language_id: unsignedInteger
    relationships:
      belongsTo: Language
```
## INSTALLATION

Simply install using composer

```bash
composer require unusualdope/filament-model-translatable
```

run then 
```bash
php artisan fmt:install
```
 and follow the prompts to publish and run the migrations and create the languages.

 don't forget to register the plugin in your panel

 ```php
use Unusualdope\FilamentModelTranslatable\FmtPlugin;
use Filament\Panel;
 
class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            // ...
            ->plugin(
                FmtPlugin::make()
            );
    }
}
 ```

## HOW TO USE IT

### MAIN MODEL
In the main model use the provided trait "HasTranslation":

```php
use Unusualdope\FilamentModelTranslatable\Traits\HasTranslation;

class Post extends Model
{
    use HasTranslation;
}
    //...
```
The plugin assumes that the translatable model is named as the main model + "Language" and the foreign key is the main model name in CamelCase + "Language" (e.g. PostLanguage)
```php
protected string $lang_model = __CLASS__ . 'Language';
```
if you want to change the language model name you can do so overwriting the property in your main model
```php
    /**
     * Translatable props needed
     */

    protected $lang_model = 'App\Models\PostTranslated'; //fqn of the translatable model 
```
the plugin assumes that the foreign key is the standard laravel foreign key
(eg: post_id), if you want to change it you can do so overwriting the property in your main model
```php
    /**
     * Specify the foreign key if not the standard one
     */

    protected $lang_foreign_key = 'post_ext_id';  
```
if for any reason you want to stop/pause the translat-ability of your model
you can set the property $is_translatable to false
```php
    /**
     * Set to false in order to disable the translatable feature
     */

    protected bool $is_translatable = false;  
```
On the main model you have to define the fields that will be translatable using standard Filament fields
as you would do in a resource, specify them in method setTranslatableFilamentFields(), the make() method
has to contain the field names that are present in the database on the <Model>Language table

```php
    use Filament\Forms\Components\Textarea;
    use Filament\Forms\Components\TextInput;
    
    public function setTranslatableFilamentFields()
    {
        return [
            TextInput::make('name')
                ->required()
                ->label('Name'),
            TextInput::make('link_rewrite')
                ->maxLength(128)
                ->label('Link Rewrite'),
            TextInput::make('meta_title')
                ->maxLength(128)
                ->label('Meta Title'),
            Textarea::make('meta_description')
                ->maxLength(512)
                ->label('Meta Description'),
            //...
        ];
    }
```

### RESOURCE

In the RESOURCE when defining the form insert the translatable fields where you want
by using the method addTranslatableFieldsToSchema() and passing as parameter the field name
exactly as defined in the database and in the main model


```php
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //translatable
                FeatureGroup::addTranslatableFieldsToSchema('name'),
                //not translatable
                Forms\Components\TextInput::make('position')
                    ->required()
                    ->numeric()
                    ->hidden()
                    ->default(0),
                //translatable
                FeatureGroup::addTranslatableFieldsToSchema('tooltip'),
                
                //..
                
            ]);
    }
```

## CREATE AND EDIT RESOURCE PAGES
On the CREATE page extend 

```php
Unusualdope\FilamentModelTranslatable\Filament\Resources\Pages\FmtCreateRecord

class CreatePost extends FmtCreateRecord
{
    //..
}
```

instead of the standard 
~~`Filament\Resources\Pages\CreateRecord`~~

On the EDIT page extend 
```php
Unusualdope\FilamentModelTranslatable\Filament\Resources\Pages\FmtEditRecord

class EditPost extends FmtEditRecord
{
    //..
}
```
instead of the standard ~~`Filament\Resources\Pages\EditRecord`~~



## RESULT
You will get a tab that let you change language and fill the content for every language:

![Fmt Preview Image 1](https://unusualdope.com/fmtImages/fmt_preview01.png)
![Fmt Preview Image 2](https://unusualdope.com/fmtImages/fmt_preview02.png)

## DATA RETRIEVAL
The trait HasTranslation provides a method to retrieve the translated data for the current language
by using the defined HasOne relationship "currentLanguage", if you want to retrieve the post name in the 
current language in frontend you can do so by using the following code:

```php
$post = Post::find(1);
$post->currentLanguage->name;
```

if you need to access all the data for all the languages 
you can use the HasMany relationship "languageData", you can do so by using the following code:

```php
$post = Post::find(1);
$post->languageData;
```

## ISSUES OR SUGGESTIONS

Please feel free to give any suggestions for improvements or 
report any issue directly on the gitHub [plugin repository](https://github.com/geimsdin/filament-model-translatable)
