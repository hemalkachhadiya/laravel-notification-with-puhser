<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Events\SenMessage;

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendNotification( Request $request ) {
        $validated = $request->validate( [
            'name' => 'required',
            'message' => 'required',
        ] );

        event( new SenMessage( $request->name, $request->message ) );
        return response()->json(
            array(
                'message' => 'Message send successfully.',
                'status' => 'success',
                'code' => 200
            ),
            200
        );
    }
}
