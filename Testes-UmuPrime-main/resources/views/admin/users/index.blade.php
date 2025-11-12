@extends('layouts.admin')

@section('title', 'Usuários')
@section('page-title', 'Usuários')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Gerenciar usuários</h5>
  <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
    <i class="fa fa-user-plus me-2"></i>Novo usuário
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
  <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="table-card">
  <div class="card-header">Lista</div>
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr>
          <th>#</th>
          <th>Nome</th>
          <th>E-mail</th>
          <th>Perfil</th>
          <th style="width:160px">Ações</th>
        </tr>
      </thead>
      <tbody>
      @forelse($users as $u)
        <tr>
          <td>{{ $u->id }}</td>
          <td>{{ $u->name }}</td>
          <td>{{ $u->email }}</td>
          <td>
            @if($u->is_admin)
              <span class="badge bg-warning text-dark">Administrador</span>
            @else
              <span class="badge bg-secondary">Usuário</span>
            @endif
          </td>
          <td>
            <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-primary">Editar</a>
            <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Excluir este usuário?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Excluir</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="5" class="text-center text-muted py-4">Nenhum usuário cadastrado.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">
  {{ $users->links() }}
</div>
@endsection
