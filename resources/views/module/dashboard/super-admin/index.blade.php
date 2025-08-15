@extends('layouts.dashbord')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h5 class="text-muted text-center">Selamat datang di Dashboard</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Statistics Cards -->
                @include('module.dashboard.super-admin.components.statistik')

                <!-- Charts -->
                @include('module.dashboard.super-admin.components.chart')
            </div>
        </section>
    </div>

    <!-- Modals -->
    @include('module.dashboard.super-admin.components.modals.dokter-aktif')
    @include('module.dashboard.super-admin.components.modals.pendapatan')

    <!-- JavaScript -->
    @include('module.dashboard.super-admin.components.javascript.chart')
@endsection
