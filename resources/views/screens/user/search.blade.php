<x-app-layout title="Top">
    @section('breadcumb')
        @php
            $breadcrumbLinks =
             [
                ['url' => route('admin.top.index'), 'name' => 'Top'],
                ['url' => '', 'name' => 'Users'],
             ];
        @endphp
        <x-partials.breadcrumb :breadcrumbLinks=$breadcrumbLinks />
    @endsection
    USER SEARCH
</x-app-layout>
