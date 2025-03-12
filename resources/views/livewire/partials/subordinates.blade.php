@foreach ($subordinates as $employee)
    <li>
        <div class="rectangle">
            <strong>{{ $employee['info']['first_name'] }} {{ $employee['info']['last_name'] }}</strong>
            <br>
            <span>{{ $employee['info']['job_role'] }}</span>
        </div>

        @if (!empty($employee['subordinates']))
            <ul class="nested">
                @include('livewire.partials.subordinates', ['subordinates' => $employee['subordinates']])
            </ul>
        @endif
    </li>
@endforeach
