@extends('layouts.auth')

@section('title', 'Login - UmuPrime Imóveis')

@section('content')
<form method="POST" action="{{ route('login') }}">
  @csrf

  <div class="text-center mb-2">
    <div class="auth-title">Login</div>
    <div class="auth-sub">Acesse o painel da UmuPrime</div>
  </div>

  {{-- E-mail --}}
  <div class="mb-3">
    <label for="email" class="form-label">E-mail</label>
    <div class="input-group">
      <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
      <input
        id="email"
        type="email"
        class="form-control @error('email') is-invalid @enderror"
        name="email"
        value="{{ old('email') }}"
        required
        autocomplete="email"
        autofocus
        placeholder="Digite seu e-mail">
      @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>

  {{-- Senha --}}
  <div class="mb-2">
    <label for="password" class="form-label">Senha</label>
    <div class="input-group">
      <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
      <input
        id="password"
        type="password"
        class="form-control @error('password') is-invalid @enderror"
        name="password"
        required
        autocomplete="current-password"
        placeholder="Senha">
      <button class="input-group-text" type="button" id="togglePass" aria-label="Mostrar/ocultar senha">
        <i class="fa-solid fa-eye-slash"></i>
      </button>
      @error('password')
        <div class="invalid-feedback d-block">{{ $message }}</div>
      @enderror
    </div>
  </div>

  {{-- Lembrar / Esqueci --}}
  <div class="d-flex align-items-center justify-content-between mb-3">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
      <label class="form-check-label" for="remember">Manter conectado</label>
    </div>

    @if (Route::has('password.request'))
      <a class="text-link" href="{{ route('password.request') }}">Esqueceu sua senha?</a>
    @endif
  </div>

  <button type="submit" class="btn btn-primary w-100">
    <i class="fa-solid fa-right-to-bracket me-1"></i> Entrar
  </button>
</form>
@endsection

@push('scripts')
<script>
  // Mostrar/ocultar senha
  (function(){
    const btn = document.getElementById('togglePass');
    const input = document.getElementById('password');
    if(!btn || !input) return;

    btn.addEventListener('click', () => {
      const show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      btn.innerHTML = show
        ? '<i class="fa-solid fa-eye"></i>'
        : '<i class="fa-solid fa-eye-slash"></i>';
    });
  })();
</script>
@endpush
