@extends('layouts.auth')
@section('title','Verifique seu e-mail - UmuPrime')

@section('content')
  <h2 class="auth-title text-center">Verifique seu e-mail</h2>
  <p class="auth-sub text-center">
    Enviamos um link de verificação para <strong>{{ Auth::user()->email }}</strong>.
    Caso não tenha recebido, você pode solicitar novamente.
  </p>

  @if (session('status') == 'verification-link-sent')
    <div class="alert alert-success py-2 px-3 mb-4" role="alert" style="background:#123820;border:none;color:#b7ffb7;">
      Novo link de verificação foi enviado para seu e-mail.
    </div>
  @endif

  <div class="d-grid gap-2">
    <form method="POST" action="{{ route('verification.send') }}">
      @csrf
      <button type="submit" class="btn btn-primary w-100">
        <i class="fa-solid fa-paper-plane me-2"></i>Reenviar link de verificação
      </button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="btn btn-outline-secondary w-100 mt-2">
        <i class="fa-solid fa-right-from-bracket me-2"></i>Sair
      </button>
    </form>
  </div>
@endsection
