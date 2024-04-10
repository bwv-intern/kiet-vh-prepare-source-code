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
            <form name="formEdit" id="formEdit" method="POST" action="{{ route('admin.user.postEdit') }}">
                @csrf
                <div class="d-flex justify-content-between m-2">
                    <h2>
                        User Edit
                    </h2>
                </div>
                

                <div class="row">
                    <div class="col-sm-6">
                        <x-forms.text-hidden id="id" name="id" value="{{$user->id}}" />
                        <x-forms.text-group label="Email" name="email" :value="$user->email" />
                    </div>
                    <div class="col-sm-6">
                        <x-forms.text-group label="User Name" name="name" :value="$user->name" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <x-forms.text-group type="password" label="Password" name="password" />
                    </div>
                    <div class="col-sm-6">
                        <x-forms.text-group type="password" label="Re-password" name="repassword" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <x-forms.select label="User flag" name="user_flg" id="user_flg" :options="getList('user.user_flag')"
                            :selected="$user->user_flg">

                        </x-forms.select>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <x-forms.text-group label="Date of birth" name="date_of_birth" :value="$user->date_of_birth != null
                            ? Illuminate\Support\Carbon::parse($user->date_of_birth)->format('Y/m/d')
                            : ''" />
                    </div>
                    <div class="col-sm-6">
                        <x-forms.text-group label="Phone" name="phone" :value="$user->phone"></x-forms.text-group>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <x-forms.textarea id="address" label="Address" name="address"
                            value="">$user->address</x-forms.textarea>

                    </div>

                </div>
                <div class="d-flex justify-content-center m-3 ">
                    <x-button.base label="Edit" class="btn btn-secondary m-1"></x-button.base>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/screens/user/edit.js'], 'build')
    @endpush
</x-app-layout>
