<?php

namespace App\Livewire\Forms;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter; //ログイン試行回数を制限し、ブルートフォース攻撃を防ぐための機能
use Illuminate\Support\Str; //文字列操作に関する便利なメソッドが多数含まれているクラス
use Illuminate\Validation\ValidationException; //バリデーションエラーを処理するための例外クラス
use Livewire\Attributes\Validate; //Livewireが提供する属性で、プロパティへのバリデーションルールを定義
use Livewire\Form;

class LoginForm extends Form
{
    /**従来のバリデーション(Laravel)：メソッドごとに、それぞれプロパティにバリデーションを定義する
    
    public string $name = '';
    public string $email = '';

    public function save()
    {
        //メソッドごとにバリデーションを記述
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
        ]); 
        
        ～処理～
    }
    */
    
    /**Livewireコンポーネントの#[Validate(...)]属性でのバリデーション：プロパティ自体にバリデーションルールを設定
     * メソッドごとにバリデーションルールを設定しなくてよい
    
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate(['required', 'email', 'unique:users'])]
    public string $email = '';

    バリデーションの記述は不要
    public function save()
    {
        ～処理～
    }
    */

    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    //ログイン状態の記録のチェックボックスの状態管理
    #[Validate('boolean')]
    public bool $remember = true; //trueでデフォルトでチェックボックスにチェックが入る

    /**
     * Attempt to authenticate the request's credentials.
     * リクエストの認証情報を検証を試行する
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    
    public function authenticate(): void
    {
        //ログイン試行回数のチェック
        $this->ensureIsNotRateLimited();
        //ensureIsNotRateLimited():レートリミットが適用されているかの確認　※「ensure」:～を保証する、確実にする
        //レートリミット：特定の時間内にユーザーやIPアドレスから送信されるリクエストの数を制限する機能

        //ユーザー認証
        if (! Auth::attempt($this->only(['email', 'password']), $this->remember)) {  
        //Auth::attempt('認証情報のプロパティ', 'ログイン情報の記録有無のプロパティ(boolean)'):認証の試行するメソッド(Laravel)、booleanを返す　※「attempt」:試す
        //$this->only(['プロパティ1', 'プロパティ2']):引数に指定したプロパティだけを配列として取得するヘルパーメソッド(Livewire)

            //試行の失敗回数を1増やす
            RateLimiter::hit($this->throttleKey());
            //throttleKey()：レートリミットにおいて、制限をかけるためのキーを生成するメソッド
            //hit():レートリミットにおいて、試行回数を増やすメソッド

            //認証失敗のバリデーションエラーを発生させる
            throw ValidationException::withMessages([ //ValidationException::withMessages('入力するフィールド名', '表示したいエラーメッセージ')

                //Laravelの言語ファイルからauth.failedに対応するエラーメッセージを取得
                'form.email' => trans('auth.failed'),
            ]);
        }

        //認証に成功した場合、ログイン試行回数をリセットする
        RateLimiter::clear($this->throttleKey()); 
        //clear():レートリミットにおいて、試行回数をリセットするメソッド
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    
    //上記のauthenticate()関数内で呼び出される関数
    protected function ensureIsNotRateLimited(): void
    {
        //ログイン試行回数が5回を超えていなければ処理を終了し、認証の処理を続行する
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
            /*ガード句：処理を続行するための前提条件を満たしているかを条件式でで確認し、満たしている場合に処理を終了させるためのif文
            [例]
            if(条件式)｛ return; ｝
            */
        }

        //Lockoutイベントを発生させ、リクエストオブジェクトを渡す。
        event(new Lockout(request()));
        //event():Laravelのヘルパー関数、アプリケーション内で特定の「イベント」を発生させる
        //Lockout:Laravelが認証失敗時のレートリミット処理のために内部的に用意しているクラス

        //次にログイン試行可能になるまでの時間を秒単位で取得
        $seconds = RateLimiter::availableIn($this->throttleKey());

        
        throw ValidationException::withMessages([
            'form.email' => trans('auth.throttle', [
                'seconds' => $seconds, 
                //'minutes'に対応する翻訳ファイルがないため、コメントアウト
                //'minutes' => ceil($seconds / 60), //ceil():引数の切り上げを行うPHPメソッド
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        //ユーザーの情報を　「test@example.com|192.168.1.100」　の形式で取得
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
        //request()->ip()：ユーザがログインリクエストを送信したIPアドレスを取得
        //transliterate():文字列をASCIIに変換
        //Str::lower():メールアドレスをすべて小文字に変換

    }
}
