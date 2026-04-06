<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quản lý Tài Khoản Nhân Viên') }}
        </h2>
    </x-slot>

   <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Thành công</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Lỗi Hành Động </p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-700">Danh sách tài khoản hệ thống</h3>
                <a href="{{ url('admin/users/create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                    + Thêm nhân viên
                </a>
            </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 border-b">ID</th>
                                <th class="py-3 px-6 border-b">Tên nhân viên</th>
                                <th class="py-3 px-6 border-b">Email</th>
                                <th class="py-3 px-6 border-b text-center">Vai trò</th>
                                <th class="py-3 px-6 border-b text-center">Hành động</th>
                                <th class="py-3 px-6 border-b text-center">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @foreach($users as $user)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition duration-150">
                                <td class="py-3 px-6 whitespace-nowrap font-medium">{{ $user->id }}</td>
                                <td class="py-3 px-6">{{ $user->name }}</td>
                                <td class="py-3 px-6">{{ $user->email }}</td>
                                <td class="py-3 px-6 text-center">
                                    @if($user->role == 'admin')
                                        <span class="bg-red-100 text-red-600 py-1 px-3 rounded-full text-xs font-bold">Quản lý</span>
                                    @elseif($user->role == 'manager')
                                        <span class="bg-green-100 text-green-600 py-1 px-3 rounded-full text-xs font-bold">Thủ kho</span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-700 py-1 px-3 rounded-full text-xs font-bold">Tài xế</span>
                                    @endif
                                </td>
                            
                                <td class="py-3 px-6 text-center flex items-center justify-center gap-3">
    <a href="{{ url('admin/users/' . $user->id . '/edit') }}" class="text-blue-500 hover:text-blue-700 font-medium">Sửa</a>

    <form action="{{ url('admin/users/' . $user->id . '/lock') }}" method="POST" class="inline-block">
        @csrf
        @method('PUT')
        <button type="submit" class="{{ $user->is_locked ? 'text-green-500 hover:text-green-700' : 'text-yellow-500 hover:text-yellow-700' }} font-medium">
            {{ $user->is_locked ? 'Mở khóa' : 'Khóa' }}
        </button>
    </form>

    <form action="{{ url('admin/users/' . $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn XÓA VĨNH VIỄN nhân viên này không?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Xóa</button>
    </form>
</td>
                                <td class="py-3 px-6 text-center">
                                @if($user->is_locked)
                                    <span class="bg-red-100 text-red-600 py-1 px-3 rounded-full text-xs font-bold">Bị khóa</span>
                                @else
                                    <span class="bg-green-100 text-green-600 py-1 px-3 rounded-full text-xs font-bold">Hoạt động</span>
                                @endif
                            </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>