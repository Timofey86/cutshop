<a href="№" class="p-6 rounded-xl bg-card hover:bg-card/60">
{{--    {{dd($storage = Storage::disk('images'))}}--}}
    <div class="h-12 md:h-16">
        <img src="{{/*asset('storage') .'/' . $item->thumbnail*/$item->makeThumbnail('70x70')}}" class="object-contain w-full h-full" alt="{{$item->title}}">
    </div>
{{--    {{dd(asset('storage/app' . '/' . $item->thumbnail))}}--}}
    <div class="mt-8 text-xs sm:text-sm lg:text-md font-semibold text-center">
        {{$item->title}}
    </div>
</a>
