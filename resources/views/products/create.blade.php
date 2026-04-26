<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nhập Hàng Hóa Mới') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    
                    <h3 class="text-2xl font-bold text-indigo-900 mb-6 border-b pb-4">Thông tin sản phẩm</h3>

                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Tên sản phẩm <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required placeholder="VD: MainBoard ASUS ROG STRIX B550-F GAMING">
                            </div>

                            <div>
                                <label for="sku" class="block mb-2 text-sm font-medium text-gray-900">Mã SKU <span class="text-red-500">*</span></label>
                                <input type="text" name="sku" id="sku" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required placeholder="VD: NK-001">
                             {{-- Hiển thị thông báo lỗi trùng SKU kiểu "cháy phố" --}}
                            @error('sku')
                            <p class="mt-2 text-sm text-red-600 font-medium">
                            {{ $message }}
                            </p>
                             @enderror                        
                            </div>

                            <div>
                                <label for="category_id" class="block mb-2 text-sm font-medium text-gray-900">Danh mục <span class="text-red-500">*</span></label>
                                <select name="category_id" id="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                                    <option value="" disabled selected>-- Chọn danh mục --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
    <label for="price" class="block mb-2 text-sm font-medium text-gray-900">Giá bán (VNĐ) <span class="text-red-500">*</span></label>
    <input type="number" name="price" id="price" min="0" oninput="checkPrice(this)" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required placeholder="0">

    <p id="price-error" class="mt-2 text-sm text-red-600 hidden font-medium">❌ Giá bán không được là số âm!</p>
</div>


        <div>
            <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900">Số lượng <span class="text-red-500">*</span></label>
            <input type="number" name="quantity" id="quantity" min="1" oninput="checkQuantity(this)" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required value="1">
            @error('quantity')
    <div class="text-red-500" style="color: red;">{{ $message }}</div>
@enderror
            
            <p id="quantity-error" class="mt-2 text-sm text-red-600 hidden font-medium">❌ Số lượng không được nhỏ hơn 0!</p>
        </div>

                            <div>
                                <label for="unit" class="block mb-2 text-sm font-medium text-gray-900">Đơn vị tính</label>
                                <input type="text" name="unit" id="unit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="VD: Cái, Đôi, Hộp..." value="Cái">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-900" for="image">Tải ảnh sản phẩm lên</label>
                            <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" id="image" name="image" type="file" accept="image/*">
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Mô tả sản phẩm</label>
                            <textarea id="description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Nhập mô tả chi tiết về sản phẩm này..."></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-4 border-t pt-5">
                            <a href="{{ route('products.index') }}" class="text-gray-600 bg-gray-100 hover:bg-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition">Hủy bỏ</a>
                            <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 font-medium rounded-lg text-sm px-5 py-2.5 text-center shadow-md transition">Lưu Sản Phẩm</button>
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
                // Hiện lỗi, đổi viền input sang màu đỏ
                errorText.classList.remove('hidden');
                input.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                input.classList.remove('border-gray-300', 'focus:ring-indigo-500', 'focus:border-indigo-500');
            } else {
                // Ẩn lỗi, trả viền về bình thường
                errorText.classList.add('hidden');
                input.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                input.classList.add('border-gray-300', 'focus:ring-indigo-500', 'focus:border-indigo-500');
            }
        }
        // THÊM HÀM NÀY VÀO ĐỂ KIỂM TRA SỐ LƯỢNG NÈ ÔNG:
    function checkQuantity(input) {
        const errorText = document.getElementById('quantity-error');
        if (input.value < 0) {
            // Hiện lỗi, đổi viền input sang màu đỏ
            errorText.classList.remove('hidden');
            input.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
            input.classList.remove('border-gray-300', 'focus:ring-indigo-500', 'focus:border-indigo-500');
        } else {
            // Ẩn lỗi, trả viền về bình thường
            errorText.classList.add('hidden');
            input.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
            input.classList.add('border-gray-300', 'focus:ring-indigo-500', 'focus:border-indigo-500');
        }
    }
    </script>
    
</x-app-layout>