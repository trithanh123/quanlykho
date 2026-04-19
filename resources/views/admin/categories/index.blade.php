<x-app-layout>
    <x-slot name="header">
    <div class="text-left flex flex-col justify-start">
        {{-- Đổi sang màu xanh lá với class text-green-600 --}}
        <h2 class="font-bold text-xl text-green-600 leading-tight flex items-center m-0">
            <span class="text-2xl mr-2">🏢</span> 
            THIẾT LẬP HỆ THỐNG KHO & NHÓM HÀNG
        </h2>
        <p class="text-sm text-gray-500 mt-1 font-medium ml-9"> Danh sách kho bãi và sơ đồ phân loại hàng hóa</p>
    </div>
</x-slot>

    <div class="py-12" x-data="{ tab: 'categories' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex border-b border-gray-200 mb-6 bg-white rounded-t-lg shadow-sm">
                <button @click="tab = 'categories'" 
                        :class="tab === 'categories' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="w-1/2 py-4 px-1 text-center border-b-2 font-bold text-sm transition-all duration-200 rounded-tl-lg">
                    📦 Quản lý Nhóm hàng (Danh mục)
                </button>
                <button @click="tab = 'warehouses'" 
                        :class="tab === 'warehouses' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="w-1/2 py-4 px-1 text-center border-b-2 font-bold text-sm transition-all duration-200 rounded-tr-lg">
                    🏠 Quản lý Kho bãi
                </button>
            </div>

            <div x-show="tab === 'categories'" x-transition>
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/3">
                        <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                            <h3 class="font-bold text-gray-800 mb-4">Thêm Nhóm hàng</h3>
                            <form action="{{ route('categories.store') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Tên nhóm</label>
                                    <input type="text" name="name" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500" required>
                                </div>
                                <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-2 rounded-md hover:bg-indigo-700">Lưu Nhóm hàng</button>
                            </form>
                        </div>
                    </div>
                    <div class="w-full md:w-2/3">
                        <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                                    <tr>
                                        <th class="px-6 py-3">Tên nhóm hàng</th>
                                        <th class="px-6 py-3 text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($categories as $cat)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 font-bold text-indigo-600">{{ $cat->name }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <form action="{{ route('categories.destroy', $cat->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="text-red-500 hover:underline font-medium">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="tab === 'warehouses'" x-transition>
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/3">
                        <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                            <h3 class="font-bold text-gray-800 mb-4">Khai báo Kho mới</h3>
                            <form action="{{ route('warehouses.store') }}" method="POST">
    @csrf
    <div class="mb-4">
        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Tên Kho</label>
        <input type="text" name="name" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500" required>
    </div>
    <div class="mb-4">
        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Địa chỉ</label>
        <input type="text" name="address" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">
    </div>
    
   <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 rounded-md hover:bg-blue-700 mt-2 transition-colors">
    + Xác nhận Kho
</button>
</form>
                        </div>
                    </div>
                    <div class="w-full md:w-2/3">
                        <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                                    <tr>
                                        <th class="px-6 py-3">Tên Kho</th>
                                        <th class="px-6 py-3">Địa chỉ</th>
                                        <th class="px-6 py-3 text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($warehouses as $wh)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 font-bold text-green-600">{{ $wh->name }}</td>
                                        <td class="px-6 py-4 text-gray-500">{{ $wh->address ?? 'Chưa có địa chỉ' }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <form action="{{ route('warehouses.destroy', $wh->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="text-red-500 hover:underline font-medium">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>