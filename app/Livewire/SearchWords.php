<?php

namespace App\Livewire; //語句検索バーの入力値をLivewireで管理し、Alpine.jsで検索結果を表示するコンポーネント
use Livewire\Component;
use App\Models\Word;

class SearchWords extends Component
{
 public $search = ''; // 検索バーの入力値を保持
 public $results = []; // 検索結果を保持

 public function updatedSearch() // Alpine.js において、特定のコンポーネントの状態が更新されたときに、その変更をLivewireに伝えるための慣習的なメソッド名
 {
  // 検索文字列が1文字以上の場合にのみ検索を実行
  if (strlen($this->search) > 0) { //文字が入力されているかの真偽判定のため、mb_strlenは不使用(英文字入力にも対応)
   $this->results = Word::where('word_name', 'like', '%' . $this->search . '%')->get(); //(検索対象,検索方法を指定する演算子,検索条件となる値)条件に合致するすべてのレコードをDBから取得
  } else {
   $this->results = []; // 検索文字列が1文字未満の場合、$this->result内の配列をクリア
  }
 }

 public function render() //Livewireコンポーネントのビューをレンダリング（描画）するためのメソッド
 {
  return view('livewire.search-words');
 }
}
