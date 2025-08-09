<?php

use App\Livewire\Forms\LoginForm; //ログインフォームのロジックを記述
use App\Providers\RouteServiceProvider; //アプリのルート設定、ログイン後のルーティング設定
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout; //Lvewireコンポーネントにレイアウトを指定する属性
use Livewire\Volt\Component;


// #[Layout('layouts.guest')]: Livewire Voltの構文で、このコンポーネントが使用するBladeレイアウトを指定
new #[Layout('layouts.guest')] class extends Component 
{
    //LoginFormクラスを$formと定義し、メソッドなどを使用可能にする
    public LoginForm $form;
    /**フォームオブジェクト(v3以降)
     * 従来のLivewire(v3以前)：ログインフォームのemail、password、rememberといったプロパティをすべてコンポーネントクラスの直下に定義
     * Livewire(v3以降)：関連するプロパティやバリデーションルール、認証ロジックといったものを、
        LoginFormという別のクラスにまとめて定義でき、そのLoginFormクラスのインスタンスを、コンポーネントのプロパティとして扱う。
    */

    /**
     * Handle an incoming authentication request.
     */

     //フォーム送信後に呼び出される
    public function login(): void
    {
        // クラスに定義されている#[Validate(...)]属性に基づき、バリデーションを実行
        $this->validate();
        /* $this->form->validate();と記述しない理由
            $formは、Livewireコンポーネントプロパティであり、
            validate()は、Livewireコンポーネントインスタンス（$this）が持つメソッドのため
        */

        $this->form->authenticate();

        //セッションIDの再生成　※※セッション固定攻撃を防ぐためのセキュリティ対策
        Session::regenerate();

        //ログイン後のリダイレクト
        $this>redirect(RouteServiceProvider::HOME,navigate: true); //お試し
        
        //$this->redirectIntended(default: RouteServiceProvider::HOME, navigate: true);
        
        /*redirectIntended():Livewire\Componentクラスのメソッド、認証に特化したリダイレクト機能 ※Laravelのredirect()->intended()と機能は一緒
        navigate: true :「Livewire Nav」というページ遷移を高速化する仕組みを実装するオプション
        　リダイレクト時に通常はページ全体をリロードするが、このオプションによりリロードを阻止し、Livewireにより非同期通信で最小限の変更要素のみを反映する。
        
        ※ redirect()：汎用的なリダイレクト
        ※intended():ユーザーがログイン前にアクセスしようとした元のページにリダイレクトさせるメソッド、元のページがない場合引数で指定されたデフォルトのURLにリダイレクトする
        */
    
    }
}; ?>

<div>
    <!-- セッションに保存されたメッセージを表示する -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <!--
    「x-コンポーネント名」：BladeコンポーネントというHTMLのタグ形式でコンポーネントを呼び出す形式
    「：」：Bladeコンポーネントの属性にPHPの式や変数の値を渡すためのもの　※「：」がない場合上記のsession('status')はただの文字列として扱われる
    session('キー')：セッションに保存されているキーの値を取得
    「/>」(自己終了タグ):開始タグ「< >」と終了タグ「</ >」をまとめたもの、タグの間にコンテンツがないことを意味する　※Bladeコンポーネントにおいては、「$slot」が必要ないことを明示する
    -->

    <!--フォーム送信時にlogin()を呼び出す-->
    <form wire:submit="login">
        
        <!-- Email Address -->
        <div>
            <!--labelのテキストとHTML属性を管理-->
            <x-input-label for="email" :value="__('Email')" /> <!--__('Email'):Laravelの多言語化ヘルパー関数で、ユーザーの言語設定に応じて翻訳される　[例]：メールアドレス(jp)-->

            <!--email入力フォーム-->
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email" required autofocus autocomplete="username" />

            <!--エラーメッセージの出力-->
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full"
                            type="password" 
                            name="password"
                            required autocomplete="current-password" /> 
                            <!--
                            ・「type="password"」：入力された文字を隠せる
                            ・「required」:入力が必須であることを示す(bool型)
                            ・「autocomplete」:フォームへの自動入力機能
                            ・「currrent-password」:現在ログインしているユーザーのパスワード
                            -->

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</div>
