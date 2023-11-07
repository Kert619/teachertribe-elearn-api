<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnswerRequest;
use App\Http\Requests\UpdateAnswerRequest;
use App\Http\Resources\AnswerResource;
use App\Models\Answer;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $answers = Answer::with(['question'])->get();

        return AnswerResource::collection($answers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnswerRequest $request)
    {
        $request->validated($request->all());

        $answer = Answer::create([
            'question_id' => $request->question_id,
            'answer' => $request->answer,
            'is_correct' => $request->is_correct,
        ]);

        $answer->load(['question']);
        return $this->success(new AnswerResource($answer), 'New answer has been added');
    }

    /**
     * Display the specified resource.
     */
    public function show(Answer $answer)
    {
        $answer->load(['question']);
        return new AnswerResource($answer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnswerRequest $request, Answer $answer)
    {
        $request->validated($request->all());

        $answer->update([
            'answer' => $request->answer,
            'is_correct' => $request->is_correct,
        ]);

        $answer->load(['question']);
        return $this->success(new AnswerResource($answer), 'Answer has been added');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Answer $answer)
    {
        $answer->delete();

        return $this->success(null, 'Answer has been deleted', 204);
    }
}
