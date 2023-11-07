<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\UserResource;
use App\Models\Course;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            $courses = $request->user()->studentClassrooms->first()->courses()->with(['phases', 'classrooms' ,'phases.levels', 'phases.quizzes', 'phases.quizzes.questions', 'phases.quizzes.questions.answers'])->get();
        } else{
            $courses = Course::with(['phases', 'classrooms' ,'phases.levels', 'phases.quizzes', 'phases.quizzes.questions', 'phases.quizzes.questions.answers'])->get();
        }

        $courses->each(function ($course){
            $course->phases->each(function ($phase) {
                $phase->levels->each(function ($level){
                    $level->is_passed = !!auth()->user()->levels()->find($level->id);
                });
            });
        });

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

        $course->load(['phases', 'classrooms' ,'phases.levels', 'phases.quizzes', 'phases.quizzes.questions', 'phases.quizzes.questions.answers']);

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

        $course->load(['phases', 'classrooms' ,'phases.levels', 'phases.quizzes', 'phases.quizzes.questions', 'phases.quizzes.questions.answers']);
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

        $course->load(['phases', 'classrooms' ,'phases.levels', 'phases.quizzes', 'phases.quizzes.questions', 'phases.quizzes.questions.answers']);
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
        $courseName = $request->course;
        $course = Course::with(['phases', 'classrooms' ,'phases.levels', 'phases.quizzes', 'phases.quizzes.questions', 'phases.quizzes.questions.answers'])->where('name', $courseName)->firstOrFail();

        if($request->user()->hasRole('student')){
            if(!$request->user()->studentClassrooms->first()->courses()->find($course->id)){
                return $this->error(null, 'Course not found', 404);
            }
        }

        return new CourseResource($course);
    }
}
