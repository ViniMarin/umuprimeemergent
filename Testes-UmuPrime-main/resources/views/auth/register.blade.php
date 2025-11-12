@extends('layouts.auth')
@section('title','Criar conta - UmuPrime')

@section('content')
  <h2 class="auth-title text-center">Criar conta</h2>
  <p class="auth-sub text-center mb-4">
    Preencha seus dados para cadastrar um acesso.
  </p>

  <form method="POST" action="{{ route('register') }}" novalidate autocomplete="off">
    @csrf

    {{-- Nome --}}
    <label for="name" class="form-label d-none">Nome completo</label>
    <div class="input-group mb-3">
      <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
      <input
        id="name"
        type="text"
        name="name"
        class="form-control @error('name') is-invalid @enderror"
        placeholder="Seu nome completo"
        value="{{ old('name') }}"
        required
        autocomplete="name"
        inputmode="text"
      >
    </div>
    @error('name')
      <div class="invalid-feedback d-block mb-2">{{ $message }}</div>
    @enderror

    {{-- E-mail --}}
    <label for="email" class="form-label d-none">E-mail</label>
    <div class="input-group mb-3">
      <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
      <input
        id="email"
        type="email"
        name="email"
        class="form-control @error('email') is-invalid @enderror"
        placeholder="Seu e-mail"
        value="{{ old('email') }}"
        required
        autocomplete="email"
        inputmode="email"
      >
    </div>
    @error('email')
      <div class="invalid-feedback d-block mb-2">{{ $message }}</div>
    @enderror

    {{-- Senha --}}
    <label for="password" class="form-label d-none">Senha</label>
    <div class="input-group mb-3">
      <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
      <input
        id="password"
        type="password"
        name="password"
        class="form-control @error('password') is-invalid @enderror"
        placeholder="Crie uma senha (mín. 8 caracteres)"
        required
        autocomplete="new-password"
        minlength="8"
      >
      <button class="btn btn-outline-secondary" type="button" id="togglePass" aria-label="Mostrar/ocultar senha">
        <i class="fa-solid fa-eye-slash"></i>
      </button>
    </div>
    @error('password')
      <div class="invalid-feedback d-block mb-2">{{ $message }}</div>
    @enderror

    {{-- Confirmar Senha --}}
    <label for="password-confirm" class="form-label d-none">Confirmar senha</label>
    <div class="input-group mb-4">
      <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
      <input
        id="password-confirm"
        type="password"
        name="password_confirmation"
        class="form-control"
        placeholder="Confirme a senha"
        required
        autocomplete="new-password"
        minlength="8"
      >
      <button class="btn btn-outline-secondary" type="button" id="togglePass2" aria-label="Mostrar/ocultar confirmação">
        <i class="fa-solid fa-eye-slash"></i>
      </button>
    </div>

    <button type="submit" class="btn btn-primary w-100">
      <i class="fa-solid fa-user-plus me-2"></i>Criar conta
    </button>

    <div class="text-center mt-3">
      <a href="{{ route('login') }}" class="text-link">Já tem conta? Entrar</a>
    </div>
  </form>
@endsection

@push('scripts')
<script>
(function(){
  function wireToggle(btnId, inputId){
    const btn = document.getElementById(btnId);
    const inp = document.getElementById(inputId);
    if(!btn || !inp) return;
    btn.addEventListener('click', () => {
      const show = inp.type === 'password';
      inp.type = show ? 'text' : 'password';
      btn.innerHTML = show
        ? '<i class="fa-solid fa-eye"></i>'
        : '<i class="fa-solid fa-eye-slash"></i>';
    });
  }
  wireToggle('togglePass', 'password');
  wireToggle('togglePass2', 'password-confirm');
})();
</script>
@endpush
