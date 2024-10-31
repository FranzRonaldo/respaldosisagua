@props(['field'])

@error($field)
    <div class="alert alert-danger mt-2 p-1">{{ $message }}</div>
@enderror