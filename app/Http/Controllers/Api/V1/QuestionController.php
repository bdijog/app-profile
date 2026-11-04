<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\AnswerStatus;
use App\Enums\QuestionStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\QuestionResource;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuestionController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Question::query()
            ->with([
                'topic',
                'answers' => function ($q) {
                    $q->where(function ($sub) {
                        $sub->where('status', AnswerStatus::Published->value)
                            ->orWhere('is_published', true);
                    })->latest();
                },
            ])
            ->answered()
            ->latest();

        if ($request->filled('topic')) {
            $topic = $request->input('topic');
            $query->whereHas('topic', function ($q) use ($topic) {
                if (is_numeric($topic)) {
                    $q->where('id', $topic);
                } else {
                    $q->where('slug', $topic);
                }
            });
        }

        if ($request->filled('search')) {
            $search = '%'.$request->input('search').'%';
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', $search)
                    ->orWhere('body', 'like', $search);
            });
        }

        $perPage = min(max((int) $request->input('per_page', 10), 1), 50);

        return QuestionResource::collection($query->paginate($perPage));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'topic_id' => ['required', 'exists:question_topics,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:10', 'max:5000'],
        ], [
            'topic_id.required' => 'Pilih topik pertanyaan.',
            'topic_id.exists' => 'Topik yang dipilih tidak valid.',
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'subject.required' => 'Subjek pertanyaan wajib diisi.',
            'body.required' => 'Isi pertanyaan wajib diisi.',
            'body.min' => 'Isi pertanyaan minimal 10 karakter.',
        ]);

        $question = Question::create([
            ...$validated,
            'status' => QuestionStatus::Pending->value,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Pertanyaan Anda berhasil dikirim. Tim kami akan meninjau dan merespon secepatnya.',
            'data' => [
                'id' => $question->id,
                'subject' => $question->subject,
                'status' => $question->status->getLabel(),
                'created_at' => $question->created_at->toISOString(),
            ],
        ], 201);
    }
}
