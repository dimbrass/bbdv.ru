<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TzController extends Controller
{
    public function count(Request $request)
    {   
        $validator = Validator::make($request->all(), 
            [
                'stones' => 'required|numeric|max:4000000000',
                'bugs'   => 'required|numeric|max:4000000000',
            ]);

        $stones = $request->input('stones');
        $bugs = $request->input('bugs');
        $bugs_stoun_numbers = [];
        $half_1 = 0;
        $half_2 = $stones;

        for ($i = 0; $i < $bugs; $i++) 
        {     
            $stones = $half_2;
            $intdiv = intdiv($stones, 2);
            if ($stones % 2 == 0)
            {
                $bugs_stoun_numbers[] = $half_1 + $intdiv;
                $half_1 = $intdiv - 1;
                $half_2 = $intdiv;
            }
            else 
            {
                $bugs_stoun_numbers[] = $half_1 + $intdiv + 1;
                $half_1 = $intdiv;
                $half_2 = $intdiv;
            } 
        }
        dd($bugs_stoun_numbers);

        sort($bugs_stoun_numbers);

        return view('tz_tek', ['bugs_stoun_numbers' => $bugs_stoun_numbers, 'stones' => $request->input('stones')]);
    }
}
