@extends('templates.main')

@section('title', 'Главная')

@section('content')
<div class="text-center">
<p>Место для вывода статистики</p>
</div>

<hr>

<div class="text-center">
  <h4><strong>Объявление всем подписчикам</strong></h4>

<form style1="width: 26rem;" method="POST" action="{{ route('send') }}">
  @csrf

	<div data-mdb-input-init class="form-outline mb-4">
    <textarea class="form-control" id="content" rows="4" name="content" >{{ old('content') }}</textarea>
    <label class="form-label" for="content">Текст рассылки</label>
  </div>

  <!-- Submit button -->
  <button data-mdb-ripple-init type="button" class="btn btn-primary btn-block mb-4" onclick="event.preventDefault(); this.closest('form').submit();">Начать</button>
</form>

</div>
@endsection