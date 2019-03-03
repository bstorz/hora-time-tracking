@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Company</div>

                    <div class="card-body">
                        @foreach($time_entries as $user)
                            @foreach($user as $entry)
                                {{ $entry->user->name }}: {{ $entry->time_spent }}
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
