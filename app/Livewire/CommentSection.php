<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Music; // Assuming you have a music model
use App\Models\Comment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CommentSection extends Component
{
    public $musicId;
    public $newComment;
    // public $user;


    public function mount($musicId)
    {
        $this->musicId = $musicId;

    }

    public function render()
    {
        $music = music::findOrFail($this->musicId);
        $comments = $music->comments;
        return view('livewire.comment-section', compact('comments', 'music'));
    }

    public function addComment()
{
    $this->validate([
        'newComment' => 'required|max:255',
    ]);

    // Log a message before creating the comment
    Log::info('Adding a new comment...');

    $music = Music::findOrFail($this->musicId);

    $music->comments()->create([
        'user_id' =>  Auth::user()->id,
        'content' => $this->newComment,
    ]);

    $this->newComment = '';

    // Log a message after creating the comment
    Log::info('Comment added successfully.');
}

    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $comment->delete();
    }

    // public function editComment($commentId, $newContent)
    // {
    //     // Log received data
    //     Log::info('Received comment ID: ' . $commentId);
    //     Log::info('Received new content: ' . $newContent);

    //     $validator = Validator::make(['newContent' => $this->newContent], [
    //         'newContent' => 'required|max:255',
    //     ]);

    //     // Check if validation fails
    //     if ($validator->fails()) {
    //         // Handle validation failure
    //         // For example, you can throw an exception or return an error message
    //         throw new \Exception('Validation failed: ' . $validator->errors()->first('newContent'));
    //     }

    //     // Find the comment
    //     $comment = Comment::findOrFail($commentId);

    //     // Update the comment content
    //     $comment->update(['content' => $this->newContent]);

    //     // Clear the new content field after submission
    //     $this->newContent = '';
    // }
}
