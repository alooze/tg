@extends('templates.main')

@section('title', $title)

@section('crumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('start') }}">Главная</a></li>
    <li class="breadcrumb-item"><a href="{{ route('options.index') }}">Настройки</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="text-center">
  <h4><strong>{{ $title }}</strong></h4>

<form style1="width: 26rem;" method="POST" action="{{ $action }}">
  @csrf
  @if ($m == 'patch')
  @method('PATCH')
  @endif

  <div data-mdb-input-init class="form-outline mb-4">
    <input type="text" id="name" class="form-control" name="name" value="{{ old('name') ?? $o->name }}" />
    <label class="form-label" for="name">Ключ</label>
  </div>

  <div data-mdb-input-init class="form-outline mb-4">
    <!-- <input type="text" id="value" class="form-control" name="value" value="{{ old('value') ?? $o->value }}" />
    <label class="form-label" for="value">Значение</label> -->
    <textarea class="form-control" id="value" rows="4" name="value">{{ $o->value }}</textarea>
            <label class="form-label" for="value">Значение</label>
  </div>

  <!-- Submit button -->
  <button data-mdb-ripple-init type="button" class="btn btn-primary btn-block mb-4" onclick="event.preventDefault(); this.closest('form').submit();">Сохранить</button>
</form>

</div>
@endsection