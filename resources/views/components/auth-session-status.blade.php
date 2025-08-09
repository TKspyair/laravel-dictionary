<!--親ビューから渡されたstatus属性を受け取り、$statusとして利用できるようにする-->
@props(['status'])

<!--$statusが存在すれば処理を行う-->
@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600']) }}>
        <!--
        $attributes:コンポーネントタグに渡されたすべてのHTML属性(class="mb-4"など)を保持する変数
        merge():デフォルトの属性と$attributesで保持する属性を結合する
        ['class' => 'font-medium text-sm text-green-600']：デフォルトの属性

        結果として<div class="mb-4 font-medium text-sm text-green-600">のように出力される
        -->
        {{ $status }}
    </div>
@endif
