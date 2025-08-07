<div>
    <!--検索バーの入力値をLivewireで管理し、Alpine.jsで検索結果を表示するコンポーネントビュー-->
    <!--app\Livewire\SearchWords.php-->
    <input id="search_word" name="search_word" wire:model.debounce.300ms="search" type="text" class="form-control" placeholder="語句を検索...">

    @if (!empty($results))
        <ul class="list-group mt-2">
            @foreach ($results as $result)
                <li class="list-group-item">
                    <h5>{{ $result->word_name }}</h5>
                </li>
            @endforeach
    @endif
</div>
