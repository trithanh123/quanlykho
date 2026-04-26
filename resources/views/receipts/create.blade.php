<x-app-layout>
    <x-slot name="header">
        <div class="text-left flex flex-col justify-start">
            <h2 class="font-bold text-xl text-blue-600 leading-tight flex items-center m-0">
                <span class="text-2xl mr-2">📥</span> 
                LẬP PHIẾU NHẬP KHO
            </h2>
            <p class="text-sm text-gray-500 mt-1 font-medium ml-9">Ghi nhận thông tin linh kiện, sản phẩm mới nhập vào hệ thống</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    
                    <form action="{{ route('receipts.store') }}" method="POST" novalidate>
                    @csrf
                        
                        {{-- THÊM CỤC BẮT LỖI NÀY VÀO ĐÂY --}}
                        @if ($errors->any())
                            <div class="p-4 mb-6 text-sm text-red-800 rounded-lg bg-red-100 border border-red-200" role="alert">
                                <span class="font-bold">hãy kiểm tra lại thông tin nè:</span>
                                <ul class="mt-1 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        {{-- Phần Thông tin chung --}}
                        <div class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <label for="note" class="block mb-2 text-sm font-bold text-gray-900">Ghi chú phiếu nhập (Không bắt buộc)</label>
                            <input type="text" name="note" id="note" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="VD: Nhập hàng đợt 1 từ nhà cung cấp Asus...">
                        </div>

                        {{-- Bảng nhập chi tiết hàng hóa --}}
                        <h3 class="text-lg font-bold text-indigo-900 mb-4 border-b pb-2">Chi tiết hàng hóa nhập</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 mb-4">
                                <thead class="text-xs text-gray-700 uppercase bg-indigo-100">
                                    <tr>
                                        <th class="px-4 py-3">Tên linh kiện / Sản phẩm <span class="text-red-500">*</span></th>
                                        <th class="px-4 py-3 w-40">Số lượng <span class="text-red-500">*</span></th>
                                        <th class="px-4 py-3 w-48">Giá nhập (VNĐ) <span class="text-red-500">*</span></th>
                                        <th class="px-4 py-3 w-20 text-center">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody id="chi-tiet-phieu">
                                    {{-- Dòng đầu tiên (Mặc định phải có 1 dòng) --}}
                                    <tr class="border-b item-row">
                                        <td class="px-2 py-3">
                                            {{-- ĐỂ Ý CÁI DẤU [] Ở NAME NHA ÔNG --}}
                                            <select name="product_id[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                                                <option value="" disabled selected>-- Chọn sản phẩm --</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->sku }} - {{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-2 py-3">
                                            <input type="number" name="quantity[]" min="1" value="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                                        </td>
                                        <td class="px-2 py-3">
                                            <input type="number" name="price[]" min="0" placeholder="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                                        </td>
                                        <td class="px-2 py-3 text-center">
                                            <button type="button" onclick="xoaDong(this)" class="text-red-500 hover:text-red-700 font-bold p-2">
                                                ❌
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Nút Thêm Dòng --}}
                        <div class="mb-8">
                            <button type="button" onclick="themDong()" class="text-indigo-600 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition">
                                ➕ Thêm hàng hóa
                            </button>
                        </div>

                        {{-- Nút Lưu Phiếu --}}
                        <div class="flex justify-end gap-4 border-t pt-5">
                            <a href="{{ route('dashboard') }}" class="text-gray-600 bg-gray-100 hover:bg-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition">Hủy bỏ</a>
                            <button type="submit" class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-6 py-2.5 text-center shadow-md transition">💾 Lưu Phiếu Nhập</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ĐOẠN SCRIPT THẦN THÁNH NẰM Ở ĐÂY --}}
    <script>
        // Hàm Thêm dòng mới
        function themDong() {
            // 1. Tìm cái bảng (tbody)
            const tbody = document.getElementById('chi-tiet-phieu');
            // 2. Lấy cái dòng đầu tiên làm chuẩn (mẫu)
            const dongMau = tbody.querySelector('.item-row');
            // 3. Clone (nhân bản) nó ra một dòng y hệt
            const dongMoi = dongMau.cloneNode(true);

            // 4. Reset lại các ô nhập liệu trong dòng mới cho nó trống trơn
            dongMoi.querySelector('select').value = '';
            const inputs = dongMoi.querySelectorAll('input');
            inputs[0].value = '1'; // Ô số lượng set về 1
            inputs[1].value = '';  // Ô giá nhập set rỗng

            // 5. Gắn dòng mới đó vào cuối bảng
            tbody.appendChild(dongMoi);
        }

        // Hàm Xóa bớt dòng
        function xoaDong(nutXoa) {
            const tbody = document.getElementById('chi-tiet-phieu');
            // Nếu bảng đang có nhiều hơn 1 dòng thì mới cho xóa
            if (tbody.querySelectorAll('.item-row').length > 1) {
                // Xóa cái dòng (thẻ tr) đang chứa cái nút bấm đó
                nutXoa.closest('tr').remove();
            } else {
                alert('Phiếu nhập phải có ít nhất 1 mặt hàng chứ ông!');
            }
        }
    </script>
</x-app-layout>