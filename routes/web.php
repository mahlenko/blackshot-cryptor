<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//URL::forceScheme('https');

Auth::routes([
    'register' => true,
    'verify' => false,
    'reset' => true
]);

/**
 * Dashboard routes
 */
Route::prefix(config('anita.dashboard.prefix'))
    ->middleware('auth')
    ->middleware('administration')
    ->name('admin.')
    ->group(function() {
        /* Dashboard */
        Route::get('/', [config('anita.dashboard.home.class'), config('anita.dashboard.home.method')])->name('home');

        /* Editor settings */
        Route::name('editor.')
            ->prefix('editor')
            ->group(function() {
                Route::get('templates', [\App\Http\Controllers\Administrator\Editor\Templates::class, 'index'])->name('templates');
            });

        /* Страницы */
        Route::prefix('page')
            ->name('page.')
            ->group(function() {
                Route::get('/{uuid?}', [App\Http\Controllers\Administrator\Page\Index::class, 'index'])->name('home');
                Route::get('/edit/{locale}/{uuid?}', [App\Http\Controllers\Administrator\Page\Edit::class, 'index'])->name('edit');
                Route::post('/store', [App\Http\Controllers\Administrator\Page\Store::class, 'index'])->name('save');
                Route::post('/delete', [App\Http\Controllers\Administrator\Page\Delete::class, 'index'])->name('delete');
                Route::post('/sortable', [App\Http\Controllers\Administrator\Page\Sortable::class, 'index'])->name('sortable');
            });

        /* Навигация */
        Route::prefix('navigation')
            ->name('navigation.')
            ->group(function() {
                Route::get('/', [App\Http\Controllers\Administrator\Navigation\Index::class, 'index'])->name('home');
                Route::get('/edit/{uuid?}', [\App\Http\Controllers\Administrator\Navigation\Edit::class, 'index'])->name('edit');
                Route::post('/store', [\App\Http\Controllers\Administrator\Navigation\Store::class, 'index'])->name('store');
                Route::post('/delete', [\App\Http\Controllers\Administrator\Navigation\Delete::class, 'index'])->name('delete');

                /* Ссылки в навигации */
                Route::prefix('items')
                    ->name('items.')
                    ->group(function() {
                        Route::get('/{uuid}', [\App\Http\Controllers\Administrator\Navigation\Items\Index::class, 'index'])->name('home');
                        Route::get('/{uuid}/edit/{locale?}/{navigation_item:uuid?}', [\App\Http\Controllers\Administrator\Navigation\Items\Edit::class, 'index'])->name('edit');
                        Route::post('/store', [\App\Http\Controllers\Administrator\Navigation\Items\Store::class, 'index'])->name('store');
                        Route::post('/delete', [\App\Http\Controllers\Administrator\Navigation\Items\Delete::class, 'index'])->name('delete');
                        Route::post('/sortable', [\App\Http\Controllers\Administrator\Navigation\Items\Sortable::class, 'index'])->name('sortable');
                        Route::post('/ajax/objects-type', [\App\Http\Controllers\Administrator\Navigation\Items\ObjectsType::class, 'index'])->name('objects');
                    });
            });

        /* Finder */
        Route::prefix('finder')
            ->name('finder.')
            ->group(function() {
                Route::get('/{uuid?}', [\App\Http\Controllers\Administrator\Finder\Editor::class, 'index'])->name('home');
                Route::get('/file/edit/{locale}/{uuid}', [\App\Http\Controllers\Administrator\Finder\FileEdit::class, 'index'])->name('file.edit');
                Route::post('/upload/editor', [\App\Http\Controllers\Administrator\Finder\EditorUpload::class, 'index'])->name('editor.upload');
                Route::post('/upload', [\App\Http\Controllers\Administrator\Finder\Upload::class, 'index'])->name('upload');
                Route::post('/delete', [\App\Http\Controllers\Administrator\Finder\Delete::class, 'index'])->name('delete');
                Route::post('/sortable/{uuid?}', [\App\Http\Controllers\Administrator\Finder\Sortable::class, 'index'])->name('sortable');
                Route::post('/sortable/folder/{uuid?}', [\App\Http\Controllers\Administrator\Finder\Sortable::class, 'folder'])->name('sortable.folder');
                Route::post('/store', [\App\Http\Controllers\Administrator\Finder\FileStore::class, 'index'])->name('file.store');

                Route::prefix('folder')
                    ->name('folder.')
                    ->group(function() {
                        Route::get('/create/{uuid}', [\App\Http\Controllers\Administrator\Finder\Folder\Create::class, 'index'])->name('create');
                        Route::post('/create', [\App\Http\Controllers\Administrator\Finder\Folder\Store::class, 'index'])->name('store');
                    });
        });

        /* Каталог */
        Route::prefix('catalog')
            ->name('catalog.')
            ->group(function() {
                Route::get('/', [\App\Http\Controllers\Administrator\Catalog\Index::class, 'index'])->name('home');

                /* категории */
                Route::prefix('category')
                    ->name('category.')
                    ->group(function() {
                        Route::get('/{uuid?}', [\App\Http\Controllers\Administrator\Catalog\Category\Index::class, 'index'])->name('home');
                        Route::get('edit/{locale}/{uuid?}', [\App\Http\Controllers\Administrator\Catalog\Category\Edit::class, 'index'])->name('edit');
                        Route::get('/categories', [\App\Http\Controllers\Administrator\Catalog\Category\Modal::class, 'index'])->name('categories');
                        Route::post('/store', [\App\Http\Controllers\Administrator\Catalog\Category\Store::class, 'index'])->name('store');
                        Route::post('/delete', [\App\Http\Controllers\Administrator\Catalog\Category\Delete::class, 'index'])->name('delete');
                        Route::post('/sortable', [\App\Http\Controllers\Administrator\Catalog\Category\Sortable::class, 'index'])->name('sortable');
                    });

                /* Характеристики */
                Route::prefix('feature')
                    ->name('feature.')
                    ->group(function() {
                        Route::get('/', [\App\Http\Controllers\Administrator\Catalog\Feature\Index::class, 'index'])->name('home');
                        Route::get('/edit/{locale}/{uuid?}', [\App\Http\Controllers\Administrator\Catalog\Feature\Edit::class, 'index'])->name('edit');
                        Route::get('/page/edit/{locale}/{uuid?}', [\App\Http\Controllers\Administrator\Catalog\Feature\Page::class, 'index'])->name('page.edit');
                        Route::post('/page/store', [\App\Http\Controllers\Administrator\Catalog\Feature\PageStore::class, 'index'])->name('page.store');
                        Route::post('/params/view_product', [\App\Http\Controllers\Administrator\Catalog\Feature\ViewOptions::class, 'product'])->name('json.product');
                        Route::post('/params/view_filter', [\App\Http\Controllers\Administrator\Catalog\Feature\ViewOptions::class, 'filter'])->name('json.filter');
                        Route::post('/store', [\App\Http\Controllers\Administrator\Catalog\Feature\Store::class, 'index'])->name('store');
                        Route::post('/delete', [\App\Http\Controllers\Administrator\Catalog\Feature\Delete::class, 'index'])->name('delete');
                        Route::post('/sortable', [\App\Http\Controllers\Administrator\Catalog\Feature\Sortable::class, 'index'])->name('sortable');
                        Route::post('/sortable/variants', [\App\Http\Controllers\Administrator\Catalog\Feature\Sortable::class, 'variants'])->name('sortable.variants');
                    });

                /* Группы характеристик */
                Route::prefix('feature-group')
                    ->name('feature.group.')
                    ->group(function() {
                        Route::get('/', [\App\Http\Controllers\Administrator\Catalog\FeatureGroup\Index::class, 'index'])->name('home');
                        Route::get('/edit/{locale}/{uuid?}', [\App\Http\Controllers\Administrator\Catalog\FeatureGroup\Edit::class, 'index'])->name('edit');
                        Route::post('/store', [\App\Http\Controllers\Administrator\Catalog\FeatureGroup\Store::class, 'index'])->name('store');
                        Route::post('/delete', [\App\Http\Controllers\Administrator\Catalog\FeatureGroup\Delete::class, 'index'])->name('delete');
                        Route::post('/sortable', [\App\Http\Controllers\Administrator\Catalog\FeatureGroup\Sortable::class, 'index'])->name('sortable');
                    });

                /* Товары */
                Route::prefix('product')
                    ->name('product.')
                    ->group(function() {
                        Route::get('/', [\App\Http\Controllers\Administrator\Catalog\Product\Index::class, 'index'])->name('home');
                        Route::get('/edit/{locale}/{uuid?}', [\App\Http\Controllers\Administrator\Catalog\Product\Edit::class, 'index'])->name('edit');
                        Route::post('/copy', [\App\Http\Controllers\Administrator\Catalog\Product\Copy::class, 'index'])->name('copy');
                        Route::post('/store', [\App\Http\Controllers\Administrator\Catalog\Product\Store::class, 'index'])->name('store');
                        Route::post('/delete', [\App\Http\Controllers\Administrator\Catalog\Product\Delete::class, 'index'])->name('delete');
                        Route::post('/sortable', [\App\Http\Controllers\Administrator\Catalog\Product\Sortable::class, 'index'])->name('sortable');
                        Route::post('/sortable/category/{uuid}', [\App\Http\Controllers\Administrator\Catalog\Product\Sortable::class, 'category'])->name('sortable.category');
                        Route::post('/ajax/update/variant-features', [\App\Http\Controllers\Administrator\Catalog\Product\AjaxUpdate::class, 'variantFeatures'])->name('ajax.update.feature');
                        Route::post('/ajax/update/params', [\App\Http\Controllers\Administrator\Catalog\Product\AjaxUpdate::class, 'productParams'])->name('ajax.update.params');
                    });

                /* Группы товаров */
                Route::prefix('variation')
                    ->name('variation.')
                    ->group(function() {
                        Route::get('/{uuid}', [\App\Http\Controllers\Administrator\Catalog\Variation\Index::class, 'index'])->name('home');
                        Route::post('/add', [\App\Http\Controllers\Administrator\Catalog\Variation\Store\AddProduct::class, 'index'])->name('add');
                        Route::post('/create', [\App\Http\Controllers\Administrator\Catalog\Variation\Store\CreateProduct::class, 'index'])->name('create');
                        Route::post('/disband', [\App\Http\Controllers\Administrator\Catalog\Variation\Disband::class, 'index'])->name('disband');
                        Route::post('/remove-product', [\App\Http\Controllers\Administrator\Catalog\Variation\RemoveProduct::class, 'index'])->name('remove.product');
                    });
            });

        /* Пользователи */
        Route::prefix('user')
            ->name('user.')
            ->group(function() {
                Route::get('/', [\App\Http\Controllers\Administrator\User\Index::class, 'index'])->name('home');
                Route::get('edit/{id}', [\App\Http\Controllers\Administrator\User\Edit::class, 'index'])->name('edit');
                Route::post('store', [\App\Http\Controllers\Administrator\User\Store::class, 'index'])->name('store');
                Route::post('delete', [\App\Http\Controllers\Administrator\User\Delete::class, 'index'])->name('delete');
            });

        /* Виджеты */
        Route::prefix('widget')
            ->name('widget.')
            ->group(function() {
                Route::get('/', [\App\Http\Controllers\Administrator\Widget\Index::class, 'index'])->name('home');
                Route::get('/edit/{uuid?}', [\App\Http\Controllers\Administrator\Widget\Edit::class, 'index'])->name('edit');
                Route::post('/store', [\App\Http\Controllers\Administrator\Widget\Store::class, 'index'])->name('store');
                Route::post('/delete', [\App\Http\Controllers\Administrator\Widget\Delete::class, 'index'])->name('delete');
                Route::post('/api', [\App\Http\Controllers\Administrator\Widget\Api::class, 'index'])->name('api');
            });

        /* Компания */
        Route::prefix('company')
            ->name('company.')
            ->group(function() {
                Route::get('/', [\App\Http\Controllers\Administrator\Company\Index::class, 'index'])->name('home');
                Route::get('/edit/{locale}/{uuid?}', [\App\Http\Controllers\Administrator\Company\Edit::class, 'index'])->name('edit');
                Route::post('/store', [\App\Http\Controllers\Administrator\Company\Store::class, 'index'])->name('store');
                Route::post('/sortable', [\App\Http\Controllers\Administrator\Company\Sortable::class, 'index'])->name('sortable');
                Route::post('/delete', [\App\Http\Controllers\Administrator\Company\Delete::class, 'index'])->name('delete');
            });

        /* Настройки */
        Route::prefix('setting')
            ->name('setting.')
            ->group(function() {
                Route::prefix('website')
                    ->name('website.')
                    ->group(function() {
                        Route::get('/{locale}', [\App\Http\Controllers\Administrator\Setting\Website\Index::class, 'index'])->name('home');
                        Route::get('/delete-file/{locale}', [\App\Http\Controllers\Administrator\Setting\Website\DeleteFile::class, 'index'])->name('delete-file');
                        Route::post('/store', [\App\Http\Controllers\Administrator\Setting\Website\Store::class, 'index'])->name('store');
                    });

                Route::get('clear-cache', function() {
                    $result = \Illuminate\Support\Facades\Artisan::call('cache:clear');
                    if ($result === 0) {
                        flash(__('settings.clear-cache.success'))->success();
                    } else {
                        flash(__('settings.clear-cache.fail'))->error();
                    }
                    return back();
                })->name('clear.cache');
            });

        /* Видео */
        Route::prefix('video')
            ->name('video.')
            ->group(function () {
                Route::get('edit/{locale}/{uuid}', [\App\Http\Controllers\Administrator\Video\Edit::class, 'index'])->name('edit');
                Route::get('reset/{uuid}', [\App\Http\Controllers\Administrator\Video\Reset::class, 'index'])->name('reset');
                Route::post('store', [\App\Http\Controllers\Administrator\Video\Store::class, 'index'])->name('store');
                Route::post('delete', [\App\Http\Controllers\Administrator\Video\Delete::class, 'index'])->name('delete');
                Route::post('sortable', [\App\Http\Controllers\Administrator\Video\Sortable::class, 'index'])->name('sortable');
            });

        /* Шаблоны */
        Route::prefix('template')
            ->name('template.')
            ->group(function() {
                Route::get('/', [\App\Http\Controllers\Administrator\Template\Index::class, 'index'])->name('home');
                Route::post('edit', [\App\Http\Controllers\Administrator\Template\Edit::class, 'index'])->name('edit');
            });
    });

/**
 * Home page
 */
Route::get('/', [\App\Http\Controllers\Page::class, 'index'])->name('home');
Route::get('/search', [\App\Http\Controllers\Search::class, 'index'])->name('search');
Route::get('/sitemap.xml', [\App\Http\Controllers\Sitemap::class, 'index'])->name('sitemap');

/**
 * Форма обратной связи
 */
Route::middleware('throttle:60,1')
    ->post('feedback', [\App\Http\Controllers\Feedback::class, 'index'])
    ->name('feedback');

/**
 * Каталог
 * При смене ссылки на каталог, обязательно сделать изменения
 */
Route::middleware('throttle:60,1')
    ->post('catalog/filterCountProducts', [App\Http\Controllers\Catalog\Category::class, 'countProducts'])
    ->name('find_count_product');

Route::middleware('throttle:30,1')
    ->post('catalog/sortable', [App\Http\Controllers\Catalog\Category::class, 'sortable'])
    ->name('category_sortable');

Route::get('{catalog}', [App\Http\Controllers\Catalog\Category::class, 'index'])
    ->where('catalog', '(catalog)(.*)')->name('catalog');

Route::get('{product}', [App\Http\Controllers\Catalog\Product::class, 'index'])
    ->where('product', '(product)(.*)')->name('product');

/**
 * Скачивание файлов
 */
Route::get('file/download/{uuid}', [\App\Http\Controllers\File\Download::class, 'index'])->name('download');

/**
 * All pages
 */
Route::get('/{slug}', [\App\Http\Controllers\Page::class, 'view'])->name('view')
    ->where('slug', '.*');
