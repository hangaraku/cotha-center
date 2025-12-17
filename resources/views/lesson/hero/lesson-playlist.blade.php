<div class="hidden w-full lg:flex flex-col overflow-y-scroll h-full">
    @foreach($module->units as $item)
    @component('lesson.hero.lesson-playlist-item')
        @if($item->id == $unit->id)
        @slot('isCurrentLesson', true)
        @endif
        @slot('href',route('lesson',["id"=>$module->id,"unitId"=>$item->id]))
        @slot('onFavoriteClick', 'alert("Favorite clicked")')
        @slot('title', $item->name)
        @slot('duration', '')
    @endcomponent
    @endforeach
   
</div>
