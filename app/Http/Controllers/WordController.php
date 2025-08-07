<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Word;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class WordController extends Controller
{
 public function index()
 {
  $words = Auth::user()->words()->orderBy('created_at', 'desc')->get();
  //Auth::user()->words()->get()で現在ログイン中のユーザーの登録した語句を取得
  //words()の()を記述し忘れると、クエリビルダではなくコレクションとして扱われエラーになるので注意する
  //orderBy('created_at', 'desc')で取得したデータを作成日時が新しい順に並び替える

  return view('words.index', compact('words')); //上記で定義した$wordsをcompact()でwords.indexに渡す
 }
 //create()、store()、show()は不要(LIvewireで処理)

}
