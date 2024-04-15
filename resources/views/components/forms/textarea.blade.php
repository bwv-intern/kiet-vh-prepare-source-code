@props(['label', 'name', 'id', 'value'])
<div class="form-group d-sm-flex align-items-center">
    <label class="col-sm-3" for="{{$id}}">{{ $label }}</label>
    <div class="col-sm-5">
        <textarea class="form-control" rows="3" name="{{ $name }}" id="{{ $id }}">{{ $value }}</textarea>
    </div>
</div><?php
