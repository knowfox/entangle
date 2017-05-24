<?php

namespace Knowfox\Entangle\Controllers;

use Illuminate\Http\Request;
use Knowfox\Entangle\Services\ImportService;
use Knowfox\Http\Controllers\Controller;

class ImportController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $this->fromToken($request->input('token'));
        }
        catch (\Exception $e) {
            return response()
                ->json([
                    'status' => 'error',
                    'message' => 'No user for token "' . $request->input('token') . '"',
                ], 404);
        }

        if ($user->id !== 1) {
            return response()
                ->json([
                    'status' => 'error',
                    'message' => 'Only user #1 may do this',
                ], 401);
        }

        return response()->json([
            'csrf_token' => csrf_token(),
        ]);
    }

    public function save(ImportService $importer, Request $request)
    {
        try {
            $user = $this->fromToken($request->input('token'));
        }
        catch (\Exception $e) {
            return response()
                ->json([
                    'status' => 'error',
                    'message' => 'No user for token "' . $request->input('token') . '"',
                ], 404);
        }

        if ($user->id !== 1) {
            return response()
                ->json([
                    'status' => 'error',
                    'message' => 'Only user #1 may do this',
                ], 401);
        }

        switch ($request->type) {
            case 'user':
                $data = ['person' => $importer->savePerson($request->all())];
                break;

            case 'event':
                $data = ['event' => $importer->saveEvent($request->all())];
                break;

            default:
                return response()
                    ->json([
                        'status' => 'error',
                        'message' => 'Unknown type "' . $request->type . '"',
                    ], 401);
        }

        return response()
            ->json([
                'status' => 'success',
            ] + $data);
    }
}
