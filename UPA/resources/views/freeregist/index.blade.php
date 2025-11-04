@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <div class="container mx-auto p-4">
        <!-- Menampilkan SweetAlert jika ada pesan -->
        @if (session('message'))
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    title: 'Info',
                    text: "{{ session('message') }}",
                    icon: 'info', // Sesuaikan icon dengan pesan
                    confirmButtonText: 'OK'
                });
            </script>
        @endif

        @if (session('success'))
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    icon: 'success', // Ikon sukses
                    confirmButtonText: 'OK'
                });
            </script>
        @endif

        @if (session('error'))
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    title: 'Kesalahan',
                    text: "{{ session('error') }}",
                    icon: 'error', // Ikon error
                    confirmButtonText: 'OK'
                });
            </script>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold text-center mb-6">Pendaftaran TOEIC</h2>

            <!-- Display Registration Status -->
            <h3 class="font-semibold text-lg mb-4">Detail Pendaftaran Anda</h3>
            <p><strong>NIM:</strong> {{ $registration->nim }}</p>
            <p><strong>Status:</strong> {{ $registration->status }}</p>
            <p><strong>Tanggal Pendaftaran:</strong> {{ $registration->registration_date->format('d-m-Y') }}</p>

            @if ($registration->ktp_path)
                <p><strong>KTP:</strong> <a href="javascript:void(0);"
                        onclick="modalAction('{{ route('freeRegist.showKtp', $registration->id) }}')"
                        class="text-blue-500">Lihat KTP</a></p>
            @endif

            <!-- Display if the user has a second registration -->
            @if ($registration->is_second_registration)
                <p><strong>Status Pendaftaran Kedua:</strong> Terdaftar</p>
            @else
                <p><strong>Status Pendaftaran Kedua:</strong> Belum Terdaftar</p>
            @endif

            <!-- Display certificate -->
            @if ($registration->certificate_path)
                <p><strong>Sertifikat:</strong> <a href="{{ Storage::url($registration->certificate_path) }}"
                        target="_blank">Lihat Sertifikat</a></p>
            @endif

            <!-- Action Buttons -->
            <div class="mt-4 flex space-x-4">
                <a href="{{ route('freeRegist.edit', $registration->id) }}"
                    class="bg-blue-500 text-white py-2 px-4 rounded w-full sm:w-auto">
                    Lengkapi Pendaftaran
                </a>
            {{-- @can('update', $registration)
                <a href="{{ route('freeRegist.edit', $registration->id) }}">Edit</a>
            @endcan --}}

            @if (!$registration->is_second_registration)
                <form id="secondRegistrationForm" action="{{ route('freeRegist.secondRegistration', $registration->id) }}"
                    method="POST">
                    @csrf
                    <button type="button" id="confirmBtn"
                        class="bg-blue-500 text-white py-2 px-4 rounded w-full sm:w-auto">
                        Daftar untuk Kedua Kalinya
                    </button>
                </form>
            @endif
        </div>
    </div>
    </div>

    <!-- Modal for KTP -->
    @include('partials.modal')
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Event listener untuk tombol konfirmasi
        document.getElementById('confirmBtn').addEventListener('click', function() {
            // Menampilkan SweetAlert2 untuk konfirmasi
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Setelah mendaftar kedua kalinya, status Anda akan diperbarui!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Daftar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika pengguna mengonfirmasi, kirimkan form
                    document.getElementById('secondRegistrationForm').submit();
                }
            });
        });

        // Function to load the modal content dynamically
        function modalAction(url = '') {
            $('#myModal').removeClass('hidden').addClass('flex'); // Menampilkan modal
            $('#ktpContent').load(url, function(response, status, xhr) {
                if (status == "success") {
                    var ktpUrl = $('#ktpContent').data('ktp-url'); // Mengambil URL
                    $('#ktpDownloadLink').attr('href', ktpUrl); // Mengatur link unduhan secara dinamis
                }
            });
        }

        // Function to close the modal
        function closeModal() {
            $('#myModal').removeClass('flex').addClass('hidden'); // Hide the modal
        }
    </script>
@endpush
