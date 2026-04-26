<x-app-layout>
    <x-slot name="header">
        <div class="text-left flex flex-col justify-start">
            <h2 class="font-bold text-xl text-indigo-600 leading-tight flex items-center m-0">
                <span class="text-2xl mr-2">🏷️</span> 
                 HÀNG HÓA
            </h2>
            <p class="text-sm text-gray-500 mt-1 font-medium ml-9">Xem danh sách, thêm, sửa, xóa các mặt hàng đang có trong kho</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-indigo-900">Danh sách Hàng hóa trong kho</h3>
                        <a href="{{ route('products.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                            + Nhập hàng mới
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                       <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 font-bold text-center">Hình ảnh</th>
                                    <th class="px-6 py-4 font-bold">Mã SKU</th>
                                    <th class="px-6 py-4 font-bold">Tên sản phẩm</th>
                                    <th class="px-6 py-4 font-bold">Danh mục</th>
                                    <th class="px-6 py-4 font-bold">Giá bán</th>
                                    <th class="px-6 py-4 font-bold text-center">Tồn kho</th>
                                    <th class="px-6 py-4 font-bold text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr class="bg-white border-b hover:bg-gray-50 transition duration-150">
                                    
                                    <td class="px-6 py-4 text-center">
                                        @if($product->image)
                                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="h-10 w-10 object-cover rounded shadow-sm mx-auto">
                                        @else
                                            <span class="inline-block h-8 w-8 rounded-full overflow-hidden bg-gray-100 mx-auto">
                                                <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.932m4.932 4.932 4.932 4.932" />
                                                </svg>
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $product->sku }}</td>
                                    
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $product->name }}</td>
                                    
                                    <td class="px-6 py-4">
                                        <span class="bg-indigo-50 text-indigo-700 text-xs font-bold px-2.5 py-1 rounded border border-indigo-200">
                                            {{ $product->category ? $product->category->name : 'Chưa phân loại' }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-6 py-4 font-bold text-green-600">{{ number_format($product->price) }} đ</td>
                                    
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $product->quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $product->quantity }} {{ $product->unit }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
    {{-- Nút Sửa --}}
    <a href="{{ route('products.edit', $product->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Sửa</a>

    {{-- Nút Xóa (Bọc trong Form bảo mật) --}}
    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Xóa</button>
    </form>
</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                        <svg style="width: 50px; height: 50px;" class="mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        Chưa có sản phẩm nào trong kho. Hãy thêm mới!
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>