<x-app-layout title="Top">
    @php
    $breadcrumbLinks =
     [
        ['url' => route('admin.top.index'), 'name' => 'Top'],
        ['url' => '', 'name' => 'Products'],
     ];
@endphp
<x-partials.breadcrumb :breadcrumbLinks=$breadcrumbLinks />
    PRODUCT
</x-app-layout>
