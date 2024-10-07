@extends('templates.main')

@section('title', $title)

@section('crumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('start') }}">Главная</a></li>
    <li class="breadcrumb-item"><a href="{{ route('cats.index') }}">Каналы и чаты</a></li>
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
    <input type="text" id="link" class="form-control" name="link" value="{{ old('link') ?? $c->link }}" />
    <label class="form-label" for="link">Ссылка</label>
  </div>

  <!-- Submit button -->
  <button data-mdb-ripple-init type="button" class="btn btn-primary btn-block mb-4" onclick="event.preventDefault(); this.closest('form').submit();">Получить данные</button>
</form>

</div>
@endsection