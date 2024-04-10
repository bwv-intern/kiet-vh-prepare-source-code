<x-app-layout title="Top">
    @section('breadcumb')
        @php
            $breadcrumbLinks = [
                ['url' => route('admin.top.index'), 'name' => 'Top'],
                ['url' => route('admin.user.search'), 'name' => 'Users'],
                ['url' => '', 'name' => 'User edit'],
            ];
        @endphp
        <x-partials.breadcrumb :breadcrumbLinks=$breadcrumbLinks />
    @endsection

    <div class="card">
        <div class="card-body">
            <form name="formSearch" id="formSearch" method="POST" action="{{ route('admin.user.add') }}">
                @csrf
                <div class="d-flex justify-content-between m-2">
                    <h2>
                        User Add
                    </h2>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <x-forms.text-group label="Email" name="email" :value="  old('email')" />
                    </div>
                    <div class="col-sm-6">
                        <x-forms.text-group label="User Name" name="name" :value="old('name')" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <x-forms.text-group type="password" label="Password" name="password" :value="old('name')" />
                    </div>
                    <div class="col-sm-6">
                        <x-forms.text-group type="password" label="Re-password" name="re-password" :value="old('email')" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <x-forms.select label="User flag" name="user_flag" id="user_flag" :options="getList('user.user_flag')" selected="1">

                        </x-forms.select>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <x-forms.text-group label="Date of birth" name="date_of_birth"
                                            :value=" old('date_of_birth')"></x-forms.text-group>
                    </div>
                    <div class="col-sm-6">
                        <x-forms.text-group label="Phone" name="phone" :value="old('phone')"></x-forms.text-group>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <x-forms.textarea id="address" label="Address" name="address" value=""></x-forms.textarea>

                    </div>

                </div>
                <div class="d-flex justify-content-center m-3 ">
                    <x-button.base label="Add" class="btn btn-secondary m-1"></x-button.base>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/screens/user/edit.js'], 'build')
    @endpush
</x-app-layout>
