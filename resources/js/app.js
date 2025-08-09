import './bootstrap'; //LaravelのデフォルトのJavaScriptファイルで、AxiosやLodashなどのライブラリをセットアップ


/*LivewireとAlpain.jsのベストプラクティス
Livewire v3では、Alpine.jsはLivewireのコア機能として組み込まれている。
Livewireが起動されると、内部的にAlpine.jsも連携して動作するように設計されている。
ただし、ViteのようなJavaScriptモジュールシステムを使用する場合、下記のように明示的に両方をインポートして起動する
*/
//Livewireの起動
import { createLaravelVitePlugin } from 'laravel-vite-plugin' //、LaravelのViteプラグインを初期化するための関数
import 'laravel-vite-plugin/plugins/livewire' //createLaravelVitePluginが読み込むLivewire専用のViteプラグイン
import { Livewire } from 'livewire' //Livewireのコアライブラリをインポート
Livewire.start() // Livewireを起動、Livewireコンポーネントがページ上で初期化され、動的な処理が可能になる

//Alpain.jsの起動
/*
import Alpine from 'alpinejs'  //Alpine.jsのコアライブラリをインポート
window.Alpine = Alpine //windowオブジェクトにAlpine.jsをグローバルに公開し、Livewireや他のスクリプトからAlpine.jsにアクセスできるようにする
Alpine.start()  //Alpine.jsを起動し、HTMLマークアップ内のx-dataなどのディレクティブを処理を可能にする
*/

