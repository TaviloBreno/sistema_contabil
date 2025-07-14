@extends('app')

@section('title', 'Empresas')


@section('content')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <script>
            // Aguarda 10 segundos e remove o elemento do DOM
            setTimeout(() => {
                const alert = document.getElementById('success-alert');
                if (alert) {
                    // Primeiro, aplica a transição de fade
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    alert.style.opacity = '0';

                    // Depois de 300ms (duração da animação do Bootstrap), remove do DOM
                    setTimeout(() => alert.remove(), 300);
                }
            }, 10000);
        </script>
    @endif

    <!--begin::App Content Header-->
    <div class="app-content-header">

        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6"><h3 class="mb-0">{{ $title }}</h3></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content Header-->
    <!--begin::App Content-->
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <!--begin::Col-->
                <div class="col-lg-3 col-6">
                    <!--begin::Small Box Widget 1-->
                    <div class="small-box bg-primary text-white">
                        <!-- Conteúdo principal -->
                        <div class="inner">
                            <h3>{{ $numberOfCompaniesRegistered }}</h3>
                            <p>Empresas Cadastradas</p>
                        </div>

                        <!-- Ícone SVG -->
                        <div class="icon">
                            <svg
                                fill="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                                aria-hidden="true"
                                style="width: 60px; height: 60px; opacity: 0.4; position: absolute; top: 10px; right: 10px;"
                            >
                                <path
                                    d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"
                                />
                            </svg>
                        </div>

                        <!-- Rodapé do box -->
                        <a href="{{ route('empresas.index') }}" class="small-box-footer text-white text-decoration-none">
                            Mais informações <i class="bi bi-arrow-right-circle"></i>
                        </a>
                    </div>

                    <!--end::Small Box Widget 1-->
                </div>
                <!--end::Col-->
                <div class="col-lg-3 col-6">
                    <!--begin::Small Box Widget 2-->
                    <div class="small-box text-bg-success">
                        <div class="inner">
                            <h3>{{ $totalObligations }}</h3>
                            <p>Obrigações</p>
                        </div>
                        <svg
                            class="small-box-icon"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                            aria-hidden="true"
                        >
                            <path
                                d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 013 19.875v-6.75z"
                            ></path>
                        </svg>
                        <a
                            href="#"
                            class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                        >
                            Mais informações <i class="bi bi-arrow-right-circle"></i>
                        </a>
                    </div>
                    <!--end::Small Box Widget 2-->
                </div>
                <!--end::Col-->
                <div class="col-lg-3 col-6">
                    <!--begin::Small Box Widget 3-->
                    <div class="small-box text-bg-warning">
                        <div class="inner">
                            <h3>{{ $totalRegistredUsers }}</h3>
                            <p>Usuários Cadastrados</p>
                        </div>
                        <svg
                            class="small-box-icon"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                            aria-hidden="true"
                        >
                            <path
                                d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z"
                            ></path>
                        </svg>
                        <a
                            href="#"
                            class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover"
                        >
                            Mais informações <i class="bi bi-arrow-right-circle"></i>
                        </a>
                    </div>
                    <!--end::Small Box Widget 3-->
                </div>
                <!--end::Col-->
                <div class="col-lg-3 col-6">
                    <!--begin::Small Box Widget 4-->
                    <div class="small-box text-bg-danger">
                        <div class="inner">
                            <h3>65</h3>
                            <p>Unique Visitors</p>
                        </div>
                        <svg
                            class="small-box-icon"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                            aria-hidden="true"
                        >
                            <path
                                clip-rule="evenodd"
                                fill-rule="evenodd"
                                d="M2.25 13.5a8.25 8.25 0 018.25-8.25.75.75 0 01.75.75v6.75H18a.75.75 0 01.75.75 8.25 8.25 0 01-16.5 0z"
                            ></path>
                            <path
                                clip-rule="evenodd"
                                fill-rule="evenodd"
                                d="M12.75 3a.75.75 0 01.75-.75 8.25 8.25 0 018.25 8.25.75.75 0 01-.75.75h-7.5a.75.75 0 01-.75-.75V3z"
                            ></path>
                        </svg>
                        <a
                            href="#"
                            class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                        >
                            More info <i class="bi bi-link-45deg"></i>
                        </a>
                    </div>
                    <!--end::Small Box Widget 4-->
                </div>
                <!--end::Col-->
                <div class="col-lg-3 col-6">
                    <!--begin::Small Box Widget - Notas Fiscais-->
                    <div class="small-box text-bg-info">
                        <div class="inner">
                            <h3>{{ $totalNotasFiscais }}</h3>
                            <p>Notas Fiscais</p>
                        </div>
                        <svg
                            class="small-box-icon"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                            aria-hidden="true"
                        >
                            <path
                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"
                            ></path>
                        </svg>
                        <a
                            href="{{ route('notas-fiscais.index') }}"
                            class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                        >
                            Mais informações <i class="bi bi-arrow-right-circle"></i>
                        </a>
                    </div>
                    <!--end::Small Box Widget - Notas Fiscais-->
                </div>
                <!--end::Col-->
            </div>

            <!--begin::Row - Estatísticas Notas Fiscais-->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-file-invoice"></i> Estatísticas de Notas Fiscais - {{ now()->format('m/Y') }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3 col-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-primary"><i class="fas fa-file-invoice"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Notas do Mês</span>
                                            <span class="info-box-number">{{ $notasFiscaisDoMes }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Autorizadas</span>
                                            <span class="info-box-number">{{ $notasAutorizadas }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-warning"><i class="fas fa-edit"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Rascunhos</span>
                                            <span class="info-box-number">{{ $notasRascunho }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info"><i class="fas fa-dollar-sign"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Valor Total</span>
                                            <span class="info-box-number">R$ {{ number_format($valorTotalDoMes, 2, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-6">
                                    <a href="{{ route('notas-fiscais.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Nova Nota Fiscal
                                    </a>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="{{ route('notas-fiscais.index') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-list"></i> Ver Todas as Notas
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Row - Estatísticas Notas Fiscais-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->
@endsection
