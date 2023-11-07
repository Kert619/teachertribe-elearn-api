<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quizzes = Quiz::with(['phase', 'questions'])->get();
        return QuizResource::collection($quizzes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuizRequest $request)
    {
        $request->validated($request->all());

        $quiz = Quiz::create([
            'phase_id' => $request->phase_id,
            'name' => $request->name,
        ]);

        $quiz->load(['phase', 'questions']);
        return $this->success(new QuizResource($quiz), 'New quiz has been added');
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz)
    {
        $quiz->load(['phase', 'questions']);
        return new QuizResource($quiz);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuizRequest $request, Quiz $quiz)
    {
        $request->validated($request->all());

        $quiz->update([
            'name' => $request->name,
        ]);

        $quiz->load(['phase', 'questions']);
        return $this->success(new QuizResource($quiz), 'Quiz has been added');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return $this->success(null, 'Quiz has been deleted', 204);
    }
}
