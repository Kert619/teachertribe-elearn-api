<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePhaseRequest;
use App\Http\Requests\UpdatePhaseRequest;
use App\Http\Resources\PhaseResource;
use App\Models\Phase;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class PhaseController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $phases = Phase::with(['course', 'levels', 'quizzes'])->get();

        return PhaseResource::collection($phases);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePhaseRequest $request)
    {
        $request->validated($request->all());

        $phase = Phase::create([
            'course_id' => $request->course_id,
            'name' => $request->name,
        ]);

        $phase->load(['course',]);
        return $this->success(new PhaseResource($phase), 'New phase has been added');
    }

    /**
     * Display the specified resource.
     */
    public function show(Phase $phase)
    {
        $phase->load(['course', 'levels', 'quizzes']);
        return new PhaseResource($phase);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePhaseRequest $request, Phase $phase)
    {
        $request->validated($request->all());

        $phase->update([
            'name' => $request->name,
        ]);

        $phase->load(['course', 'levels', 'quizzes']);
        return $this->success(new PhaseResource($phase), 'Phase has been added');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Phase $phase)
    {
        $phase->delete();

        return $this->success(null, 'Phase has been deleted', 204);
    }
}
