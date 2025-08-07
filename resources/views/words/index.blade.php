@extends('layouts.app-base')

@section('content')
    <div class="container" x-data="{ showMessage: false, message: '' }"
        @showMessage.window="showMessage = true; message = $event.detail.message; setTimeout(() => showMessage = false, 3000)">
        <!--検索機能コンポーネントビュー-->
        @livewire('search-words')

        <!-- 語句登録モーダルコンポーネントビュー-->
        @livewire('word-modal')

        <!-- フォーム送信成功時のフラッシュメッセージ -->
        <div x-show="showMessage" x-transition:duration.500ms class="alert alert-success" role="alert">
         <!--x-transition:要素の表示・非表示にアニメーションを追加-->
         <!--alert(Bootstrap):アラートの基本スタイルの適用-->
         <!--alert-success:アラートの背景を緑色にする-->
         <!--role="alert":アラートメッセージであることを明示する-->
            
            <span x-text="message"></span> <!--x-text="変数":Livewireから受け取った変数の値を表示する-->
        </div>

        @foreach ($words as $word)
            <article>
                <h2>{{ $word->word_name }}</h2>
                <p>{{ $word->description }}</p>
            </article>
        @endforeach
    </div>
@endsection
