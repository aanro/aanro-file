<?php

namespace Someline\Component\File;


use Dingo\Api\Routing\Router as ApiRouter;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Someline\Models\File\SomelineFile;
use Someline\Repositories\Eloquent\SomelineFileRepositoryEloquent;
use Someline\Repositories\Interfaces\SomelineFileRepository;

class SomelineFileServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if (class_exists(SomelineFile::class)) {
            Relation::morphMap([
                SomelineFile::MORPH_NAME => SomelineFile::class,
            ]);
        }
        $this->loadMigrationsFrom(__DIR__ . '/../../../database/migrations');
        $this->publishes([
            __DIR__ . '/../../../config/config.php' => config_path('someline-file.php'),

            // master files
            __DIR__ . '/../../../master/Api/SomelineFile.php.dist' => app_path('Models/File/SomelineFile.php'),
            __DIR__ . '/../../../master/Api/SomelineFileRepository.php.dist' => app_path('Repositories/Interfaces/SomelineFileRepository.php'),
            __DIR__ . '/../../../master/Api/SomelineFileRepositoryEloquent.php.dist' => app_path('Repositories/Eloquent/SomelineFileRepositoryEloquent.php'),
            __DIR__ . '/../../../master/Api/SomelineFilesController.php.dist' => app_path('Api/Controllers/SomelineFilesController.php'),
            __DIR__ . '/../../../master/Api/SomelineFileTransformer.php.dist' => app_path('Transformers/SomelineFileTransformer.php'),
            __DIR__ . '/../../../master/Api/SomelineFileValidator.php.dist' => app_path('Validators/SomelineFileValidator.php'),
            __DIR__ . '/../../../master/Http/Console/SomelineFileController.php.dist' => app_path('Http/Controllers/Console/SomelineFileController.php'),

            // database
            __DIR__ . '/../../../database/seeds/SomelineFilesTableSeeder.php.dist' => base_path('database/seeds/SomelineFilesTableSeeder.php'),

            // resources folders
//            __DIR__ . '/../../../resources/assets/js/console' => resource_path('assets/js/components/console'),
//            __DIR__ . '/../../../resources/views/console' => resource_path('views/console'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../../config/config.php',
            'someline-file'
        );

        // repository
        if (interface_exists(SomelineFileRepository::class)) {
            $this->app->bind(SomelineFileRepository::class, SomelineFileRepositoryEloquent::class);
        }
    }

    public static function api_routes(ApiRouter $api, callable $callback = null)
    {

        // /files
        $api->group(['prefix' => 'files'], function (ApiRouter $api) use ($callback) {
            $callback && call_user_func($callback, $api);

            $api->get('/', 'SomelineFilesController@index');
            $api->post('/', 'SomelineFilesController@store');
            $api->get('/{id}', 'SomelineBoardsController@show')->where('id', '[0-9]+');
            $api->put('/{id}', 'SomelineFilesController@update')->where('id', '[0-9]+');
            $api->delete('/{id}', 'SomelineFilesController@destroy')->where('id', '[0-9]+');

        });

    }

    public static function console_routes(callable $callback = null)
    {
        \Route::group(['prefix' => 'files'], function () use ($callback) {
            $callback && call_user_func($callback);

            \Route::get('/', 'SomelineFileController@getFileList');
            \Route::get('/new', 'SomelineFileController@getFileNew');
            \Route::get('/{file_id}', 'SomelineFileController@getFileDetail');
            \Route::get('/{file_id}/edit', 'SomelineFileController@getFileEdit');

        });
    }

    public static function getConfig($name)
    {
        return config('someline-file.' . $name);
    }

    public static function isWithPackage($package_name)
    {
        $packages_config = self::getConfig('packages');
        return (isset($packages_config[$package_name]) && $packages_config[$package_name]);
    }

}