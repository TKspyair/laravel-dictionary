<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

//語句登録のフォームリクエストのバリデーションを行う
class WordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();//ログイン中の場合のみリクエストが実行される。※return trueにすると認証をスキップして誰でもリクエストを送れるようになる。
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'word_name' => 'required', //'required'はフィールドに何らかの値が入力されているかチェックするバリデーションルール
            'description' => 'required'                        
        ];
    }
}
