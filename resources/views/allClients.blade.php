@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Все клиенты') }}</div>
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Имя</th>
                                <th>Фамилия</th>
                                <th>Телефон</th>
                                <th>Приглашения</th>
                                <th><a href="/admin/add_client" class="btn btn-primary">Добавить</a></th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(is_array($clients))
                            @foreach ($clients as $client)
                                <tr>
                                    <td><?= $client['name']; ?></td>
                                    <td><?= $client['last_name']; ?></td>
                                    <td><?= $client['phone']; ?></td>
                                    <td><?= $client['welcome_amount']; ?></td>
                                    <td>
                                        <a href="/admin/welcome?id={{$client['id']}}" >{{ 'пригласить' }}</a>
                                        <a href="/admin/edit_client?id={{$client['id']}}" >{{ 'редактировать' }}</a>
                                        <a href="/admin/delete_client?id={{$client['id']}}" >{{ 'удалить' }}</a>
                                        <!-- <form method="post" action="/admin/message">
                                           <button type="submit"class="">отправить приглашение</button>
                                        </form> -->
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                   {{ $message }}
            </div>
        </div>
    </div>
</div>
@endsection
