<div class="block w-100 text-center">
    @foreach ($users_commission as $item)
        <div class="w-45 inline-block text-center my-50 align-top">
            <span class="font-bold">
                ----------------------------------------------------
            </span>
            <span class="font-bold block">{!! strtoupper($item->fullName()) !!}</span>
            <div class="text-xs text-center uppercase" style="width: 350px; margin:0 auto; font-weight:100">{!! $item->position !!}</div>
        </div>
    @endforeach
</div>