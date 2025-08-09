<?php

//use App\Http\Controllers\ProfileController; BreezeがBladeタイプの時に使用
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\WordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Breeze(Volt Class API)version
/*
Route::view('URL', 'ビュー名')でルーティング
※コントローラーや無名関数を介さずに、ビューとロジックを1つのファイルにできる
*/
Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

//wordsフォルダ内へのルーティング
Route::get('/words', [WordController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('words.index');
//コントローラー内のメソッドを指定してルーティングする場合は、コントローラー内にreturn view()でルーティング先を定義する
//name('words.index')があることで、ルート名でURL生成ができる　例：<a href="{{ route('words.index') }}


require __DIR__.'/auth.php';



/*Breeze(Blade)version
/*
Route::get('URL', function(){return view('ビュー名')})
もしくは
Route::get('URL', [コントローラー名::class, 'ビュー名'])->middleware(['auth', 'verified'])->name('ビューの相対パス');
でルーティング
※MVCモデルを使用するので、コードの役割が明確
*/

/*
Route::get('/', function () {//無名関数でルーティングする場合、return view()でレスポンスする必要がある。
 return view('welcome');
});

Route::get('/dashboard', function () {
 return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
 Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
 Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
 Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
*/

