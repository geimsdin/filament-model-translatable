<?php

namespace Unusualdope\FilamentModelTranslatable\Commands;

use Illuminate\Console\Command;
use Unusualdope\FilamentModelTranslatable\Models\FmtLanguage;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fmt:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install script for Filament Model Translatable plugin.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line("***************************************");
        $this->line("*            UNUSUALDOPE              *");
        $this->line("*      ***     ***    ***    ***      *");
        $this->line("*     FILAMENT MODEL TRANSLATABLE     *");
        $this->line("***************************************");
        $this->newLine(2);
        if ( $this->confirm('Now we will install the language table, ok? (skip if already installed)', true) ) {
            $this->callSilent('vendor:publish', [
                "--tag" => "filament-model-translatable-migrations"
            ]);
            $this->call('migrate');
        }
        $langs = 0;
        if ($this->confirm("Do you want to create some Languages now?",true)){
            while ($langs < 999) {
                $lang_iso_code = $this->ask('Enter the language Iso Code (e.g. \'en\' or \'it\'', '');
                $lang_name = $this->ask('Enter the language Full Name (e.g. \'English\' or \'Italiano\'', '');
                $lang_default = $this->confirm('Is ' . $lang_name . ' the default app language?', false);

                $new_lang = new FmtLanguage();

                $new_lang->iso_code = $lang_iso_code;
                $new_lang->name = $lang_name;
                $new_lang->is_default = $lang_default;

                $new_lang->save();

                $this->line('Language ' . $lang_name . ' created succesfully!');

                if ( $this->confirm('Do you want to create another language?',false) ) {
                    $langs++;
                } else {
                    break;
                }

            }
        }

        $this->line('All Done, enjoy your translatable content!');
        if ($this->confirm('All done! Would you like to show some love by starring our plugin on GitHub?', true)) {
            if (PHP_OS_FAMILY === 'Darwin') {
                exec('open https://github.com/geimsdin/filament-model-translatable');
            }
            if (PHP_OS_FAMILY === 'Linux') {
                exec('xdg-open https://github.com/geimsdin/filament-model-translatable');
            }
            if (PHP_OS_FAMILY === 'Windows') {
                exec('start https://github.com/geimsdin/filament-model-translatable');
            }

            $this->components->info('Thank you!');
        }

        return static::SUCCESS;
    }
}
