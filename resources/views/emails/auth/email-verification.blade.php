@component('mail::message')
#Dziękujemy za rejestracje.

Aby aktywować konto kliknij w poniższy przycisk lub skopiuj link do przeglądarki

@component('mail::button', ['url' => $confirmation_link])
Aktywuj konto
@endcomponent

###### {{ $confirmation_link }}

Pozdrawiamy,<br/>
{{ config('app.name') }}
@endcomponent
