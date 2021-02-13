<?php

namespace App\Imports;

use App\Models\Restaurant;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RestaurantsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    private $from_chars;
    private $to_chars;

    public function __construct()
    {
        $this->from_chars = [
            'Ã',
            'Ã¡',
            'Ã©',
            'Ã­',
            'Ã³',
            'Ãº',
            'Ã±'
        ];
        $this->to_chars = [
            'Á',
            'á',
            'é',
            'í',
            'ó',
            'ú',
            'ñ'
        ];
    }

    public function model(array $row)
    {
        $cleanable = ['name','site','street','city','state'];
        foreach($row as $k=>$v){
            $row[$k] = array_search($k,$cleanable,true)!==false?
                str_replace($this->from_chars,$this->to_chars,$v):
                $v;
        }
        return new Restaurant($row);
    }
}
