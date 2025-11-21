<x-mail::message>
# Introduction

{{$name}}
The body of your message.
{{$body}}

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
