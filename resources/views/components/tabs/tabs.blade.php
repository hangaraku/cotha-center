<div class="tab flex flex-col">
    <ul class="flex flex-wrap text-sm font-medium text-center">

        {{-- @component('components.tabs.tab-link')
            @slot('href', '#tab-content-2')
            @slot('icon')
                @component('components.icons.chat-bubble-left-right')
                    @slot('class', 'w-4 sm:w-6')
                @endcomponent
            @endslot
            @slot('label', 'Resource')
        @endcomponent
        @component('components.tabs.tab-link')
            @slot('href', '#tab-content-3')
            @slot('icon')
                @component('components.icons.pencil')
                    @slot('class', 'w-4 sm:w-6')
                @endcomponent
            @endslot
            @slot('label', 'Exercise')
        @endcomponent --}}
    </ul>
    @component('components.tabs.tab-content')
        @slot('tabContentId', 'tab-content-1')
        @slot('class', 'flex flex-col gap-y-6 bg-white rounded-xl p-6 md:p-10 w-full text-black')
        @slot('content')
            @php
                $videoUrl = $unit->img_url ?? null;
                $embedHtml = null;
                if ($videoUrl) {
                    if (Str::contains($videoUrl, 'iframe')) {
                        $embedHtml = $videoUrl;
                    } elseif (preg_match('/youtu\.be\/([\w-]+)/', $videoUrl, $matches)) {
                        $videoId = $matches[1];
                        $embedHtml = '<iframe class="w-full h-full" width="100%" height="100%" src="https://www.youtube.com/embed/' . $videoId . '" frameborder="0" allowfullscreen></iframe>';
                    } elseif (preg_match('/youtube\.com.*[\?&]v=([\w-]+)/', $videoUrl, $matches)) {
                        $videoId = $matches[1];
                        $embedHtml = '<iframe class="w-full h-full" width="100%" height="100%" src="https://www.youtube.com/embed/' . $videoId . '" frameborder="0" allowfullscreen></iframe>';
                    } elseif (preg_match('/youtube\.com\/embed\/([\w-]+)/', $videoUrl, $matches)) {
                        $videoId = $matches[1];
                        $embedHtml = '<iframe class="w-full h-full" width="100%" height="100%" src="https://www.youtube.com/embed/' . $videoId . '" frameborder="0" allowfullscreen></iframe>';
                    } elseif (preg_match('/youtube\.com\/watch\?v=([\w-]+)/', $videoUrl, $matches)) {
                        $videoId = $matches[1];
                        $embedHtml = '<iframe class="w-full h-full" width="100%" height="100%" src="https://www.youtube.com/embed/' . $videoId . '" frameborder="0" allowfullscreen></iframe>';
                    }
                }
            @endphp


            @if($embedHtml)
                <div class="mb-6">
                    <h3 class="text-2xl md:text-2xl font-semibold text-blue-900 mb-2">Video</h3>
                    <div class="aspect-video w-full rounded-lg overflow-hidden bg-black">
                        {!! $embedHtml !!}
                    </div>
                </div>
            @endif
            @php
                $topicContent = trim($slot);
                $showTopic = !empty($topicContent) && $topicContent !== '-';
            @endphp
            @if($showTopic)
            <div class="flex flex-col gap-y-2">
                <h3 class="text-2xl md:text-2xl font-semibold text-blue-900">Topic</h3>
                <p class="text-sm md:text-base text-black">{{$slot}}</p>
            </div>
            @endif
        @endslot
    @endcomponent
    {{-- @component('components.tabs.tab-content')
        @slot('tabContentId', 'tab-content-2')
        @slot('class', 'hidden flex flex-col gap-y-6 text-primary')
        @slot('content')
            <div class="flex flex-col gap-y-2">
                <h3 class="text-base md:text-xl font-semibold">Resource Tab</h3>
                <p class="text-sm md:text-base">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Eius explicabo molestias rerum voluptates
                    fugiat
                    provident optio tenetur aut culpa. Assumenda explicabo rerum laudantium dolores eius! Consequuntur,
                    quas
                    magni? Repudiandae officia consequuntur perspiciatis eligendi sed earum quasi dolorum vero placeat
                    excepturi. Hic error voluptate autem, iste doloribus eos fugit doloremque odit?
                </p>
            </div>
        @endslot
    @endcomponent
    @component('components.tabs.tab-content')
        @slot('tabContentId', 'tab-content-3')
        @slot('class', 'hidden flex flex-col gap-y-6 text-primary')
        @slot('content')
            <div class="flex flex-col gap-y-2">
                <h3 class="text-base md:text-xl font-semibold">Notes Tab</h3>
                <p class="text-sm md:text-base">
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Modi officia, repellat commodi accusamus nulla,
                    inventore numquam laborum non in ipsa fuga distinctio repellendus necessitatibus soluta animi, ratione ipsam
                    at accusantium?
                </p>
            </div>
        @endslot
    @endcomponent --}}
</div>


