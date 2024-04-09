<!-- breadcrumb.blade.php -->
@props([
    'breadcrumbLinks'
])
<ol class="breadcrumb">
    @if ($breadcrumbLinks)
        @foreach($breadcrumbLinks as $index => $link)
            <li class="breadcrumb-item {{ $index === count($breadcrumbLinks) - 1 ? 'active' : '' }}">
                @if($link['url'] != '')
                    <a href="{{ $link['url'] }}">{{ $link['name'] }}</a>
                @else
                    {{ $link['name'] }}
                @endif
            </li>
        @endforeach
    @endif
</ol>