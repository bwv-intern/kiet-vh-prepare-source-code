<x-app-layout title="Top">
    @section('breadcumb')
        @php
            $breadcrumbLinks = [['url' => route('admin.top.index'), 'name' => 'Top'], ['url' => '', 'name' => 'Users']];
        @endphp
        <x-partials.breadcrumb :breadcrumbLinks=$breadcrumbLinks />
    @endsection

    <div class="card">
        <div class="card-body">
            <form name="formSearch" id="formSearch" method="POST" action="{{ route('admin.user.search') }}">
                @csrf
                <div class="d-flex justify-content-between m-2">
                    <h2>
                        User Search
                    </h2>
                    <x-button.base type="button" label="Add User" class="btn btn-primary" />
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <x-forms.text-group label="User Name" name="name" :value="$paramSession['name'] ?? null" />
                    </div>
                    <div class="col-sm-6">
                        <x-forms.text-group label="Email" name="email" :value="$paramSession['email'] ?? null" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group">
                            <label class="col-3">User Flag</label>
                            <div class="col-8 mx-3">
                                <x-forms.checkbox-group :label="null" name="user_flag" :options="getList('user.user_flag')"
                                    :valueChecked="$paramSession['user_flag'] ?? null" />
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-6">
                        <x-forms.text-group label="Date of birth" name="date_of_birth" id="date_of_birth"
                            :value="$paramSession['date_of_birth'] ?? null" />
                    </div>
                    <div class="col-sm-6">
                        <x-forms.text-group label="Phone" name="phone" :value="$paramSession['phone'] ?? null" />
                    </div>
                </div>

                <div class="d-flex justify-content-end m-3 ">
                    <x-button.base label="Search" class="btn btn-light m-1" />
                    <x-button.clear label="Clear" screen="usr01" class="btn btn-light m-1" />
                </div>
        </div>
    </div>


</x-app-layout>
