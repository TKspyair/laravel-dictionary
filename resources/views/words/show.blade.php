@extends('layouts.app-base')

@section('content')
<a href="{{ route('words.index') }}">&lt; 戻る</a>

<article>
    <h2>{{ $word->word }}</h2>
    <p>{{ $word->description }}</p>
</article>
@endsection
