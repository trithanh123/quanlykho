<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản Lý Kho linh kiện - Phong Vũ PC</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="flex min-h-screen">
        {{-- Cột bên trái: Form Đăng nhập --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center bg-white p-8">
            <div class="max-w-md w-full">
                <div class="text-center mb-10">
                    <h2 class="text-4xl font-bold text-blue-900">ĐĂNG NHẬP</h2>
                    <p class="text-gray-500 mt-2">Hệ thống quản lý kho Cho Công Ty Máy Tính Phong Vũ </p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg text-sm">
                            <p class="font-bold mb-1">Đăng nhập thất bại!</p>
                            <ul class="list-disc ml-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2"> Tên đăng nhập</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 rounded-lg bg-gray-100 border-transparent focus:border-blue-500 focus:bg-white focus:ring-0 text-sm" placeholder="admin@gmail.com">
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Mật khẩu</label>
                        <input type="password" name="password" required class="w-full px-4 py-3 rounded-lg bg-gray-100 border-transparent focus:border-blue-500 focus:bg-white focus:ring-0 text-sm" placeholder="********">
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition duration-200">
                        XÁC NHẬN
                    </button>
                </form>
            </div>
        </div>

        {{-- Cột bên phải: Hình ảnh Kho linh kiện Phong Vũ --}}
        <div class="hidden lg:block lg:w-1/2 bg-cover bg-center relative" 
             style="background-image: url('{{ asset('images/bg-kho-phongvu.jpg') }}');">
            
            {{-- Lớp phủ màu xanh dương đậm chất Phong Vũ --}}
            <div class="absolute inset-0 bg-blue-900 bg-opacity-70"></div>
            
            {{-- Nội dung chữ và Logo --}}
            <div class="relative h-full w-full flex flex-col items-center justify-center z-10 space-y-6">
                
                {{-- Logo Phong Vũ --}}
                <img src="{{ asset('images/phongvu.png') }}" alt="Phong Vũ PC" class="h-20 w-auto rounded shadow-xl border-2 border-white/20 bg-white/10 p-2">
                
                <h1 class="text-white text-4xl lg:text-5xl font-bold text-center px-10 drop-shadow-lg leading-tight uppercase tracking-wider">
                    Hệ Thống Quản Lý <br> 
                    <span class="text-blue-300">Kho Linh Kiện</span>
                </h1>
                
                <p class="text-blue-100 text-lg font-medium tracking-widest uppercase">
                    Smart Inventory System
                </p>
            </div>
        </div>
    </div>
</body>
</html>