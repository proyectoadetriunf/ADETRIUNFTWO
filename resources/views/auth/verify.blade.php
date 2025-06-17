@extends('layouts.app')

@section('content')
<style>
    body {
        margin: 0;
        min-height: 100vh;
        background: linear-gradient(to bottom, #f39c12, #e74c3c, #e84393); /* Mismo degradado */
        font-family: 'Segoe UI', sans-serif;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Verifique su dirección de correo electrónico</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            Se ha enviado un nuevo enlace de verificación a su dirección de correo electrónico.
                        </div>
                    @endif

                    Antes de continuar, revise su correo electrónico para obtener un enlace de verificación.
                    Si no recibiste el correo electrónico,
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">haz clic aquí para solicitar otro</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
