@props(['disabled' => false])
<!-- @ props構文
配列で属性を受け取る： @ props(['属性名1', '属性名2', ...])
デフォルト値の設定をする： @ props(['属性名' => 'デフォルト値'])
-->
<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!}>
<!--
PHP三項演算子：(条件式) ? (真の場合の値) : (偽の場合の値);　シンプルなif-else文を1行で記述できる
「!!~!!」:Bladeの非エスケープ処理、HTMLタグなどを文字列に変換せず、そのまま出力する
-->
