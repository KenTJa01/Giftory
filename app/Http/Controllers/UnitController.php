<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::orderBy('id', 'asc')->where('flag',1)->orWhere('flag', 2)->get();
        return view('master-unit', [
            'units' => $units
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'unit_name' => 'required',
        ]);

        if (isset($request->flag)) {
            $validatedData['flag'] = 1;
        } else {
            $validatedData['flag'] = 2;
        }

        $validatedData['created_by'] = 0;
        $validatedData['updated_by'] = 0;

        Unit::create($validatedData);

        return redirect('/master-unit')->with('success', 'Data Unit Berhasil Ditambahkan !!!');
    }

    public function update(Request $request)
    {
        $unit = Unit::find($request->id);
        $unit->unit_name = $request->unit_name;

        if ($request['flag'] != null){
            $flag = 1;
        }else{
            $flag = 2;
        }

        $unit->flag = $flag;
        $unit->updated_by = 0;

        $unit->update();

        return redirect('/master-unit')->with('success', 'Data Unit Berhasil Diperbaharui !!!');
    }

    public function destroy(Request $request)
    {
        $unit = Unit::find($request->id);
        $unit->flag = 0;
        $unit->update();
        return redirect('/master-unit')->with('success', 'Data Unit Berhasil Dihapus !!!');
    }
}
