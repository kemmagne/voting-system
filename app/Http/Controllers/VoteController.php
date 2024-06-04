<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Candidate;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewVoteNotification;



class VoteController extends Controller
{
    
    public function store(Request $request)
    {
        // Validate vote data
        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'student_id' => 'required|exists:students,id' // Assuming students are registered
        ]);
    
        // Store the vote
        Vote::create([
            'candidate_id' => $request->candidate_id,
            'student_id' => $request->student_id
        ]);
    
        // Send email notifications
        // Logic for sending email to student and admins
    }


    public function show()
    {
        $posts = Post::with('candidates')->get();
        return view('vote', compact('posts'));
    }

    public function submit(Request $request)
    {
        $validatedData = $request->validate([
            'post_id.*' => 'required|exists:posts,id',
            'candidate_id.*' => 'required|exists:candidates,id',
        ]);

        foreach ($validatedData['post_id'] as $key => $postId) {
            $studentId = auth()->user()->id;
            $candidateId = $validatedData['candidate_id'][$key];

            Vote::create([
                'student_id' => $studentId,
                'candidate_id' => $candidateId,
            ]);

            $candidate = Candidate::find($candidateId);
            $post = $candidate->post;
            $admins = User::where('role', 'admin')->get();

            Mail::to($admins)->send(new NewVoteNotification($post, $candidate));
            Mail::to(auth()->user())->send(new NewVoteNotification($post, $candidate));
        }

        return redirect()->route('vote.show')->with('success', 'Votes submitted successfully!');
    }

}
