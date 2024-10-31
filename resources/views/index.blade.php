@extends('layouts.master')
@section('title') @lang('translation.Dashboard') @endsection
@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Minible @endslot
        @slot('title') Panel de informacion @endslot
    @endcomponent

    <div class="row">
        <!-- Primer recuadro: Total ingresado -->
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="float-end mt-2">
                        <div id="total-revenue-chart" data-colors='["--bs-primary"]'></div>
                    </div>
                    <div>
                        <h4 class="mb-1 mt-1"><span data-plugin="counterup">{{ number_format($totalIngresado, 2) }}</span> Bs.</h4>
                        <p class="text-muted mb-0">Total Ingresado</p>
                    </div>
                <!--    <p class="text-muted mt-3 mb-0">
                        <span class="text-success me-1"><i class="mdi mdi-arrow-up-bold me-1"></i>2.65%</span> since last week
                    </p>  -->
                </div>
            </div>
        </div> <!-- end col-->

        <!-- Segundo recuadro: Total no pagado -->
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="float-end mt-2">
                        <div id="orders-chart" data-colors='["--bs-success"]'></div>
                    </div>
                    <div>
                        <h4 class="mb-1 mt-1"><span data-plugin="counterup">{{ number_format($totalNoPagado, 2) }}</span> Bs.</h4>
                        <p class="text-muted mb-0">Pendiente por Pagar</p>
                    </div>
                 <!--   <p class="text-muted mt-3 mb-0"><span class="text-danger me-1"><i class="mdi mdi-arrow-down-bold me-1"></i>0.82%</span> since last week
                    </p>  -->
                </div>
            </div>
        </div> <!-- end col-->

        <!-- Tercer recuadro: Total de propiedades activas -->
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="float-end mt-2">
                        <div id="customers-chart" data-colors='["--bs-primary"]'></div>
                    </div>
                    <div>
                        <!-- Mostrar el total de propiedades activas -->
                        <h4 class="mb-1 mt-1"><span data-plugin="counterup">{{ $totalPropiedadesActivas }}</span></h4>
                        <p class="text-muted mb-0">Propiedades Activas</p>
                    </div>
               <!--     <p class="text-muted mt-3 mb-0"><span class="text-success me-1"><i class="mdi mdi-arrow-up-bold me-1"></i>5.15%</span> since last week
                    </p> -->
                </div>
            </div>
        </div> <!-- end col-->
    </div> <!-- end row-->

    
    
    
    <!-- El resto del contenido permanece igual -->
@endsection

@section('script')
    <!-- Scripts necesarios para los gráficos o funcionalidades -->
    <script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/dashboard.init.js') }}"></script>
@endsection
