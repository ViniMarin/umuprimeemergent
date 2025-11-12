@extends('layouts.auth')
@section('title','Definir nova senha - UmuPrime')

@section('content')
  <h2 class="auth-title text-center">Definir nova senha</h2>
  <p class="auth-sub text-center mb-4">Crie uma nova senha para sua conta.</p>

  <form method="POST" action="{{ route('password.update') }}" novalidate>
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    {{-- E-mail --}}
    <label class="form-label" for="email">E-mail</label>
    <div class="input-group mb-3">
      <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
      <input id="email" type="email" name="email"
             class="form-control @error('email') is-invalid @enderror"
             value="{{ old('email', request('email')) }}" placeholder="seuemail@exemplo.com" required>
      @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    {{-- Nova senha --}}
    <label class="form-label" for="password">Nova senha</label>
    <div class="input-group mb-3">
      <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
      <input id="password" type="password" name="password"
             class="form-control @error('password') is-invalid @enderror"
             placeholder="Digite a nova senha" required>
      <button class="btn btn-outline-secondary" type="button" id="togglePass">
        <i class="fa-solid fa-eye-slash"></i>
      </button>
      @error('password')
        <div class="invalid-feedback d-block">{{ $message }}</div>
      @enderror
    </div>

    {{-- Confirmar nova senha --}}
    <label class="form-label" for="password_confirmation">Confirmar nova senha</label>
    <div class="input-group mb-4">
      <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
      <input id="password_confirmation" type="password" name="password_confirmation"
             class="form-control" placeholder="Repita a nova senha" required>
      <button class="btn btn-outline-secondary" type="button" id="togglePass2">
        <i class="fa-solid fa-eye-slash"></i>
      </button>
    </div>

    <button type="submit" class="btn btn-primary w-100">
      <i class="fa-solid fa-rotate me-2"></i>Salvar nova senha
    </button>
  </form>
@endsection

@push('scripts')
<script>
(function(){
  const t1 = document.getElementById('togglePass');
  const p1 = document.getElementById('password');
  const t2 = document.getElementById('togglePass2');
  const p2 = document.getElementById('password_confirmation');
  function swap(btn, input){
    const on = input.type === 'password';
    input.type = on ? 'text' : 'password';
    btn.innerHTML = on ? '<i class="fa-solid fa-eye"></i>' : '<i class="fa-solid fa-eye-slash"></i>';
  }
  t1 && t1.addEventListener('click', () => swap(t1,p1));
  t2 && t2.addEventListener('click', () => swap(t2,p2));
})();
</script>
@endpush
