@extends('layouts.app', ['module' => $module, 'classroom'=> $classroom])

@section('title', 'Lesson')

@section('content')
    
    @include('components.nav_bar.nav-bar', ['isCoursePage' => true])
    @include('lesson.hero.hero')
    @include('lesson.tab_section.tab-section',["unit"=>$unit])
    @include('components/footer/footer')
    <script src="{{ asset('js/tab.js') }}"></script>
    <div x-data="{ 
        raised: false, 
        showTooltip: false,
        message: '',
        showMessageInput: false
    }" class="">
        <button
            @mouseenter="showTooltip = true"
            @mouseleave="showTooltip = false"
            @focus="showTooltip = true"
            @blur="showTooltip = false"
            @click="toggleRaiseHand()"
            :class="raised ? 'bg-blue-500 border-blue-600' : 'bg-white bg-opacity-80 border-blue-400'"
            class="fixed z-50 bottom-8 right-8 w-16 h-16 rounded-full border-2 shadow-lg flex items-center justify-center transition focus:outline-none group"
            type="button"
        >
            <img src='https://em-content.zobj.net/source/apple/96/raised-hand_270b.png' alt='Raise Hand' class='w-10 h-10'>
            <!-- Tooltip -->
            <span x-show="showTooltip" class="absolute bottom-20 right-0 mb-2 w-48 p-2 rounded bg-gray-800 text-white text-xs opacity-90 z-50" style="white-space: normal;">
                Klik untuk mengangkat tangan dan meminta bantuan pengajar
            </span>
        </button>
        
        <!-- Message input modal -->
        <div x-show="showMessageInput" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-96 max-w-sm">
                <h3 class="text-lg font-semibold mb-4">Pesan untuk pengajar (opsional)</h3>
                <textarea 
                    x-model="message" 
                    placeholder="Jelaskan apa yang ingin ditanyakan..."
                    class="w-full p-3 border border-gray-300 rounded-lg mb-4 h-24 resize-none"
                ></textarea>
                <div class="flex gap-2">
                    <button 
                        @click="confirmRaiseHand()"
                        class="flex-1 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
                    >
                        Angkat Tangan
                    </button>
                    <button 
                        @click="showMessageInput = false"
                        class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400"
                    >
                        Batal
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Floating text when raised -->
        <div x-show="raised" class="fixed z-50 bottom-28 right-8 bg-blue-500 text-white px-4 py-2 rounded-full shadow-lg text-base font-semibold animate-bounce">
            Kamu mengangkat tangan
        </div>
        
        <script>
            function toggleRaiseHand() {
                if (!this.raised) {
                    this.showMessageInput = true;
                } else {
                    this.lowerHand();
                }
            }
            
            function confirmRaiseHand() {
                this.raised = true;
                this.showMessageInput = false;
                this.raiseHand();
            }
            
            function raiseHand() {
                fetch('{{ route("raise-hand.toggle") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        classroom_id: {{ $classroom->id }},
                        unit_id: {{ $unit->id }},
                        message: this.message
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
            
            function lowerHand() {
                fetch('{{ route("raise-hand.toggle") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        classroom_id: {{ $classroom->id }},
                        unit_id: {{ $unit->id }},
                        message: ''
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.raised = false;
                        this.message = '';
                        console.log(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        </script>
    </div>
@endsection