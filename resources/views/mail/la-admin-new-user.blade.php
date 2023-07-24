@component('mail::message')

Здравствуйте! Для продолжения регистрации в LaAdminPanel перейдите по ссылке ниже.

@component('mail::button', ['url' => route('laadmin.register', ['token' => $token, 'email' => $email])])
LaAdminPanel
@endcomponent

<small>Если у вас возникли проблемы с нажатием кнопки «LaAdminPanel», скопируйте и вставьте приведенный ниже
URL-адрес в ваш веб-браузер:
<a href="{{ route('laadmin.register', ['token' => $token, 'email' => $email]) }}">
{{ route('laadmin.register', ['token' => $token, 'email' => $email]) }}
</a>
</small>
@endcomponent
