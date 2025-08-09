<?php

use App\Models\User;
use App\Providers\RouteServiceProvider; //ログイン後のリダイレクト先（HOME）を定義
use Illuminate\Auth\Events\Registered; //ユーザー登録が完了した際に発生させるイベント
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; //スワードを安全な文字列に変換（ハッシュ化）
use Illuminate\Validation\Rules;  //パスワードの強度など、高度な入力値チェックのルールを定義
use Livewire\Attributes\Layout; //このコンポーネントが使うレイアウトファイルを指定
use Livewire\Volt\Component;

//共通レイアウトの指定
new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    
    /*#[Validate]属性を使用しない理由 -Livewireの2つのバリデーション-
    
    1. $this->validate(): registerのようなアクションメソッドの中で呼び出す。
    フォームが送信された特定のタイミングで一度だけバリデーションを実行したい場合に適しています。
    
    2. #[Validate]属性: コンポーネントのプロパティに直接ルールを記述する。
    ユーザーが入力フィールドを更新するたびにリアルタイムでバリデーションを行いたい場合に非常に便利です。

    まとめ： この登録フォームでは、ユーザーが全ての情報を入力し、「Register」ボタンを押したタイミングで一度だけ検証すれば十分です。
    そのため、registerメソッド内で$this->validate()を呼び出す方法が、処理の流れを追いやすく、直接的で分かりやすい実装となっています
    */
    
    //ユーザー登録処理
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()], 
            //「Rules\Password::defaults()」：Laravel推奨のパスワード強度(Rules\Password::defaults())を満たしているかの確認
        ]);

        //パスワードのハッシュ化
        $validated['password'] = Hash::make($validated['password']);

        //バリデーション済みのデータで、新しいユーザーをDBに作成
        event(new Registered($user = User::create($validated)));
        /* create()の処理の流れ
        1 $user = new User()：Userモデルのインスタンスを作成 
        2 fil()：でモデルに定義されてる$fillableにリストアップされているキーの値のみインスタンスにセット
        3 save()：DBにインスタンスにセットされた値をインサート(挿入する)
        */

        //ユーザー登録後、自動ログイン
        Auth::login($user);

        $this->redirect(RouteServiceProvider::HOME, navigate: true);
    }
}; ?>

<div>
    <form wire:submit="register">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>
