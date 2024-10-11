@extends('templates.main')

@section('title', $title)

@section('crumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('start') }}">Главная</a></li>
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Подписчики</a></li>
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
    <input type="text" id="username" class="form-control" name="username" value="{{ old('title') ?? $u->username }}" disabled />
    <label class="form-label" for="username">Username</label>
  </div>

  <!-- Checkbox -->
  <div class="form-check d-flex justify-content-center mb-4">
    <input
      class="form-check-input me-2"
      type="checkbox"
      value="1"
      name="status"
      id="status"
      @if ($u->status == 1) 
      checked
      @endif
    />
    <label class="form-check-label" for="status">
      Активен (получает объявления)
    </label>
  </div>

  <!-- Submit button -->
  <button data-mdb-ripple-init type="button" class="btn btn-primary btn-block mb-4" onclick="event.preventDefault(); this.closest('form').submit();">Сохранить</button>
</form>

</div>
@endsection