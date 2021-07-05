<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StationController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const FILENAME = 'Stations_2019.csv';


    public function getStations(Request $request)
    {
        // get all the params form the request
        $sortByColumn = $request->input('sortByColumn') ?? '';
        $ascending = $request->input('ascending') ?? '';
        $page = $request->input('pageNumber') ?? 1 ;
        $limit = $request->input('itemPerPage') ?? null;
        $offset = $limit*($page-1);

        $path = $this->getfilePath();

        if (!file_exists($path) || !is_readable($path))
        return false;

        $data = array();
        $lines = explode( "\n", file_get_contents( $path ) );
        $columns = str_getcsv( array_shift( $lines ) );
        foreach ( $lines as $line ) {

            $row = array();

            foreach ( str_getcsv( $line ) as $key => $field ){
                $row[ $columns[ $key ] ] = $field;
            }

            $row = array_filter( $row );

            $data[] = $row;

        }

        $stations = array_slice($data, $offset, $limit);
        
        //sorting
        if($sortByColumn == 'name' || $sortByColumn == 'Code'){

            $this->sortResults($stations, $sortByColumn, $ascending);
        }

        $results = [];
        $results['count'] = count($data);
        $results['columns'] = $columns;
        $results['data'] = $stations;

        return response()->json($results);
    }


    public function getCordinates(Request $request)
    {

        $path = $this->getfilePath();

        if (!file_exists($path) || !is_readable($path))
        return false;

        $data = array();
        $lines = array_map('str_getcsv', file($path));
        array_shift($lines);
        
        foreach ($lines as $line) {

            $row = array();
            
            $row['label'] = $line[1];
            $row['lat'] = (float)$line[2];
            $row['lng'] = (float)$line[3];

            $data[] = $row;
        }

        return response()->json($data);
    }

    //private method to handle sorting
    private function sortResults(&$data, $sortByColumn, $ascending){

        usort($data, function ($a, $b) use ($sortByColumn, $ascending) {

            if($sortByColumn == 'name'){
                $cmp = strcmp($a[$sortByColumn], $b[$sortByColumn]);
                return $ascending == 'true' ? $cmp : -$cmp;
            }

            if($sortByColumn == 'Code'){
                
                return $ascending == 'true' ? $a[$sortByColumn] > $b[$sortByColumn] : $a[$sortByColumn] < $b[$sortByColumn];
            }
        });

    }

    //get file path
    private function getFilePath(){

        return storage_path(self::FILENAME);
    }

}
