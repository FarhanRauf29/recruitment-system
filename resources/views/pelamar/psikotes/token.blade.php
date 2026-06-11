@extends('layouts.pelamar')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title mb-0">
                                <i class="fas fa-key me-2"></i> Masukkan Token Tes Psikotes
                            </h3>
                            <p class="small mb-0">Silakan masukkan token yang diberikan oleh admin.</p>
                        </div>
                    </div>

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('pelamar.psikotes.start') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="token" class="form-label">Token</label>
                            <input type="text" name="token" id="token" class="form-control"
                                placeholder="Masukkan token..." required>
                            <div class="form-text">Token terdiri dari 6 karakter alfanumerik</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-arrow-right me-1"></i> Mulai Tes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
