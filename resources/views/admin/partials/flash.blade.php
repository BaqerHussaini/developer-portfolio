@if (session('status'))
    <p class="flash">{{ session('status') }}</p>
@endif

@if ($errors->any())
    <p class="flash error">
        {{ $errors->first() }}
    </p>
@endif
