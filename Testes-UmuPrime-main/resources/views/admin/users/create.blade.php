@extends('layouts.admin')

@section('title', 'Novo Usuário')
@section('page-title', 'Novo Usuário')

@section('content')
@if($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
  </div>
@endif

<div class="card-custom">
  <form method="POST" action="{{ route('admin.users.store') }}" autocomplete="off">
    @csrf

    <div class="mb-3">
      <label class="form-label">Nome</label>
      <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label">E-mail</label>
      <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Senha</label>
      <input type="password" name="password" class="form-control" required>
      <div class="form-text">Mínimo 8 caracteres, com letras e números.</div>
    </div>

    <div class="mb-3">
      <label class="form-label">Confirmar senha</label>
      <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    <div class="form-check form-switch mb-3">
      <input class="form-check-input" type="checkbox" name="is_admin" id="is_admin" value="1" {{ old('is_admin')?'checked':'' }}>
      <label class="form-check-label" for="is_admin">Administrador</label>
    </div>

    <div class="d-flex gap-2">
      <button class="btn btn-primary"><i class="fa fa-save me-2"></i>Salvar</button>
      <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
