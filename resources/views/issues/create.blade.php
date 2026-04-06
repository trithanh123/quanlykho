<x-app-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-700">
        
        <div class="bg-indigo-500 px-6 py-4 border-b border-indigo-600">
            <h2 class="text-xl font-bold text-white flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                LẬP PHIẾU XUẤT KHO MỚI
            </h2>
        </div>

        <div class="p-6">
            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('issues.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Ghi chú phiếu xuất</label>
                    <textarea name="note" rows="3" class="w-full bg-gray-700 border border-gray-600 text-black text-sm rounded-lg focus:ring-indigo-400 focus:border-indigo-400 block p-3 transition-colors placeholder-gray-400" placeholder="Nhập ghi chú hoặc lý do xuất kho (nếu có)..."></textarea>
                </div>

                <div class="mb-6">
                    <div class="flex justify-between items-end mb-2">
                        <label class="block text-sm font-medium text-gray-300">Danh sách hàng hóa xuất <span class="text-red-500">*</span></label>
                    </div>
                    
                    <div id="product-list" class="space-y-3">
                        <div class="flex items-center gap-3 product-row bg-gray-700 p-3 rounded-lg border border-gray-600 transition-all hover:border-gray-500">
                            
                            <div class="flex-1">
                                <select name="products[0][id]" class="w-full bg-gray-800 border border-gray-600 text-white text-sm rounded-lg focus:ring-indigo-400 focus:border-indigo-400 block p-2.5" required>
                                    <option value="" class="text-gray-400">-- Chọn sản phẩm cần xuất --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }} (Tồn kho: {{ $product->quantity }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="w-32">
                                <input type="number" name="products[0][quantity]" class="w-full bg-gray-800 border border-gray-600 text-white text-sm rounded-lg focus:ring-indigo-400 focus:border-indigo-400 block p-2.5 text-center" placeholder="Số lượng" min="1" required>
                            </div>
                            
                            <button type="button" class="text-gray-400 hover:text-red-400 hover:bg-red-400/10 p-2 rounded-lg transition-colors duration-200 focus:outline-none" onclick="removeRow(this)" title="Xóa dòng này">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>

        <button type="button" id="btn-add-product" class="mt-4 inline-flex items-center text-sm font-medium text-orange-400 hover:text-orange-300 bg-orange-500/10 hover:bg-orange-500/20 border border-orange-500/30 px-4 py-2.5 rounded-lg transition-colors duration-200 focus:outline-none">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
    </svg>
    Thêm dòng sản phẩm
</button>
                </div>

                <div class="flex items-center justify-end gap-4 border-t border-gray-700 pt-6 mt-2">
                    <a href="{{ route('issues.index') }}" class="text-sm font-medium text-gray-400 hover:text-white transition-colors">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium px-6 py-2.5 rounded-lg shadow-md transition-all focus:ring-4 focus:ring-indigo-500/50 focus:outline-none flex items-center">
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

<script>
    let rowIndex = 1;

    document.getElementById('btn-add-product').addEventListener('click', function() {
        let productList = document.getElementById('product-list');
        
        let newRow = document.createElement('div');
        newRow.className = 'flex items-center gap-3 product-row bg-gray-700 p-3 rounded-lg border border-gray-600 transition-all hover:border-gray-500';
        newRow.innerHTML = `
            <div class="flex-1">
                <select name="products[${rowIndex}][id]" class="w-full bg-gray-800 border border-gray-600 text-white text-sm rounded-lg focus:ring-indigo-400 focus:border-indigo-400 block p-2.5" required>
                    <option value="" class="text-gray-400">-- Chọn sản phẩm cần xuất --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} (Tồn kho: {{ $product->quantity }})</option>
                    @endforeach
                </select>
            </div>
            <div class="w-32">
                <input type="number" name="products[${rowIndex}][quantity]" class="w-full bg-gray-800 border border-gray-600 text-white text-sm rounded-lg focus:ring-indigo-400 focus:border-indigo-400 block p-2.5 text-center" placeholder="Số lượng" min="1" required>
            </div>
            <button type="button" class="text-gray-400 hover:text-red-400 hover:bg-red-400/10 p-2 rounded-lg transition-colors duration-200 focus:outline-none" onclick="removeRow(this)" title="Xóa dòng này">
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