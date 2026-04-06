<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản Lý Kho Vận</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="flex min-h-screen">
        <div class="w-full lg:w-1/2 flex items-center justify-center bg-white p-8">
            <div class="max-w-md w-full">
                <div class="text-center mb-10">
                    <h2 class="text-4xl font-bold text-indigo-900">ĐĂNG NHẬP</h2>
                    <p class="text-gray-500 mt-2">Hệ thống quản lý kho vận </p>
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
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 rounded-lg bg-gray-100 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-0 text-sm" placeholder="admin@gmail.com">
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Mật khẩu</label>
                        <input type="password" name="password" required class="w-full px-4 py-3 rounded-lg bg-gray-100 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-0 text-sm" placeholder="********">
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg transition duration-200">
                        XÁC NHẬN
                    </button>
                </form>
            </div>
        </div>

        <div class="hidden lg:block lg:w-1/2 bg-cover bg-center" 
             style="background-image: url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=2070&auto=format&fit=crop');">
            <div class="h-full w-full bg-indigo-900 bg-opacity-20 flex items-center justify-center">
                <h1 class="text-white text-5xl font-bold text-center px-10 shadow-sm">SMART INVENTORY SYSTEM</h1>
            </div>
        </div>
    </div>
</body>
</html>