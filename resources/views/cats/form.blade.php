@extends('templates.main')

@section('title', $title)

@section('crumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('start') }}">Главная</a></li>
    <li class="breadcrumb-item"><a href="{{ route('cats.index') }}">Категории</a></li>
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
    <input type="text" id="title" class="form-control" name="title" value="{{ old('title') ?? $c->title }}" />
    <label class="form-label" for="title">Название (на кнопке)</label>
  </div>

  <div data-mdb-input-init class="form-outline mb-4">
    <input type="text" id="caption" class="form-control" name="caption" value="{{ old('caption') ?? $c->caption }}" />
    <label class="form-label" for="caption">Заголовок (пока не используется)</label>
  </div>

  <div data-mdb-input-init class="form-outline mb-4">
    <input type="text" id="position" class="form-control" name="position" value="{{ old('position') ?? $c->position }}" />
    <label class="form-label" for="position">Порядок вывода</label>
  </div>

  <div data-mdb-input-init class="form-outline mb-4">
    <textarea class="form-control" id="description" rows="4" name="description">{{ old('description') ?? $c->description }}</textarea>
    <label class="form-label" for="description">Описание (будет показано в боте при выборе категории)</label>
  </div>

  <div data-mdb-input-init class="form-outline mb-4">
    <textarea class="form-control" id="keywords" rows="4" name="keywords" bplaceholder="web,сайт,дизайн,верстка">{{ old('keywords') ?? $c->keywords }}</textarea>
    <label class="form-label" for="keywords">Ключевые слова (используются для размещения объявления в этой категории) через запятую</label>
  </div>

  <!-- Checkbox -->
  <div class="form-check d-flex justify-content-center mb-4">
    <input
      class="form-check-input me-2"
      type="checkbox"
      value="1"
      name="status"
      id="status"
      @if ($c->status == 1) 
      checked
      @endif
    />
    <label class="form-check-label" for="status">
      Активная категория (использовать в боте)
    </label>
  </div>

  <!-- Submit button -->
  <button data-mdb-ripple-init type="button" class="btn btn-primary btn-block mb-4" onclick="event.preventDefault(); this.closest('form').submit();">Сохранить</button>
</form>

</div>
@endsection