<x-app-layout title="Top">

    @section('breadcumb')
        @php
            $breadcrumbLinks = [['url' => route('admin.top.index'), 'name' => 'Top']];
        @endphp
        <x-partials.breadcrumb :breadcrumbLinks=$breadcrumbLinks />
    @endsection
    TOP
</x-app-layout>
