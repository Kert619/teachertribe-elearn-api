<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLevelRequest;
use App\Http\Requests\UpdateLevelRequest;
use App\Http\Resources\LevelResource;
use App\Models\Level;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $levels = Level::with(['phase'])->get();
        return LevelResource::collection($levels);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLevelRequest $request)
    {
        $request->validated($request->all());
        
        $level = Level::create([
            'phase_id' => $request->phase_id,
            'name' => $request->name,
            'description' => $request->description,
            'initial_output' => $request->initial_output,
            'expected_output' => $request->expected_output
        ]);

        $level->load(['phase']);
        return $this->success(new LevelResource($level), 'New level has been added');
    }

    /**
     * Display the specified resource.
     */
    public function show(Level $level)
    {
        $level->load(['phase']);
        return new LevelResource($level);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLevelRequest $request, Level $level)
    {
        $request->validated($request->all());

        $level->update([
            'name' => $request->name,
            'description' => $request->description,
            'initial_output' =>$request->initial_output,
            'expected_output' =>$request->expected_output,
        ]);

        $level->load(['phase']);
        return $this->success(new LevelResource($level), 'Level has been added');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Level $level)
    {
        $level->delete();

        return $this->success(null, 'Level has been deleted', 204);
    }

    public function getByCoursePhaseLevel(Request $request){
        $courseName = $request->course;
        $phaseName = $request->phase;
        $levelName = $request->level;
        

        $level = Level::where('name', $levelName)->whereHas('phase', function($query) use ($courseName, $phaseName){
            $query->where('name', $phaseName)->whereHas('course', function($query) use ($courseName){
                $query->where('name', $courseName);
            });
        })->firstOrFail();

        $level->load(['phase', 'phase.course']);

        return new LevelResource($level);
    }
}
