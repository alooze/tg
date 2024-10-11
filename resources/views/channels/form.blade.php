@extends('templates.main')

@section('title', $title)

@section('crumbs')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('start') }}">Главная</a></li>
    <li class="breadcrumb-item"><a href="{{ route('channels.index') }}">Каналы и чаты</a></li>
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
    <label class="form-label" for="title">Название</label>
  </div>

  <div data-mdb-input-init class="form-outline mb-4">
    <div class="btn-group">
      <input type="radio" class="btn-check" name="channel_type" id="chat" value="chat" autocomplete="off" {{ $c->channel_type == 'chat' || $c->channel_type == 'supergroup' ? 'checked' : '' }}/>
      <label class="btn btn-secondary" for="chat" data-mdb-ripple-init>Чат</label>

      <input type="radio" class="btn-check" name="channel_type" id="channel" value="channel" autocomplete="off" {{ $c->channel_type == 'channel' ? 'checked' : '' }}/>
      <label class="btn btn-secondary" for="channel" data-mdb-ripple-init>Канал</label>
    </div>
  </div>

  <div data-mdb-input-init class="form-outline mb-4">
    <input type="text" id="channel_id" class="form-control" name="channel_id" value="{{ old('channel_id') ?? $c->channel_id }}" />
    <label class="form-label" for="channel_id">Telegram ID</label>
  </div>

  <div data-mdb-input-init class="form-outline mb-4">
    <input type="text" id="username" class="form-control" name="username" value="{{ old('username') ?? $c->username }}" />
    <label class="form-label" for="username">Telegram username</label>
  </div>

  <div data-mdb-input-init class="form-outline mb-4">
    <input type="text" id="access_hash" class="form-control" name="access_hash" value="{{ old('access_hash') ?? $c->access_hash }}" />
    <label class="form-label" for="access_hash">Telegram access_hash</label>
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
      Активный канал (использовать для получения объявлений)
    </label>
  </div>

  <!-- Submit button -->
  <button data-mdb-ripple-init type="button" class="btn btn-primary btn-block mb-4" onclick="event.preventDefault(); this.closest('form').submit();">Сохранить</button>
</form>

</div>
@endsection