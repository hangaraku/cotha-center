<div class="flex flex-col">
    <div class="w-full h-full">
        <div
            class="flex flex-col justify-center items-center sm:items-start gap-y-6 px-8 sm:px-16 lg:px-24 xl:px-48 py-12 text-white text-center sm:text-left">
            @guest
                <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">
                    Belajar Coding Jadi Seru!

                </h1>
                <p class="text-lg md:text-xl font-medium">
                    Yuk, belajar ngoding lewat game seru dari nol!
                </p>
                <div class="flex flex-wrap justify-center sm:justify-start gap-4 mt-6">
                    <a href="{{ route('login') }}"
                        class="bg-[#f261a1] hover:bg-[#b44a75] text-white font-bold px-8 py-3 rounded-xl shadow transition-colors duration-300 border-2 border-[#f261a1]">
                        Log In
                    </a>
                    <a href=""
                        class="bg-white border-2 border-[#f261a1] text-[#f261a1] hover:bg-[#f261a1] hover:text-white font-bold px-8 py-3 rounded-xl shadow transition-colors duration-300">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            @endguest

            @auth
                <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">
                    Selamat datang!
                </h1>
                <p class="text-lg md:text-xl font-medium">
                    Yuk, lanjutkan belajar codingmu!
                </p>
                <div class="flex flex-wrap justify-center sm:justify-start gap-4 mt-6">
                    <a href="#footer"
                        class="bg-[#f261a1] hover:bg-[#b44a75] text-white font-bold px-8 py-3 rounded-full shadow transition-colors duration-300 border-2 border-[#f261a1]">
                        Lihat Kelas
                    </a>
                    <a href=""
                        class="bg-white border-2 border-[#f261a1] text-[#f261a1] hover:bg-[#f261a1] hover:text-white font-bold px-8 py-3 rounded-full shadow transition-colors duration-300">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>