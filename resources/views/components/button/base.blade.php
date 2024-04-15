@props([
    'type' => 'submit',
    'label',
    'isDisabled' => false,
    'id' =>'',
    'class' =>'',
])

<button {{ $attributes->merge(['type' => $type, 'class' => $class, 'disabled' => $isDisabled,'id'=>$id]) }}>
    {{ $label }}
</button>
