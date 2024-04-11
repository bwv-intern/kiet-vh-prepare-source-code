<x-app-layout title="Top">
    @section('breadcumb')
        @php
            $breadcrumbLinks = [['url' => route('admin.top.index'), 'name' => 'Top'], ['url' => '', 'name' => 'Users']];
        @endphp
        <x-partials.breadcrumb :breadcrumbLinks=$breadcrumbLinks />
    @endsection

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between m-2">
                <h2>
                    User Search
                </h2>
                <button class="btn btn-primary" type="button" data-add-route="{{route('admin.user.add')}}" name="addButton" id="addButton">Add User</button>
            </div>
            <form name="formSearch" id="formSearch" method="POST" action="{{ route('admin.user.handleSearch') }}">
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <x-forms.text-group label="Email" name="email" :value="$paramSession['email'] ?? old('email')" />
                    </div>
                    <div class="col-sm-6">
                        <x-forms.text-group label="Full Name" name="name" :value="$paramSession['name'] ?? old('name')" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group">
                            <label class="col-3">User Flag</label>
                            <div class="col-8 mx-3">
                                <x-forms.checkbox-group :label="null" name="user_flg" :options="getList('user.user_flag')"
                                    :valueChecked="$paramSession['user_flg'] ?? old('user_flg')" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <x-forms.text-group label="Date of birth" name="date_of_birth"
                            :value="$paramSession['date_of_birth'] ?? old('date_of_birth')"></x-forms.text-group>
                    </div>
                    <div class="col-sm-6">
                        <x-forms.text-group label="Phone" name="phone" :value="$paramSession['phone'] ?? old('phone')"></x-forms.text-group>
                    </div>
                </div>

                <div class="d-flex justify-content-end m-3 ">
                    <x-button.base label="Search" class="btn btn-secondary m-1" ></x-button.base>
                    <x-button.clear label="Clear" screen="user.search" class="btn btn-secondary m-1"></x-button.clear>
                    <x-button.base  type="submit" name="btnExport" label="Export" screen="usr01" formaction="{{ route('admin.user.search') }}" class="btn btn-secondary m-1"></x-button.base>
                    <x-button.base type="button" label="Import" screen="usr01" class="btn btn-secondary m-1"></x-button.base>
                </div>
            </form>
        </div>
    </div>
        <div class="card card-secondary card-outline">
            <div class="card-body">
                <div class="col" id="table_data">
                    @if (count($users) > 0)
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex row justify-content-between align-item-center">              
                                   {{ $users->links('common.pagination') }}
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <table class="table table-bordered table-responsive-md">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Email</th>
                                        <th>Full name</th>
                                        <th>User flag</th>
                                        <th>Date of birth</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach ($users as $user)
                                        <tr id="userRow_{{ $user->id }}">
                                            <td class="d-flex align-items-center justify-content-center">
                                                <a href="{{ route('admin.user.edit', ['id' => $user->id]) }}"
                                                   class="btn btn-primary mr-2 edit-btn">Edit</a>

                                                <form name="deleleUser" id="deleteForm_{{ $user->id }}"
                                                      data-add-route="{{ route('admin.user.delete', ['id' => $user->id]) }}"
                                                      action="{{ route('admin.user.delete', ['id' => $user->id]) }}" method="GET"
                                                      class="delete-user">

                                                    <button type="submit" class="btn btn-danger delete-btn">Delete</button>

                                                </form>

                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->user_flg == 0 ? 'Admin' : ($user->user_flg == 2 ? 'Support' : 'User') }}
                                            </td>
                                            <td>
                                                @if ($user->date_of_birth)
                                                    {{ $user->date_of_birth->format('d/m/Y') }}
                                                @endif
                                            </td>
                                            <td>{{ $user->phone }}</td>
                                            <td>{{ $user->address }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info" role="alert">
                            <p class="m-0 p-0">There is no result.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
        @push('scripts')
            @vite(['resources/js/screens/user/search.js'], 'build')
        @endpush
</x-app-layout>
