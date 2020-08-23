@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                Жуки не любят находиться рядом друг с другом и каждый прячется под отдельным камнем и старается выбирать камни, максимально удаленные от соседей. Так же жуки любят находится максимально далеко от края. Как только жук сел за камень, он более не перемещается. Всего в линии лежат X камней. И туда последовательно бежит прятаться Y жуков. Найти сколько свободных камней будет слева и справа от последнего жука.
                <br><br>
                X может быть до 4 млрд.
                <br><br>
                Примеры<br>
                X=8, Y=1 – ответ 3,4   <br>
                X=8, Y=2 – ответ 1,2   <br>
                X=8, Y=3 – ответ 1,1   <br>
                </div>

                <div class="card-body">    
                    <form name="tek" action="{{ route('tz_tek') }}" target="_self" method="post">
                        @csrf
                        <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="stones">Камней (Х)</label>
                                    <input name="stones" type="text" class="form-control @error('stones') is-invalid @enderror" id="stones" placeholder="" value="{{ old('stones') }}" required autofocus>
                                    <div class="invalid-feedback">
                                    @error('stones')
                                        <strong>{{ $message }}</strong>
                                    @enderror`
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="bugs">Жуков (Y)</label>
                                    <input name="bugs" type="text" class="form-control @error('bugs') is-invalid @enderror" id="bugs" placeholder="" value="{{ old('bugs') }}" required>
                                    <div class="invalid-feedback">
                                    @error('bugs')
                                        <strong>{{ $message }}</strong>
                                    @enderror`
                                    </div>
                                </div>
                        </div>
                        <button class="btn btn-primary btn-lg btn-block" type="submit">Посчитать и нарисовать</button>
                    </form> 

                    {{ old('stones') }} {{ old('bugs') }}

                    <br><br>
                    @isset($bugs_stoun_numbers)                        
                        @for ($i = 1; $i <= $stones['0']; $i++)
                            @php
                                $color = 'dark';    // dd($bugs_stoun_numbers);                   
                            @endphp
                            
                            @if ($i == current($bugs_stoun_numbers))
                                @php
                                    $color = 'success'; 
                                    next($bugs_stoun_numbers);
                                @endphp
                            @endif
                            <button type="button" class="btn btn-{{ $color }}">{{ $i }}</button><br>
                        @endfor
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
