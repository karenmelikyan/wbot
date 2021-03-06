@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Редактировать главное меню') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ '/admin/edit_menu' }}">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-6">
                                <textarea rows = "15"  id="menu"   class="form-control @error('email') is-invalid @enderror" name="menu" autofocus>
                                {{ $menu['text'] }}
                                </textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ 'Обновить' }}
                                </button>
                            </div>
                        </div>
                        <br>
                        <a href="/admin/">{{ $message }}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
