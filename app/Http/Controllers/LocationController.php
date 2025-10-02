<?php

namespace App\Http\Controllers;

use App\Exceptions\CommonCustomException;
use App\Interfaces\InterfaceClass;
use App\Models\Location;
use App\Models\Profile;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    public function index()
    {
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::MASTER_LOCATION_LIST)) {
            return abort(403, 'Insufficient permission.');
        }

        $isReqLocAllowed = false;
        if (Profile::authorize(InterfaceClass::MASTER_LOCATION_CREATE)) {
            $isReqLocAllowed = true;
        }

        (array) $data = [
            'is_req_loc_allowed' => $isReqLocAllowed,
        ];

        return view('master-location', $data);
    }

    public function getLocationListDatatable(Request $request){
        $Auth = Auth::user();

        $sql = ("SELECT * FROM locations
        ORDER BY id ASC");

        $data = DB::select($sql);

        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn("actions", function($row) {
            $buttons = '';
            if (Profile::authorize(InterfaceClass::MASTER_LOCATION_EDIT)) {
                $buttons = '
                <button type="submit" class="btn btn-primary editLocation" id="btnEditLocation" data-l="'.$row->id.'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                    </svg>
                </button>';
            }
            return $buttons;
        })
        ->rawColumns(['actions'])
        ->make(true);
    }

    public function postLocationReqSubmit(Request $request){
        // return response()->json($request);

        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'location_code' => ['required', 'string'],
            'location_name' => ['required', 'string'],
            'flag' => ['required', 'integer'],
        ]);

        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $code = Str::upper($request['location_code']);

        DB::beginTransaction();
        try {
            // Insert Location
            Location::create([
                'location_code' => $code,
                'location_name' => $validated['location_name'],
                'flag' => $validated['flag'],
                'created_by' => $user?->id,
                'updated_by' => $user?->id,
            ]);

            (string) $title = 'Success';
            (string) $message = 'Location request successfully submitted';
            (string) $route = route('master-location');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => null,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when submit location request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException($e->getMessage(), 422, $e);
        }
    }

    public function getOldDataLocationEdit(Request $request){
        $location = DB::table('locations')->where('id', $request->location_id)->first();
        return response()->json($location);
    }

    public function postLocationUpdateSubmit(Request $request){

        // return response()->json($request);
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'location_id' => ['required', 'integer'],
            'location_code' => ['required', 'string'],
            'location_name' => ['required', 'string'],
            'flag' => ['required', 'integer'],
        ]);

        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();


        // Huruf Kapital
        $code = Str::upper($validated['location_code']);

        DB::beginTransaction();
        try {
            $location = Location::find($validated['location_id']);
            $location->location_code = $code;
            $location->location_name = $validated['location_name'];
            $location->flag = $validated['flag'];
            $location->updated_by = $user->id;
            $location->update();

            (string) $title = 'Success';
            (string) $message = 'Location request successfully updated';
            (string) $route = route('master-location');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => null,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when update location request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException($e->getMessage(), 422, $e);
        }
    }



    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'location_code' => 'required',
    //         'location_name' => 'required',
    //     ]);

    //     if (isset($request->flag)) {
    //         $validatedData['flag'] = 1;
    //     } else {
    //         $validatedData['flag'] = 2;
    //     }

    //     $validatedData['created_by'] = 0;
    //     $validatedData['updated_by'] = 0;

    //     Location::create($validatedData);

    //     return redirect('/master-location')->with('success', 'Data Location Berhasil Ditambahkan !!!');

    // }

    // public function update(Request $request)
    // {
    //     $location = Location::find($request->id);

    //     $location->location_code = $request->location_code;
    //     $location->location_name = $request->location_name;
    //     $location->flag = $request->flag;
    //     $location->updated_by = 0;

    //     $location->update();

    //     return redirect('/master-location')->with('success', 'Data Location Berhasil Diperbaharui !!!');
    // }

    // public function destroy(Request $request)
    // {
    //     $location = Location::find($request->id);
    //     $location->flag = 0;
    //     $location->update();
    //     return redirect('/master-location')->with('success', 'Data Location Berhasil Dihapus !!!');

    // }
}
