@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Панель администратора</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Вы успешно вошли в систему!
    
                    <br>
                    <br>
                    <h6><a href="{{ url('/admin/edit_menu') }}">Редактировать главное меню</a></h6>
                    <h6><a href="{{ url('/admin/all_clients') }}">Клиентская база</a></h6>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
