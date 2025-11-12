@extends('layouts.auth')
@section('title','Confirmar senha - UmuPrime')

@section('content')
  <h2 class="auth-title text-center">Confirmar senha</h2>
  <p class="auth-sub text-center mb-4">
    Por segurança, confirme sua senha para continuar.
  </p>

  <form method="POST" action="{{ route('password.confirm') }}" novalidate autocomplete="off">
    @csrf

    <label class="form-label" for="password">Senha</label>
    <div class="input-group mb-4">
      <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
      <input
        id="password"
        type="password"
        name="password"
        class="form-control @error('password') is-invalid @enderror"
        placeholder="Digite sua senha"
        required
        autocomplete="current-password"
        inputmode="text"
      >
      <button class="btn btn-outline-secondary" type="button" id="togglePass" aria-label="Mostrar/ocultar senha">
        <i class="fa-solid fa-eye-slash"></i>
      </button>
      @error('password')
        <div class="invalid-feedback d-block">{{ $message }}</div>
      @enderror
    </div>

    <button type="submit" class="btn btn-primary w-100">
      <i class="fa-solid fa-check me-2"></i>Confirmar
    </button>
  </form>

  <div class="text-center mt-3">
    @if (Route::has('password.request'))
      <a class="text-link" href="{{ route('password.request') }}">
        Esqueceu sua senha?
      </a>
    @endif
  </div>
@endsection

@push('scripts')
<script>
(function(){
  const t = document.getElementById('togglePass');
  const p = document.getElementById('password');
  if (t && p) {
    t.addEventListener('click', () => {
      const show = p.type === 'password';
      p.type = show ? 'text' : 'password';
      t.innerHTML = show
        ? '<i class="fa-solid fa-eye"></i>'
        : '<i class="fa-solid fa-eye-slash"></i>';
    });
  }
})();
</script>
@endpush
