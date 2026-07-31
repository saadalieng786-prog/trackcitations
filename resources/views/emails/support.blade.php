<x-mail::message>

# Hello,

We've got a support request from user <b>{{ $fromUserName }}</b>
<b>Subject:</b>
<x-mail::panel>
    {{ $supportSubject }}
</x-mail::panel>
<b>Message:</b>
<x-mail::panel>
    {{ $supportDescription }}
</x-mail::panel>

Thanks,
{{ config('app.name') }}
</x-mail::message>
