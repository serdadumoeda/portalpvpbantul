@extends('layouts.app')

@section('content')
<div class="bg-primary py-5 text-white mb-5">
    <div class="container text-center">
        <h1 class="fw-bold">{{ $data->judul }}</h1>
        <p class="lead mb-0">Satpel PVP Bantul</p>
    </div>
</div>

<div class="container mb-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $data->judul }}</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            @if($data->gambar)
                <div class="text-center mb-5">
                    <img src="{{ asset($data->gambar) }}" class="img-fluid rounded shadow-lg" alt="{{ $data->judul }}" style="max-height: 500px;">
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <div class="content-body" style="font-size: 1.1rem; line-height: 1.8;">
                        {!! $data->konten !!}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
