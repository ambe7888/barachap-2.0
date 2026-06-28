@foreach($data->messages as $message)
    <x-chat::provider.message :$message :$data />
@endforeach
