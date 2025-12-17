@extends("layouts.app")

@section('content')
<div class="min-h-screen flex flex-row" style="background: url('{{ asset('images/login-background.jpg') }}') center bottom / cover no-repeat fixed; overflow: hidden;">
    <!-- Left: Empty -->
    <div class="hidden lg:block lg:w-1/2"></div>
    <!-- Right: Login Form -->
    <div class="flex w-full lg:w-1/2 items-center justify-center px-6 py-12">
        <div class="w-full max-w-lg bg-white bg-opacity-90 shadow-lg rounded-lg p-8 mx-auto">
            <div class="text-center">
                <img class="mx-auto h-14 w-auto rounded-full" src="{{ asset('images/logo.png') }}" alt="Logo">
                <h2 class="mt-6 text-3xl font-bold text-blue-900">Selamat Datang</h2>
                <p class="mt-2 text-sm text-gray-600">Silahkan login untuk melanjutkan</p>
            </div>

            <form action="{{ route('authenticate') }}" method="POST" class="mt-8 space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-blue-900">Email atau Username</label>
                    <input type="text" name="email" id="email" required
                        class="mt-1 p-3 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm"
                        placeholder="contoh: budi.kurniawan atau budi.kurniawan@student.cotha.id">
                    <p class="mt-1 text-xs text-gray-500">Anda bisa login dengan email lengkap atau username (bagian sebelum @)</p>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-blue-900">Password</label>
                    <input type="password" name="password" id="password" required
                        class="mt-1 p-3 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center space-x-2 text-sm text-gray-700">
                        <input type="checkbox" name="remember"
                            class="h-4 w-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                        <span>Remember me</span>
                    </label>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center rounded-md border border-transparent bg-pink-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                        Sign in
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
