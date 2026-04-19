<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
                
                <div class="bg-indigo-600 px-6 py-4 border-b border-indigo-700">
                    <h2 class="text-xl font-bold text-white flex items-center m-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        LẬP PHIẾU XUẤT KHO MỚI
                    </h2>
                </div>

                <div class="p-6 md:p-8">
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-6 shadow-sm">
                            <div class="flex items-center font-bold mb-2">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                Vui lòng kiểm tra lại thông tin:
                            </div>
                            <ul class="list-disc list-inside text-sm pl-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('issues.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Ghi chú phiếu xuất</label>
                            <textarea name="note" rows="3" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors placeholder-gray-400 shadow-sm" placeholder="Nhập ghi chú hoặc lý do xuất kho (nếu có)..."></textarea>
                        </div>

                        <div class="mb-6">
                            <div class="flex justify-between items-end mb-3 border-b border-gray-200 pb-2">
                                <label class="block text-base font-bold text-gray-800">Danh sách hàng hóa xuất <span class="text-red-500">*</span></label>
                            </div>
                            
                            <div id="product-list" class="space-y-3">
                                <div class="flex items-center gap-3 product-row bg-white p-3 rounded-lg border border-gray-200 shadow-sm transition-all hover:border-indigo-300">
                                    
                                    <div class="flex-1">
                                        <select name="products[0][id]" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 shadow-sm" required>
                                            <option value="" class="text-gray-500">-- Chọn sản phẩm cần xuất --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">
                                                    {{ $product->name }} (Tồn kho: {{ $product->quantity }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="w-32">
                                        <input type="number" name="products[0][quantity]" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 text-center shadow-sm" placeholder="Số lượng" min="1" required>
                                    </div>
                                    
                                    <button type="button" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-md transition-colors duration-200 focus:outline-none" onclick="removeRow(this)" title="Xóa dòng này">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <button type="button" id="btn-add-product" class="mt-4 inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 px-4 py-2.5 rounded-md transition-colors duration-200 focus:outline-none shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Thêm dòng sản phẩm
                            </button>
                        </div>

                        <div class="flex items-center justify-end gap-4 border-t border-gray-200 pt-6 mt-4">
                            <a href="{{ route('issues.index') }}" class="text-sm font-bold text-gray-600 hover:text-gray-900 transition-colors bg-gray-100 hover:bg-gray-200 px-6 py-2.5 rounded-md border border-gray-300">
                                Hủy bỏ
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-8 py-2.5 rounded-md shadow-md transition-all focus:ring-4 focus:ring-indigo-300 focus:outline-none flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Lưu Phiếu Xuất
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    let rowIndex = 1;

    document.getElementById('btn-add-product').addEventListener('click', function() {
        let productList = document.getElementById('product-list');
        
        let newRow = document.createElement('div');
        newRow.className = 'flex items-center gap-3 product-row bg-white p-3 rounded-lg border border-gray-200 shadow-sm transition-all hover:border-indigo-300';
        newRow.innerHTML = `
            <div class="flex-1">
                <select name="products[${rowIndex}][id]" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 shadow-sm" required>
                    <option value="" class="text-gray-500">-- Chọn sản phẩm cần xuất --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} (Tồn kho: {{ $product->quantity }})</option>
                    @endforeach
                </select>
            </div>
            <div class="w-32">
                <input type="number" name="products[${rowIndex}][quantity]" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 text-center shadow-sm" placeholder="Số lượng" min="1" required>
            </div>
            <button type="button" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-md transition-colors duration-200 focus:outline-none" onclick="removeRow(this)" title="Xóa dòng này">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </button>
        `;
        
        productList.appendChild(newRow);
        rowIndex++;
    });

    function removeRow(btn) {
        let row = btn.closest('.product-row');
        let productList = document.getElementById('product-list');
        
        if (productList.children.length > 1) {
            row.remove();
        } else {
            alert("Phải có ít nhất 1 sản phẩm để xuất kho!");
        }
    }
</script>
</x-app-layout>