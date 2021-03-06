<?php
namespace App\Http\Controllers;

use App\Models\Catalogs\UnitType;
use App\Http\Controllers\Controller;
use App\Http\Requests\UnitTypeRequest;
use App\Http\Resources\UnitTypeCollection;
use App\Http\Resources\UnitTypeResource;

class UnitTypeController extends Controller
{
    public function records()
    {
        $records = UnitType::all();

        return new UnitTypeCollection($records);
    }

    public function record($id)
    {
        $record = new UnitTypeResource(UnitType::findOrFail($id));

        return $record;
    }

    public function store(UnitTypeRequest $request)
    {
        $id = $request->input('id');
        $unit_type = UnitType::firstOrNew(['id' => $id]);
        $unit_type->fill($request->all());
        $unit_type->save();

        return [
            'success' => true,
            'message' => ($id)?'Unidad editada con éxito':'Unidad registrada con éxito'
        ];
    }

    public function destroy($id)
    {
        $record = UnitType::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Unidad eliminada con éxito'
        ];
    }
}