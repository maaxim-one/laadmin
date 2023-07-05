@component('mail::message')
<p>На сайте что-то пошло не так!</p>

<p>
<strong>Ошибка:</strong>
{{ $e->getMessage() }}
</p>

@endcomponent
