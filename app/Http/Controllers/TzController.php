<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\tz\tek\Stone;

class TzController extends Controller
{ 
    public $arr_test;  

    function __construct()
    {
		$this->arr_test = [];
	}

    public function place_bug()
    {   
        $max_val = Stone::max('f_stones');            
        $max_id = Stone::where('f_stones', $max_val)->orderBy('id', 'desc')->value('id');    

        $intdiv = intdiv($max_val, 2);  

        if ($max_val % 2 == 0)
        {     
            $new_bug_stone_id = $max_id + $intdiv;       

            $forward_bug_f_stones = $intdiv;              
            $back_bug_f_stones = $intdiv - 1;        
        }
        else 
        {
            $new_bug_stone_id = $max_id + $intdiv + 1;    

            $forward_bug_f_stones = $intdiv;              
            $back_bug_f_stones = $intdiv;        
        }
    
        $update = Stone::where('id', $new_bug_stone_id)->update(['f_stones' => $forward_bug_f_stones]);    
        $update = Stone::where('id', $max_id)          ->update(['f_stones' => $back_bug_f_stones]); 

        $this->arr_test [] = 
        [ 
            'max_val' => $max_val,
            'max_id' => $max_id, 
            'new_bug_stone_id' => $new_bug_stone_id, 
            'forward_bug_f_stones' => $forward_bug_f_stones, 
            'back_bug_f_stones' => $back_bug_f_stones
        ];

        return $new_bug_stone_id;
    }

    public function count_by_MySQL(Request $request)
    {   
        $validator = Validator::make($request->all(), 
            [
                'stones' => 'required|numeric|max:4000000000',
                'bugs'   => 'required|numeric|max:2000000000',
            ]);

        $stones = $request->input('stones');
        $bugs = $request->input('bugs');

        if ($bugs <= $stones)
        {
            /*
            fill the table with all stones and its properties how many free stones step forward
            first record is mot a stone. 
            it is a start edge (with property how many free stones step forward)
            let it be first bug wich run without queue))
            */
            $truncate = Stone::truncate();        
            $arr_stones = array_fill(0, $stones + 1, ['id' => null]);
            $insert = Stone::insert($arr_stones);

            $update = Stone::where('id', 1)->update(['f_stones' => $stones]);

            for ($i = 1; $i <= $bugs; $i++) 
            {    
                $all_bug_stone_ids [] = $this->place_bug();
            }

            $last_bug_stone_id = last($all_bug_stone_ids);
            $last_bug_stone_number = $last_bug_stone_id - 1;
            $last_bug_stone_forward  = Stone::find($last_bug_stone_id)
                                            ->f_stones; 
            $last_bug_stone_backward = Stone::where('id', '<', $last_bug_stone_id)
                                            ->whereNotNull('f_stones')
                                            ->orderBy('id', 'desc')
                                            ->first()
                                            ->f_stones; 

            $last_bug_stone_properties =
            [
                'last_bug_stone_id' => $last_bug_stone_id,
                'last_bug_stone_number' => $last_bug_stone_number,
                'last_bug_stone_forward' => $last_bug_stone_forward,
                'last_bug_stone_backward' => $last_bug_stone_backward,
            ];
            
            sort($all_bug_stone_ids);          

            $arr_to_view = 
            [
                'all_stones_count' => $stones, 
                'all_bug_stone_ids' => $all_bug_stone_ids, 
                'last_bug_stone_properties' => $last_bug_stone_properties
            ];          

            return view('tz_tek', $arr_to_view);
        }    
        else
            return '<h3> Жуков должно быть не меньше камней! </h3>';
    }
}

/*
Надо запоминать в массив ВСЕ ряды свободных камней между занятыми.
Искать средствами php нет смысла, т.к. масив миллиарда свободных кусков в динамическую память не влезет.
варианты:
- nosql (думаю, осилит 4 млрд записей легче.);
- запись в файл (быстрее бд но трудозатратно по настройкам);
- запись в базу (выбрал пал на MySQL, т.к. настроена на моем сервере, и с nosql в рабочих проектах не сталкивался).

Можно заметить, что расстояние например левого жука (при рассадке слева направо) от края отрезка длиной X 
в зависимости от числа жуков представляет собой ряд: L(1) = X/2, L(2) = X/4, L(3) = X/4, L(4) = X/8, L(5) = X/8, L(6) = X/8, L(7) = X/8... и т.д. 
Осталось сообразить, что же это за формула. Степень двойки,.. Треугольник Паскаля... Простите, что ковырять не стал. Показываю в т.з. практические навыки.
*/