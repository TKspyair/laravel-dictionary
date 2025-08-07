<!--モーダルの開閉状態の管理-->
<div x-data="{ showModal: false }" @closeModal.window="showModal = false">
    <button @click="showModal = true" class="fab-button btn btn-primary rounded-circle shadow-lg">
        <i class="fas fa-plus"></i><!--「＋」マークの表示-->
    </button>

    <!-- モーダル本体 -->
    <div x-bind:class="{ 'modal': true, 'd-block': showModal }" tabindex="-1">
        <!--x-bind:属性名="条件式"-->
        <!--showModalがtrueの場合、modalクラスの「display: none」をd-blockで打ち消す　※x-showはbootstrapのmodalクラスと競合してしまうため不使用-->
        <!--「tabindex="-1"」モーダルが非表示時、要素がキーボードのタブ移動でフォーカスされないようにします。-->

        <div class="modal-dialog">
            <div class="modal-content">
                <form wire:submit.prevent="save">
                    <!--フォームの送信時にsaveメソッド(EloquentORMのメソッド：DBへのレコード保存)を実行
                      「.prevent」：ブラウザのデフォルトのフォーム送信を無効化し、ページのリロードをなくす-->

                    <!--モーダルヘッダー部-->
                    <div class="modal-header">
                        <h5 class="modal-title">新しい語句を追加</h5>

                        <!--モーダルの閉じるボタン(Alpineのみ)-->
                        <button type="button" class="btn-close" @click="showModal = false" aria-label="Close"></button>
                    </div>

                    <!--モーダルボディ部-->
                    <div class="modal-body">
                        <!--フォーム入力部-->

                        <!--語句名フィールド-->
                        <div class="mb-3">
                            <label for="word_name" class="form-label">語句</label>
                            <input id="word_name" name="word_name" type="text" class="form-control" wire:model="word_name" required>
                            <!--form-control:フォーム入力欄に一貫したスタイルとレイアウトを適用するBootstrapクラス-->
                            <!--wire:model="プロパティ"：フロント側のプロパティの状態をLivewireコンポーネント(サーバー側)にリアルタイムに同期する-->

                            <!--Livewireのバリデーションエラーメッセージ-->
                            @error('word_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!--語句説明フィールド-->
                        <div class="mb-3">
                            <label for="description" class="form-label">説明</label>
                            <textarea id="description" name="description" class="form-control" wire:model="description" rows="3" required></textarea>

                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!--保存ボタン-->
                    <div class="modal-footer d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
