@extends('templates.main')

@section('title', 'Каналы и чаты')

@section('crumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('start') }}">Главная</a></li>
    <li class="breadcrumb-item active" aria-current="page">Каналы и чаты</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="text-center">
  <h4><strong>Каналы и чаты</strong></h4>
  <a class="btn btn-primary mb-5" data-mdb-ripple-init="" href="{{ route('channels.create') }}" role="button" style="">Добавить</a>
</div>

<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Название</th>
      <th scope="col">Адрес</th>
      <th scope="col">Telegram ID</th>
      <th scope="col">Тип</th>
      <th scope="col">Активен</th>
      <th scope="col">ID последнего сообщения</th>
      <th scope="col">Действие</th>
    </tr>
  </thead>

  <tbody>
  	@foreach ($channels as $c)
    <tr>
      <th scope="row">{{ $c->id }}</th>
      <td><a href="{{ route('channels.edit', ['channel' => $c->id]) }}">{{ $c->title }}</td>
      <td><a href="{{ $c->link }}" target="_blank">{{ $c->link }}</a></td>
      <td>{{ $c->channel_id }}</td>
      <td>{{ $c->channel_type }}</td>
      <td>@if ($c->status == 1)
        <span class="badge badge-success">Да</span>
        @else
        <span class="badge badge-dunger">Нет</span>
        @endif
      </td>
      <td>{{ $c->last_message_id }}</td>
      <td>
      	<a class="btn btn-outline-primary btn-rounded" data-mdb-ripple-color="dark" href="{{ route('channels.edit', ['channel' => $c->id]) }}" role="button">Править</a>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection