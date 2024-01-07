# Filament-Model-Translatable (plugin)

## DESCRIPTION
Adds the ability to insert translations in content using a language table.
For example you can create a main model that stores the object id and the data that is not translatable, on the lang model you have to define the foreign key for the main object(in this case post_id) and the one for the language that has always to be "language_id".
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

## MAIN MODEL

In the main model extend the FmtModel:

```php
use Unusualdope\FilamentModelTranslatable\Models\FmtModel;

class Post extends FmtModel
{
```

Define some properties to make the plugin work, see the example with comments:
```php
    /**
     * Translatable props needed
     */

    protected $lang_model = 'App\Models\PostLanguage'; //fqn of the translatable model 
    protected $lang_foreign_key = 'post_id'; //foreign key

    protected $is_translatable = true;
    protected $translatable = [
        'title' => [ //field name that will match with the LangModel db field and property
            'formType' => 'TextInput', //The type of form input field as in Filament
            'name' => 'Title', //Field Label
            'methods' => [ //The methods you want to call from filament on your field to define it
                'required' => '1',
                'prefix' => 'title',
                ...
                'anotherMethod' => [
                    'param1' => '1',
                    'param2' => 'test'
                    ...
                    'paramN' => 'xxx'
                ]
            ],
        ],
        'content' => [
            'formType' => 'RichEditor',
            'name' => 'Content',
            'methods' => [
                'columnSpanFull' => '',
            ],
        ],
    ];
```

## RESOURCE

In the RESOURCE you have to use the Trait fmtTrait and retrieve the translatable fields with 

```php
self::addTranslatableFieldsToSchema(array $schema, Form $form, Bool false);
``` 

1 - As first parameter you can pass the current schema and it will give you back the full schema with the translatable fields appended at the end.

2 - the Form object

3 - If you want back only the array containing the schema of the translatable fields (to allow you to place it in the middle of your schema) set this to false (default is true)
Below is just an example:

```php
class PostResource extends Resource
{
    use FmtModelTrait;

    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    public static function form(Form $form): Form
    {

        $schema = [
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->required(),
            Forms\Components\Select::make('post_status_id')
                ->relationship('postStatus', 'name')
                ->required(),
        ];

        $schema = self::addTranslatableFieldsToSchema($schema, $form);

        return $form
            ->schema($schema);
    }
```

## CREATE AND EDIT RESOURCE PAGES
On the CREATE page extend 

```php
Unusualdope\FilamentModelTranslatable\Filament\Resources\Pages\FmtCreateRecord
```

instead of the standard 
~~`Filament\Resources\Pages\CreateRecord`~~

On the EDIT page extend 
```php
Unusualdope\FilamentModelTranslatable\Filament\Resources\Pages\FmtEditRecord
```
instead of the standard ~~`Filament\Resources\Pages\EditRecord`~~

e.g.:

```php
use Unusualdope\FilamentModelTranslatable\Filament\Resources\Pages\FmtCreateRecord;

class CreatePlan extends FmtCreateRecord
{
    protected static string $resource = PlanResource::class;
}
```


## RESULT
You will get a tab that let you change language and fill the content for every language:

![Fmt Preview Image](https://unusualdope.com/external/images/fmt/fmtPreview01.png)

## ISSUES OR SUGGESTIONS

Please feel free to give any suggestions for improvements or report any issue directly on the github [plugin repository](https://github.com/geimsdin/filament-model-translatable)
