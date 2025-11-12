@extends('layouts.auth')

@section('title', 'Recuperar Senha - UmuPrime Imóveis')

@push('styles')
<style>
  /* deixa o campo e o botão centralizados e com largura mais compacta */
  .field-compact{ max-width:420px; margin-inline:auto; }
</style>
@endpush

@section('content')
  <div class="text-center mb-3">
    <h1 class="auth-title mb-1">Esqueceu sua senha?</h1>
    <p class="auth-sub">Digite seu e-mail para receber o link de redefinição.</p>
  </div>

  @if (session('status'))
    <div class="alert alert-success bg-success bg-opacity-10 border-0 text-success fw-medium">
      <i class="fa-solid fa-circle-check me-2"></i>
      {{ session('status') }}
    </div>
  @endif

  <form method="POST" action="{{ route('password.email') }}" novalidate>
    @csrf

    <!-- Campo centralizado e sem label acima -->
    <div class="mb-3 field-compact">
      <div class="input-group">
        <span class="input-group-text">
          <i class="fa-solid fa-envelope"></i>
        </span>
        <input
          id="email"
          type="email"
          name="email"
          class="form-control @error('email') is-invalid @enderror"
          value="{{ old('email') }}"
          required
          autocomplete="email"
          autofocus
          placeholder="E-mail"
        >
      </div>
      @error('email')
        <div class="invalid-feedback d-block">{{ $message }}</div>
      @enderror
    </div>

    <div class="field-compact d-grid gap-2 mt-3">
      <button type="submit" class="btn btn-primary">
        <i class="fa-solid fa-paper-plane me-2"></i>
        Enviar link de redefinição
      </button>
    </div>

    <div class="text-center mt-2">
      <a href="{{ route('login') }}" class="text-link">
        <i class="fa-solid fa-arrow-left-long me-1"></i>
        Voltar para o login
      </a>
    </div>
  </form>
@endsection
