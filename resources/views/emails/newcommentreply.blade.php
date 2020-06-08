@component('mail::message')

## Your video has received a reply

@component('mail::button', ['url' => route('lesson.comment', ['course' => $comment->lesson->course->id, 'lesson' => $comment->lesson->id, 'comment_id' => $comment->comment->id ]) ])

See the  feedback

@endcomponent

@endcomponent
