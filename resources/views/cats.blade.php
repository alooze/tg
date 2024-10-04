@extends('templates.main')

@section('title', 'Главная')

@section('nav')
<!-- Navbar -->
  <!-- <nav class="navbar navbar-expand-lg bg-body"> -->
  <nav class="navbar navbar-expand-lg navbar-light bg-body-tertiary">
    
    <div class="container-fluid">
      <button
        data-mdb-collapse-init
        class="navbar-toggler"
        type="button"
        data-mdb-target="#navbar01"
        aria-controls="navbar01"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <i class="fas fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbar01">
        
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        
      <li class="nav-item">
        <a class="nav-link" aria-current="page" href="/">Главная</a>
        </li>
      <li class="nav-item active">
        <a class="nav-link" href="{{ route('cat.list') }}">Категории</a>
        </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('channel.list') }}">Каналы и чаты</a>
        </li>
      </ul>
      </div>
     
    </div>
  </nav>
  <!-- Navbar -->
@endsection

@section('content')
{{ dump($cats) }}
@endsection