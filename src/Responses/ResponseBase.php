<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Intervention\Image\Exception\NotFoundException;
use Prologue\Alerts\Facades\Alert;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class ResponseBase
{
    public static function view($data, string $redirectUrl = null, $msg = null, $showError = false, $view = null)
    {
        $status = 'success';
        $successMessage = 'The item has been ' . $msg . ' successfully';
        $errorMessage = 'The item was not ' . $msg . ' successfully';

        if ($data instanceof \Exception || isset($data['error'])) {
            $status = 'error';
            $errMessage = $data instanceof \Exception ? $data->getMessage() : $data['error'];
            $message = $showError || config('app.debug') ? $errMessage : $errorMessage;

            Alert::warning($message)->flash();

            if ($view) {
                return view($view, [
                    'status' => $status,
                    'error' => $message
                ]);
            }

            return redirect()->back()->withErrors(['error' => $message]);
        }

        Alert::success($successMessage)->flash();

        if ($view) {
            return view($view, [
                'status' => $status,
                'data' => $data
            ]);
        }

        return $redirectUrl ? redirect(backpack_url($redirectUrl)) : redirect()->back();
    }

    public static function json($data, $msg = null, $showError = false, $autoHide = true, $isDirect = false): JsonResponse
    {
        $status = 'success';
        $successMessage = 'The item has been ' . $msg . ' successfully';
        $errorMessage = 'The item was not ' . $msg . ' successfully';
        $responseData = $data;

        if ($data instanceof \Exception || isset($data['error'])) {
            $status = 'error';
            $errMessage = $data instanceof \Exception ? $data->getMessage() : $data['error'];
            $message = $showError || config('app.debug') ? $errMessage : $errorMessage;

            $response = [
                'status' => $status,
                'message' => $message,
                'data' => [],
            ];

            if ($showError) {
                $response['error'] = $errMessage;
            }

            if (!$autoHide) {
                $response['autoHide'] = false;
            }

            return response()->json($response);
        }

        if (isset($data['data'])) {
            $responseData = $data['data'];
        }

        if ($isDirect) {
            return response()->json($responseData);
        }

        $response = [
            'status' => $status,
            'message' => $successMessage,
            'data' => $responseData,
        ];

        return response()->json($response);
    }
}
