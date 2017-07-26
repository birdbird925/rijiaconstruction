@if(count($errors) > 0)
    <ul class="error">
        @foreach($errors->all() as $error)
            <li>{{$error}}</li>
        @endforeach
    </ul>
@endif
@if (session('status'))
    <div class="success">
        {{ session('status') }}
    </div>
@endif
