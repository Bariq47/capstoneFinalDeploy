@extends('layouts.app')

@section('title', 'Manajemen Akun')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Manajemen Akun</h4>
            <div class="text-muted">
                Kelola akun pengguna sistem
            </div>
        </div>

        <a href="{{ route('akun.create') }}" class="btn btn-primary px-4">
            <i class="bi bi-plus-lg me-1"></i> Tambah Akun
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user['username'] }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $user['role'] }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('akun.edit', $user['id']) }}" class="btn btn-warning">
                                        Edit
                                    </a>

                                    <form action="{{ route('akun.destroy', $user['id']) }}" method="POST"
                                        class="form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-delete"
                                            data-text="Data akun ini akan dihapus permanen">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Belum ada data akun
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
