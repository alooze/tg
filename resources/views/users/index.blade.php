@extends('templates.main')

@section('title', 'Подписчики')

@section('crumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('start') }}">Главная</a></li>
    <li class="breadcrumb-item active" aria-current="page">Подписчики</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="text-center">
  <h4><strong>Подписчики</strong></h4>
</div>

<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Username</th>
      <th scope="col">First name</th>
      <th scope="col">Last name</th>
      <th scope="col">ID чата</th>
      <th scope="col">Активен</th>
      <th scope="col">ID последнего сообщения</th>
      <th scope="col">Действие</th>
    </tr>
  </thead>

  <tbody>
  	@foreach ($users as $u)
    <tr>
      <th scope="row">{{ $u->id }}</th>
      <td><a href="{{ route('users.edit', ['user' => $u->id]) }}">{{ $u->username }}</td>
      <td>{{ $u->first_name }}</td>
      <td>{{ $u->last_name }}</td>
      <td>{{ $u->chat_id }}</td>
      <td>@if ($u->status == 1)
        <span class="badge badge-success">Да</span>
        @else
        <span class="badge badge-dunger">Нет</span>
        @endif
      </td>
      <td>{{ $u->last_post_id }}</td>
      <td>
      	<a class="btn btn-outline-primary btn-rounded" data-mdb-ripple-color="dark" href="{{ route('users.edit', ['user' => $u->id]) }}" role="button">Править</a>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection