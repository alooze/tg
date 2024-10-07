@extends('templates.main')

@section('title', 'Настройки')

@section('crumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('start') }}">Главная</a></li>
    <li class="breadcrumb-item active" aria-current="page">Настройки</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="text-center">
  <h4><strong>Настройки</strong></h4>
  <a class="btn btn-primary mb-5" data-mdb-ripple-init="" href="{{ route('options.create') }}" role="button" style="">Создать новую</a>
</div>

<div class="note note-primary mb-3">
  <strong>Обратите внимание:</strong> данный раздел предназначен для управления работой системы. Ключи настроек, как правило, жестко прописаны в коде, поэтому менять их не нужно. Если нет уверенности, что изменения будут сделаны в нужном месте, сохраните где-то значение настройки, которую меняете, и ее ключ; при необходимости вы сможете вернуть предыдущее состояние настройки.
</div>

<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Ключ</th>
      <th scope="col">Значение</th>
      <th scope="col">Действия</th>
    </tr>
  </thead>

  <tbody>
  	@foreach ($options as $o)
    <tr>
      <th scope="row">{{ $o->id }}</th>
      <td><a href="{{ route('options.edit', ['option' => $o->id]) }}">{{ $o->name }}</td>
      <td>
        <form method="POST" action="{{ route('options.update', $o) }}">
            @csrf
            @method('PATCH')
            <textarea class="form-control" id="value" rows="4" name="value">{{ $o->value }}</textarea>
            <!-- <label class="form-label" for="value">Значение</label> -->
            <a class="btn btn-outline-danger btn-rounded" data-mdb-ripple-color="dark" href="javascript:;" role="button" onclick="event.preventDefault(); this.closest('form').submit();">Сохранить</a>
        </form>
        
      </td>
      <td>
      	<a class="btn btn-outline-primary btn-rounded" data-mdb-ripple-color="dark" href="{{ route('options.edit', $o) }}" role="button">Править</a>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection