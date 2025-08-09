@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 space-y-1']) }}>
        @foreach ((array) $messages as $message) <!--　「「(array)　※キャスト演算子」：　直後にある変数を強制的に指定した型に変換する-->
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
