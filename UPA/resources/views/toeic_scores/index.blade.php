@extends('layouts.app')

@section('content')
<div class="container">
    <h2>PDF Score </h2>

    {{-- Hanya admin yang bisa unggah --}}
    @if(auth()->check() && auth()->user()->role_name === 'admin')
        <a href="{{ route('toeic-scores.create') }}" class="btn btn-success mb-3">Uploaded PDF</a>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>PDF</th>
                <th>Uploaded At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($scores as $score)
                <tr>
                    <td>{{ $score->id }}</td>
                    <td><a href="{{ asset('storage/' . $score->pdf) }}" target="_blank">Lihat PDF</a></td>
                    <td>{{ $score->created_at->format('d-m-Y H:i') }}</td>
                    <td>
                        @if(auth()->check() && auth()->user()->role_name === 'admin')
                            <a href="{{ route('toeic-scores.edit', $score->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('toeic-scores.destroy', $score->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        @else
                            <span class="text-muted">Tidak tersedia</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Belum ada PDF TOEIC tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
