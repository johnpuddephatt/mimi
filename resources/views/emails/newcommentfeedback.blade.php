@component('mail::message')

## You’ve received feedback

@component('mail::button', ['url' => route('lesson.comment', ['course' => $comment->lesson->course->id, 'lesson' => $comment->lesson->id, 'comment_id' => $comment->comment->id ]) ])

See your feedback

@endcomponent

@endcomponent
