<?php

namespace App\Http\Controllers;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CommonController extends Controller
{
    public function returnResponse($code, $message, $data = null)
    {
        if ($code == 3) {

            $return = array(
                'status' => 'VALIDATION',
                'code' => $code,
                'data' => $message,
                'message' => 'The data given was Invalid',
            );

            return json_encode($return);
        }

        if ($code == 0) {

            if(!is_null($data)) {

                $return = array(
                    'status' => 'OK',
                    'code' => $code,
                    'data' => $data,
                    'message' => $message,
                );
            }

            if(is_null($data)) {
                
                $return = array(
                    'status' => 'OK',
                    'code' => $code,
                    'message' => $message,
                );
            }

            return json_encode($return);
        }

        if ($code == 1) {

            $return = array(
                'status' => 'ERROR',
                'code' => $code,
                'message' => $message,
            );

            return json_encode($return);
        }

    }

    public function imageUrl($input)
    {
        return $input == null ? 'https://picsum.photos/300/300' : asset('storage').'/'.$input;
    }

    public function imageStore($path, $image)
    {
        $image_name = str_replace(' ', '', time()).trim($image->getClientOriginalName());
        $image->storeAs($path, $image_name);  
        $url_image = $path.'/'.$image_name;

        return $url_image;
    }

    public function paginate($data, $paginate = 12, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $data = $data instanceof Collection ? $data : Collection::make($data);
        return new LengthAwarePaginator($data->forPage($page, $paginate)->values(), $data->count(), $paginate, $page);
    }
}
