<?php

namespace App\Http\Controllers;

use App\Imports\RestaurantsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RestaurantsImportController extends Controller
{
    public function import()
    {
        $files = array_filter(scandir(storage_path()),function($file){
            return strpos($file,'.csv') === strlen($file)-4;
        });

        $exceptions = [];

        foreach($files as $file){
            try{
                Excel::import(new RestaurantsImport(), storage_path($file));
            }catch(\Exception $e){
                $exceptions[] = $e;
            }
        }

        return ['csv_files'=>$files,'exceptions'=>$exceptions];
    }
}
