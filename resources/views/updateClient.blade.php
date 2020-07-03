@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Обновить данные клиента') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ '/admin/edit_client' }}">
                        @csrf

                        <input type="hidden" value="{{ $client['id'] }}" name="id">
                        
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Имя') }}</label>

                            <div class="col-md-6">
                                <input type="text" value="{{ $client['name'] }}" class="form-control @error('email') is-invalid @enderror" name="name" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="last_name" class="col-md-4 col-form-label text-md-right">{{ __('Фамилия') }}</label>

                            <div class="col-md-6">
                                <input type="text" value="{{ $client['last_name'] }}" class="form-control @error('password') is-invalid @enderror" name="last_name" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('Телефон') }}</label>

                            <div class="col-md-6">
                                <input type="text" value="{{ $client['phone'] }}"class="form-control @error('password') is-invalid @enderror" name="phone" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Обновить') }}
                                </button>
                            </div>
                        </div>

                        <a href="/admin/all_clients">{{ $message }}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
