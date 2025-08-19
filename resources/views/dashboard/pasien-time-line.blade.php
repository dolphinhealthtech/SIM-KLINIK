@extends('layouts.dashbord')


@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h5 class="text-muted text-center">Time Line Pasien: {{ $datapasien->nama }}</h5>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="timeline">

                                        <div class="time-label">
                                            <span class="">as</span>
                                        </div>
                                        <div>
                                            <i class=""></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="fas fa-clock"></i> </span>
                                                <h3 class="timeline-header"></h3>
                                                <div class="timeline-body">
                                                    as
                                                </div>
                                            </div>
                                        </div>

                                    <div>
                                        <i class="fas fa-clock bg-gray"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

@endsection

