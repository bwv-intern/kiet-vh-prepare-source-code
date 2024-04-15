@props([
    'label',
    'type' => 'checkbox',
    'name',
    'isDisabled' => false,
    'options' => [],
    'valueChecked' => [],
    'isArray' => true,
    'ratioBreak' => null,
])

@php
    if (empty($id)) {
        $id = str_contains($name, '_') ? str_replace('_', '-', $name) : $name;
    }
@endphp

@foreach($options as $key => $value)
    <label class="{{ 'mr-5' . $attributes['classCheckbox'] }}">
        <input
            {{
                $attributes->merge([
                'type' => $type,
                'name' => $isArray ? $name . '[]' : $name,
                'id' => $isArray ? $id . '-' .$key : $id,
                'data-label' => $label,
                'class' => 'form-check-input i-checkbox',
                'value' => $key,
                'disabled' => $isDisabled,
                'checked' => is_array($valueChecked) && in_array($key, $valueChecked) || $key == $valueChecked,
            ]) }}
        />
        <span class="form-check-label font-weight-normal">{{ $value }}</span>
    </label>
    @if (!empty($ratioBreak) && ($key + 1) % $ratioBreak == 0)
        <br />
    @endif
@endforeach
