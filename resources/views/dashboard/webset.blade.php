@extends('layouts.dashbord')


@section('content')
<div class="content-wrapper">
    <section class="content mt-3">
        <div class="card">
            <div class="card-header">
              <h3 class="card-title">Projects Detail</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-4 order-1 order-md-1">
                        <div class="col-md-12">
                            <div class="card card-primary card-outline">
                                <div class="card-header d-flex justify-content-center align-items-center">
                                    <h3 class="card-title">App Seting</h3>
                                </div>
                                <form action="{{ route('web.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <div class="text-center">
                                            <label for="profileImageInput">
                                                <img id="profileImage" class="profile-user-img img-fluid"
                                                src="{{ asset('setting/' . ($setting->profile_image ?? 'default.jpg')) }}"
                                                alt="User profile picture"
                                                style="width: 150px; height: 150px; cursor: pointer;">

                                            </label>
                                            <input type="file" id="profileImageInput" name="profile_image" accept="image/*" class="d-none">
                                        </div>

                                        <div class="form-group row mt-2">
                                            <label for="nama" class="col-sm-3 col-form-label">Nama Web:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="nama" name="nama" value="{{ $setting->nama ?? '' }}" placeholder="Masukkan Nama">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="alamat" class="col-sm-3 col-form-label">Alamat:</label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" id="alamat" name="alamat" placeholder="Masukkan Alamat" rows="1">{{ $setting->alamat ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row justify-content-center  mb-8">
                                            <button type="submit" class="btn btn-sm btn-primary w-100">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-8 order-2 order-md-2">
                        <div class="row">
                            <div class="col-12">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                <span class="info-box-number text-center text-muted mb-0">
                                        <div class="time-container">
                                            <!-- Time will be displayed here -->
                                        </div>
                                    </span>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="row col-md-6">
                                <!-- SATUSEHAT Form -->
                                <div class="col-md-12">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header d-flex justify-content-center align-items-center">
                                            <h3 class="card-title">Satu Sehat</h3>
                                        </div>
                                        <form action="{{ route('web.update.satusehat') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="card-body">
                                                @foreach ($set_Sehat as $setsatusehat)
                                                    <div class="row">
                                                        @foreach ([
                                                            'org_id' => 'ID',
                                                            'client_id' => 'Client ID',
                                                            'client_secret' => 'Client Secret',
                                                            'SATUSEHAT_BASE_URL' => 'SATUSEHAT BASE URL'
                                                        ] as $name => $label)
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="{{ $name }}">{{ $label }}</label>
                                                                    <input type="text" value="{{ $setsatusehat->$name }}" name="{{ $name }}" class="form-control" id="{{ $name }}" placeholder="Enter {{ $label }}" required>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="row col-md-6">
                                <!-- BPJS Form -->
                                <div class="col-md-12">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header d-flex justify-content-center align-items-center">
                                            <h3 class="card-title">BPJS</h3>
                                        </div>
                                        <form action="{{ route('web.update.bpjs') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="card-body">
                                                @foreach ($set_bpjs as $setbpjs)
                                                    <div class="row">
                                                        @foreach ([
                                                            'CONSID' => 'CONSID',
                                                            'KPFK' => 'Kode Faskes',
                                                            'USERNAME' => 'Username',
                                                            'PASSWORD' => 'Password',
                                                            'SCREET_KEY' => 'Secret Key',
                                                            'USER_KEY' => 'User Key',
                                                            'SERVICE_ANTREAN' => 'Service Antrean',
                                                            'SERVICE' => 'Service',
                                                            'APP_CODE' => 'App Code',
                                                            'BASE_URL' => 'Base URL'
                                                        ] as $name => $label)
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="{{ $name }}">{{ $label }}</label>
                                                                    <input type="text" value="{{ $setbpjs->$name }}" name="{{ $name }}" class="form-control" id="{{ $name }}" placeholder="Enter {{ $label }}" required>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
    document.getElementById('profileImageInput').addEventListener('change', function(event) {
        let reader = new FileReader();
        reader.onload = function() {
            document.getElementById('profileImage').src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    });
</script>
<script>
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { hour12: false });
        const dateString = now.toLocaleDateString('id-ID', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
        });
        document.querySelector('.time-container').innerHTML = timeString + ' <span>' + dateString + '</span>';
    }
    setInterval(updateTime, 1000);
    updateTime();  // Initialize immediately
</script>
@endsection
