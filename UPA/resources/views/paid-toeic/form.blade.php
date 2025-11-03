@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4>Paid TOEIC Registration</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('paid-toeic.register') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="photo" class="form-label">Photo</label>
            <input type="file" name="photo" class="form-control" required>
            @error('photo') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="id_card" class="form-label">ID Card (KTP)</label>
            <input type="file" name="id_card" class="form-control" required>
            @error('id_card') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-success">Submit & Proceed to Payment</button>
    </form>
</div>
@endsection
