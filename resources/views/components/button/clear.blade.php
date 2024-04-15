@props([
    'label' => '',
    'screen' => '',
    'id' =>'btn-clear',
    'class'=> ''
])

<button
	type="button"
	data-url="{{ route('common.resetSearch') }}"
    data-screen="{{ $screen }}"
    id="{{ $id }}"
    class="{{$class}}"
>
    {{ $label }}
</button>
