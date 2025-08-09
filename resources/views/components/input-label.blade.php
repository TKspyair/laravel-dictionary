@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }}>

    <!--ラベル名を表示-->
    {{ $value ?? $slot }}
    <!--「??」: PHPの合体演算子で、変数が存在しない場合にデフォルト値を設定する
            [例]: $変数　?? デフォルト値
        $slot:Bladeコンポーネントタグの開始タグと終了タグの間に記述されたコンテンツを挿入するための、特別な変数
    -->
</label>
