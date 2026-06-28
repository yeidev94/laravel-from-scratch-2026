
    @props([
        'name'=>'required'
    ])

    {{-- @if ($errors->has('description'))
        <p class="text-xs text-red-500">{{ $errors->first('description') }}</p>
    @endif --}}

    @error($name)
        <p class="text-xs text-error">{{ $message }}</p>
    @enderror