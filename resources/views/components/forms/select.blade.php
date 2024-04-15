@props(['label', 'name', 'id', 'options', 'selected'])

<div class="input-group">
    <label class="col-3 align-items-center" for="{{ $id }}">{{ $label }}</label>
    <div class="col-sm-5">
        <select class="form-control" name="{{ $name }}" id="{{ $id }}">
            @foreach ($options as $optionValue => $optionLabel)
                <option
                    value="{{ $optionValue }}" {{ $optionValue == $selected ? 'selected' : '' }}>{{ $optionLabel }}</option>
            @endforeach
        </select>
    </div>
</div>
