<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
     //ログイン成功後のルーティング先の設定
    public const HOME = '/words/index';

    //RouteServiceProviderがLaravelによって起動（boot）される際に呼び出される
    public function boot(): void
    {
        //apiという名前のレートリミッターを定義
        RateLimiter::for('api', function (Request $request) 
        {
            /*レートリミッターの内容
            $requestでのユーザーID(ログイン済み)もしくはIPアドレス(未ログイン)を取得し、それらに1分間に60回までのアクセス許可する
            */
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
            // nullsafe演算子「?->」: 「?->」の前がnullでない場合のみ、その後の処理を実行する(nullの場合は式全体がnullになることでエラーを防ぐ)
            // Elvis演算子「?:」:「条件式 ?: false」のように使う三項演算子「条件式 ? true : false」からtrueの場合の処理を省略したもの
        });

        //ルートファイル(web.phpなどroutes配下のファイル)を読み込むグループの定義
        $this->routes(function ()
        {
            //routes/api.php内の全ルートにapiミドルウェアグループとを適用
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php')); // 上記の設定をroutes/api.phpに適用し、読み込み
                /*
                prefix():Laravelのルーティングメソッド、指定の接頭辞(プレフィックス)を付与する　
                group():共通の設定を適用したいルートをまとめる
                base_path():指定したディレクトリやファイルからルートディレクトリへの絶対パスを返す
                */

            Route::middleware('web')
                ->group(base_path('routes/web.php')); 
        });
    }
}
