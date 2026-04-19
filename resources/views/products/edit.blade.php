<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chỉnh sửa Hàng Hóa') }} - {{ $product->sku }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    
                    {{-- CHÚ Ý: Action trỏ về hàm update và truyền cái ID vào --}}
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- BẮT BUỘC PHẢI CÓ DÒNG NÀY ĐỂ LARAVEL HIỂU ĐÂY LÀ FORM SỬA --}}
                        @method('PUT') 

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            {{-- Ô Tên sản phẩm --}}
                            <div>
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Tên sản phẩm <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ $product->name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                            </div>

                            {{-- Ô Mã SKU --}}
                            <div>
                                <label for="sku" class="block mb-2 text-sm font-medium text-gray-900">Mã SKU <span class="text-red-500">*</span></label>
                                <input type="text" name="sku" id="sku" value="{{ $product->sku }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                            </div>

                            {{-- Ô Chọn Danh mục --}}
                            <div>
                                <label for="category_id" class="block mb-2 text-sm font-medium text-gray-900">Danh mục <span class="text-red-500">*</span></label>
                                <select name="category_id" id="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                                    @foreach($categories as $category)
                                        {{-- Chỗ này check xem ID danh mục cũ có khớp không để tự động in đậm nó lên --}}
                                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Ô Giá bán --}}
                            <div>
                                <label for="price" class="block mb-2 text-sm font-medium text-gray-900">Giá bán (VNĐ) <span class="text-red-500">*</span></label>
                                <input type="number" name="price" id="price" min="0" oninput="checkPrice(this)" value="{{ $product->price }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                                <p id="price-error" class="mt-2 text-sm text-red-600 hidden font-medium">❌ Giá bán không được là số âm!</p>
                            </div>

                            {{-- Ô Số lượng --}}
                            <div>
                                <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900">Số lượng <span class="text-red-500">*</span></label>
                                <input type="number" name="quantity" id="quantity" min="0" oninput="checkQuantity(this)" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required value="{{ $product->quantity }}">
                                <p id="quantity-error" class="mt-2 text-sm text-red-600 hidden font-medium">❌ Số lượng không được nhỏ hơn 0!</p>
                            </div>

                            {{-- Ô Đơn vị tính --}}
                            <div>
                                <label for="unit" class="block mb-2 text-sm font-medium text-gray-900">Đơn vị tính</label>
                                <input type="text" name="unit" id="unit" value="{{ $product->unit }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                            </div>
                        </div>

                        {{-- Ô SỬA HÌNH ẢNH (Phần tui mới thêm vào cho ông nè) --}}
                        <div class="mb-6 bg-white p-4 border border-gray-200 rounded-lg shadow-sm">
                            <label class="block mb-3 text-sm font-bold text-gray-900">Sửa hình ảnh sản phẩm</label>
                            
                            {{-- Hiện ảnh cũ nếu có --}}
                            <div class="mb-4 flex items-center gap-4">
                                @if($product->image)
                                    <div class="shrink-0">
                                        <p class="text-xs text-gray-500 mb-1 font-medium">Ảnh hiện tại:</p>
                                        <img src="{{ asset($product->image) }}" alt="Current Image" class="h-20 w-20 object-cover rounded-lg border border-gray-300 shadow-sm">
                                    </div>
                                @else
                                    <div class="shrink-0">
                                        <p class="text-xs text-gray-500 mb-1 font-medium">Ảnh hiện tại:</p>
                                        <div class="h-20 w-20 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center text-gray-400 text-xs text-center p-2 shadow-sm">
                                            Chưa có ảnh
                                        </div>
                                    </div>
                                @endif

                                {{-- Nút chọn file mới --}}
                                <div class="flex-1">
                                    <label for="image" class="block text-xs text-gray-500 mb-1 font-medium">Tải ảnh mới lên (Tùy chọn):</label>
                                    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-2 transition-colors hover:border-indigo-400" id="image" name="image" type="file" accept="image/*">
                                    <p class="mt-1 text-xs text-gray-400">Định dạng hỗ trợ: PNG, JPG, JPEG.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Ô Mô tả --}}
                        <div class="mb-6">
                            <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Mô tả sản phẩm</label>
                            <textarea id="description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">{{ $product->description }}</textarea>
                        </div>

                        {{-- Nút bấm --}}
                        <div class="flex items-center justify-end gap-4 border-t pt-5">
                            <a href="{{ route('products.index') }}" class="text-gray-600 bg-gray-100 hover:bg-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition">Hủy bỏ</a>
                            <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center shadow-md transition">Lưu Thay Đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function checkPrice(input) {
            const errorText = document.getElementById('price-error');
            if (input.value < 0) {
                errorText.classList.remove('hidden');
                input.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                input.classList.remove('border-gray-300', 'focus:ring-indigo-500', 'focus:border-indigo-500');
            } else {
                errorText.classList.add('hidden');
                input.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                input.classList.add('border-gray-300', 'focus:ring-indigo-500', 'focus:border-indigo-500');
            }
        }
        
        function checkQuantity(input) {
            const errorText = document.getElementById('quantity-error');
            if (input.value < 0) {
                errorText.classList.remove('hidden');
                input.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                input.classList.remove('border-gray-300', 'focus:ring-indigo-500', 'focus:border-indigo-500');
            } else {
                errorText.classList.add('hidden');
                input.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                input.classList.add('border-gray-300', 'focus:ring-indigo-500', 'focus:border-indigo-500');
            }
        }
    </script>
</x-app-layout>