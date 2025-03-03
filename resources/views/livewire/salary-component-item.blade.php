<div class="border-b ">
    <div class=" parent flex justify-between items-center cursor-pointer bg-gray-100 hover:bg-gray-200 border"

        style="font-size: 13px; height: 35px; padding: 0px 10px 0px {{ 20 + ($level * 15) }}px;">

        <span>
            <span wire:click="toggleExpand('{{ $component['name'] }}')">
                @if (!empty($component['children']))
                @if ($expanded[$component['name']])
                <img style="height: 10px;" src="{{asset('images/minuss.png')}}" alt="">
                @else
                <img style="height: 10px;" src="{{asset('images/plus.png')}}" alt="">
                @endif
                @endif
            </span>

            <span>{{ $component['name'] }}</span>
        </span>

        <span style="margin-left: auto;">{{ number_format((float) $component['amount'], 2) }}</span>
    </div>
    @if (!empty($component['children']) && $expanded[$component['name']])
    <div>
        @foreach ($component['children'] as $child)
        @include('livewire.salary-component-item', ['component' => $child, 'level' => $level + 1])
        @endforeach
    </div>
    @endif
</div>
