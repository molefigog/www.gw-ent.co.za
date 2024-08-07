<div class="container mt-4">
    <h5>{{ $comments->count() }} Comments</h5>

    @foreach ($comments as $comment)
        <div wire:key="{{ $comment->id }}" class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="me-3">
                        {{-- Uncomment and style the profile picture if needed --}}
                        {{-- <img style="width: 40px; height: 40px;" src="{{ asset('storage/' . $comment->user->avatar) }}" alt="" class="rounded-circle"/> --}}
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h6 class="mb-0">{{ $comment->user->name ?? 'Anonymous' }}</h6>
                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                            @if (Auth::user() && $comment->user_id == Auth::user()->id)
                                <div>
                                    {{-- Uncomment and style the edit button if needed --}}
                                    {{-- <button class="btn btn-outline-primary btn-sm me-2" wire:click="editComment({{ $comment->id }}, $event.target.value)">
                                        <i class="bi bi-pencil"></i>
                                    </button> --}}
                                    <button class="btn btn-outline-danger btn-sm" wire:click="deleteComment({{ $comment->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <p class="mb-0">{{ $comment->content }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @if (Auth::user())
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="me-3">
                        <img style="width: 40px; height: 40px;" src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="" class="rounded-circle" />
                    </div>
                    <div class="flex-grow-1">
                        <form wire:submit.prevent="addComment">
                            <div class="mb-3">
                                <textarea wire:model="newComment" id="comment-text-area" class="form-control" rows="4" placeholder="Add a comment"></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-sm">Comment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center">Login to comment</button>
    @endif
</div>
