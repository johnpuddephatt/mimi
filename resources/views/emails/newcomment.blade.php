@component('mail::message')

## {{ $comment->user->first_name }} has replied to {{ $comment->lesson->title }}

@component('mail::button', ['url' => route('lesson.comment', ['course' => $comment->lesson->course->id, 'lesson' => $comment->lesson->id, 'comment_id' => $comment->id ]) ])

Visit this reply

@endcomponent

@endcomponent
