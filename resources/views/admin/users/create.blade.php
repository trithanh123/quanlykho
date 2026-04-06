<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Thêm Nhân Viên Mới') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        <p class="font-bold">Ối! Dữ liệu bạn nhập có vấn đề:</p>
                        <ul class="list-disc ml-5 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ url('admin/users') }}">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                
                <form method="POST" action="{{ url('admin/users') }}">
                    @csrf <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Tên nhân viên</label>
                        <input type="text" name="name" id="name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email đăng nhập</label>
                        <input type="email" name="email" id="email" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu (Ít nhất 8 ký tự)</label>
                        <input type="password" name="password" id="password" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div class="mb-6">
                        <label for="role" class="block text-sm font-medium text-gray-700">Vai trò phân quyền</label>
                        <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="manager">Thủ kho</option>
                            <option value="driver">Tài xế</option>
                            <option value="admin">Quản lý (Admin)</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-4 mt-6">
                <a href="{{ url('admin/users') }}" class="text-gray-500 hover:text-gray-800 font-medium px-4 py-2 rounded hover:bg-gray-100 transition duration-150">
                    Hủy bỏ
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded transition duration-200">
                    Lưu dữ liệu
                </button>
            </div>
            </form>
            </div>
        </div>
    </div>
</x-app-layout>