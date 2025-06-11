<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <title>Login - Sistema Contábil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" />

    <!-- Plugins -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" crossorigin="anonymous" />

    <!-- AdminLTE (ajuste o caminho se necessário) -->
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.css') }}">
</head>

<body class="login-page bg-body-secondary">



    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Sistema</b>Contábil</a>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Acesse sua conta</p>

                @if($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $erro)
                    <div>{{ $erro }}</div>
                    @endforeach
                </div>
                @endif

                {{-- Formulário --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required autofocus>
                        <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Senha" required id="passwordInput">
                        <div class="input-group-text">
                            <span class="bi bi-eye-slash toggle-password" style="cursor: pointer;" id="togglePassword"></span>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-8">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember"> Lembrar-me </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Entrar</button>
                            </div>
                        </div>
                    </div>
                </form>

                <p class="mb-1 mt-3"><a href="{{ route('password.request') }}">Esqueci minha senha</a></p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('adminlte/js/adminlte.js') }}"></script>
    <script src="{{ asset('adminlte/js/eyes.js') }}"></script>
</body>

</html>
