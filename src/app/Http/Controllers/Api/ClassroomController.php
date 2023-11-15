<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignCoursesRequest;
use App\Http\Requests\AssignStudentsRequest;
use App\Http\Requests\StoreClassroomRequest;
use App\Http\Requests\UpdateClassroomRequest;
use App\Http\Resources\ClassroomResource;
use App\Models\Classroom;
use App\Models\Role;
use App\Models\User;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClassroomController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        

        $classroom = Classroom::with(['courses','students','user', 'courses.phases'])->whereBelongsTo($request->user())->get();

        return ClassroomResource::collection($classroom);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClassroomRequest $request)
    {
        $request->validated($request->all());

        $classroom = Classroom::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
        ]);

        $classroom->load(['courses', 'user','students', 'courses.phases']); 
        return $this->success(new ClassroomResource($classroom), 'New classroom has been added');
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
        $classroom->load(['courses', 'students', 'user', 'courses.phases']);
        return new ClassroomResource($classroom);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClassroomRequest $request, Classroom $classroom)
    {
        $request->validated($request->all());

        $classroom->update([
            'name' => $request->name
        ]);

        $classroom->load(['courses', 'students', 'user', 'courses.phases']);
        return $this->success(new ClassroomResource($classroom), 'Classroom has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom)
    {
        $classroom->delete();
        return $this->success(null,'Classroom has been deleted', 204);
    }

    public function assignCourses(AssignCoursesRequest $request, Classroom $classroom){
        $validated= $request->validated();

        $classroom->courses()->attach($validated['courses']);

        $classroom->load(['courses', 'students', 'user', 'courses.phases']);

        return $this->success(new ClassroomResource($classroom), 'Courses has been assigned to class');
    }

    public function getByName(Request $request){
        $classroomName = $request->classroom;
        $classroom = Classroom::with(['courses', 'students', 'user', 'courses.phases'])->where('name', $classroomName)->firstOrFail();

        return new ClassroomResource($classroom);
    }

    public function assignStudents(AssignStudentsRequest $request, Classroom $classroom){ 
        $validated = $request->validated();
        $students = [];

        foreach ($validated['students'] as $student) {
            $studentData = [
                'name' => $student['name'],
                'email' => $student['email'],
                'password' => Hash::make($student['password']),
                'user_id' => $request->user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            array_push($students, $studentData);
        }

         User::insert($students);

        //  RETRIEVE ALL INSERTED USERS
         $insertedStudents = User::whereIn('email', array_column($students, 'email'))->get();

        //  RETRIEVE STUDENT ROLE INSTANCE
         $role =  Role::where('code', 'student')->firstOrFail();

        //  ASSIGN USERS TO STUDENT ROLE
         $role->users()->attach(array_column($insertedStudents->toArray(), 'id'));

        //  ASSIGN STUDENTS TO THIS CLASSROM
         $classroom->students()->attach(array_column($insertedStudents->toArray(), 'id'));
         $classroom->load(['courses', 'user','students']); 
         return $this->success(new ClassroomResource($classroom), 'New students inserted');
    }
}