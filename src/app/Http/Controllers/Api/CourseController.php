<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $courses = null;

        if($request->user()->hasRole('student')){
            $courses = $request->user()->studentClassrooms->first()->courses()->with(['phases', 'phases.levels', 'phases.quizzes'])->get();
        } else{
            $courses = Course::with(['phases', 'phases.levels', 'phases.quizzes'])->get();
        }

        return CourseResource::collection($courses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        $request->validated($request->all());

        $course = Course::create([
            'name' => $request->name,
        ]);

        return $this->success(new CourseResource($course), 'New course has been created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Course $course)
    {
        if($request->user()->hasRole('student')){
            if(!$request->user()->studentClassrooms->first()->courses()->find($course->id)){
                return $this->error(null, 'Course not found', 404);
            }
        }

        return new CourseResource($course);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $request->validated($request->all());

        $course->update([
            'name' => $request->name
        ]);

        return $this->success(new CourseResource($course), 'Role has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return $this->success(null, 'Course has been deleted', 204);
    }

    public function getByName(Request $request){
        $isStudent = $request->user()->hasRole('student');
        $levels_passed = $request->user()->levels;

        $courseName = $request->course;
        
        $course = Course::with(['phases', 'phases.levels', 'phases.quizzes'])->where('name', $courseName)->firstOrFail();
        if(!$course) return $this->error(null, 'Course not found', 404);

        
        if($isStudent){
            if(!$request->user()->studentClassrooms->first()->courses()->find($course->id)){
                return $this->error(null, 'Course not found', 404);
            }
        }

        $is_previous_level_passed = true;

        foreach($course->phases as $phase){
            $phase->levels->each(function ($level) use ($levels_passed, &$is_previous_level_passed, $isStudent) {
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
            });
        }
        
        return new CourseResource($course);
    }
}