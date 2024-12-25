<?php

namespace App\Http\Controllers;

use App\Models\LicensePlate;
use App\Models\ReportRepair;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $licensePlate = LicensePlate::has('report')->with('report')->get();
        return Inertia::render('Reports/Show', ['licensePlate' => $licensePlate]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $licensePlate = LicensePlate::get();
        return Inertia::render('Reports/Create', ['license_plates' => $licensePlate]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'plate' => 'nullable|string',
            'report' => 'nullable|string',
            'service' => 'array',
            'service.*' => 'string',
        ]);

        // dd($request);
        $servicesToInsert = [];
        foreach ($data['service'] as $service) {
            $servicesToInsert[] = [
                'license_plate_id' => $data['plate'],
                'repair' => $service,
                'created_at' => now(),
                'updated_at' => now()
            ];
        };
        if ($request['other'] !== null) {
            array_push($servicesToInsert, ['license_plate_id' => $request->plate, 'repair' => $request->other, 'created_at' => now(), 'updated_at' => now()]);
        }

        // dd($servicesToInsert);
        ReportRepair::insert($servicesToInsert);

        return redirect()->route('report.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $report = ReportRepair::where('license_plate_id', $id);
            if (!$report) {
                dd('Error');
            }

            $report->delete();
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
