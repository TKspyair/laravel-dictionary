<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Word; //DBのwordsテーブルを操作する
use Illuminate\Support\Facades\Auth; //認証機能を提供するファサード

class WordModal extends Component
{
 //word-modal.blade.php内のフォームの入力値
 public $word_name = '';
 public $description = '';

 //フォームのバリデーションルール
 protected $rules = [
  'word_name' => 'required',
  'description' => 'required'
 ];

 //フォーム送信時に発火し、フォーム入力値をDBレコードとして保存
 public function save()
 {
  $this->validate();

  Word::create([
   'word_name' => $this->word_name,
   'description' => $this->description,
   'user_id' => Auth::id()
  ]);

  $this->reset(['word_name', 'description']);

  // フラッシュメッセージを親コンポーネントに送信
  $this->dispatch('showMessage', message: '保存完了'); //dispatch('イベント名',キー名: '値')

  // Alpine.jsにモーダルを閉じるイベントを発火
  $this->dispatch('closeModal');
 }

 public function render()
 {
  return view('livewire.word-modal');
 }
}
