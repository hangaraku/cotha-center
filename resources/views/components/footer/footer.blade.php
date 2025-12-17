<div id="footer" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white mt-auto">
    <div class="w-full px-8 sm:px-16 lg:px-24 xl:px-48 py-6">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            {{-- Left: Menu links --}}
            <div class="flex flex-wrap justify-center sm:justify-start gap-4 text-sm">
                <a href="#" class="text-white hover:text-[#f261a1] transition-colors duration-300">Tentang Kami</a>
                <a href="#" class="text-white hover:text-[#f261a1] transition-colors duration-300">Kursus</a>
                <a href="#" class="text-white hover:text-[#f261a1] transition-colors duration-300">Bantuan</a>
                <a href="#" class="text-white hover:text-[#f261a1] transition-colors duration-300">Kebijakan Privasi</a>
            </div>

            {{-- Center: Contact & Social --}}
            <div class="flex items-center gap-4">
                <a href="mailto:hello@cotha.id" class="text-white hover:text-[#f261a1] transition-colors duration-300">
                    @component('components.icons.email-icon')
                        @slot('class', 'w-4 h-4')
                    @endcomponent
                </a>
                <a href="https://wa.me/6282141741790" target="_blank" class="text-white hover:text-[#f261a1] transition-colors duration-300">
                    @component('components.icons.whatsapp-icon')
                        @slot('class', 'w-4 h-4')
                    @endcomponent
                </a>
                <a href="https://www.instagram.com/cotha_id/" target="_blank" class="text-white hover:text-[#f261a1] transition-colors duration-300">
                    @component('components.icons.ig-icon-logo')
                        @slot('class', 'w-4 h-4')
                    @endcomponent
                </a>
            </div>

            {{-- Right: Powered by --}}
            <div class="text-sm text-white/80">
                Powered by <span class="font-semibold ">Comfypace</span>
            </div>
        </div>
    </div>
</div>
