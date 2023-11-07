<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = Question::with(['answers', 'quiz'])->get();

        return QuestionResource::collection($questions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuestionRequest $request)
    {
        $request->validated($request->all());

        $question = Question::create([
            'quiz_id' => $request->quiz_id,
            'question' => $request->question,
            'points' => $request->points,
        ]);

        $question->load(['answers', 'quiz']);
        return $this->success(new QuestionResource($question), 'New question has been added');
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        $question->load(['answers','quiz']);
        return new QuestionResource($question);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuestionRequest $request, Question $question)
    {
        $request->validated($request->all());

        $question->update([
            'question' => $request->question,
            'points' => $request->points,
        ]);

        $question->load(['answers', 'quiz']);
        return $this->success(new QuestionResource($question), 'Question has been added');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $question->delete();

        return $this->success(null, 'Question has been deleted', 204);
    }
}
