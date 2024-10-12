@extends('templates.main')

@section('title', 'Категории')

@section('crumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('start') }}">Главная</a></li>
    <li class="breadcrumb-item active" aria-current="page">Категории</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="text-center">
  <h4><strong>Категории</strong></h4>
  <a class="btn btn-primary mb-5" data-mdb-ripple-init="" href="{{ route('cats.create') }}" role="button" style="">Создать новую категорию</a>
</div>

<div class="note note-primary mb-3">
  <strong>Обратите внимание:</strong> категории в данном разделе могут меняться по вашему усмотрению, но в случае добавления новых категорий, необходимо внести соответствующие изменения в промпт для GPT в разделе Настроек. 
</div>

<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Название</th>
      <th scope="col">Описание</th>
      <th scope="col">Порядок вывода</th>
      <th scope="col">Активно</th>
      <th scope="col">Действия</th>
    </tr>
  </thead>
  <tbody>
  	@foreach ($cats as $c)
    <tr>
      <th scope="row">{{ $c->id }}</th>
      <td>{{ $c->title }}</td>
      <td>{{ $c->description }}</td>
      <td>{{ $c->position }}</td>
      <td>@if ($c->status == 1)
      	<span class="badge badge-success">Да</span>
      	@else
      	<span class="badge badge-dunger">Нет</span>
      	@endif
      </td>
      <td>
      	<a class="btn btn-outline-primary btn-rounded" data-mdb-ripple-color="dark" href="{{ route('cats.edit', $c) }}" role="button">Править</a>

      	<form method="POST" action="{{ route('cats.destroy', $c) }}">
            @csrf
            @method('DELETE')
            <a class="btn btn-outline-danger btn-rounded" data-mdb-ripple-color="dark" href="javascript:;" role="button" onclick="event.preventDefault(); this.closest('form').submit();">Удалить</a>
        </form>

      	
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection