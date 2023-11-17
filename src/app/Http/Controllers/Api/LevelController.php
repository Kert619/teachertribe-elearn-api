<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLevelRequest;
use App\Http\Requests\UpdateLevelRequest;
use App\Http\Resources\LevelResource;
use App\Models\Course;
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
        $isStudent = $request->user()->hasRole('student');
        $levels_passed = $request->user()->levels;

        $course = Course::with(['phases', 'phases.levels'])->where('name', $courseName)->firstOrFail();
        $levels = $course->phases->flatMap(function ($phase){
            return $phase->levels;
        });

       

        $is_previous_level_passed = true;

        foreach($levels as $level){
          $level->is_passed = !!$levels_passed->find($level->id);
          $level->is_unlocked = false;

          if($level->is_passed){
            $level->is_unlocked = true;
          } elseif(!$level->is_passed && $is_previous_level_passed) {
                $level->is_unlocked = true;
                $is_previous_level_passed = false;
          }

          if(!$isStudent) {
            $level->is_passed = true;
            $level->is_unlocked = true;
          }
        }
        
        $level = Level::where('name', $levelName)->whereHas('phase', function($query) use ($courseName, $phaseName){
            $query->where('name', $phaseName)->whereHas('course', function($query) use ($courseName){
                $query->where('name', $courseName);
            });
        })->firstOrFail();

      
        $is_level_unlocked = $levels->first(function($value) use ($level) {
            return $value->id == $level->id;
        })->is_unlocked;

        if(!$is_level_unlocked) {
            return $this->error(null,'Level is not unlocked yet.', 400);
        }

        $level->load(['phase', 'phase.course']);

        return new LevelResource($level);
    }
}