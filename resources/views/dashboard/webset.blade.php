@extends('layouts.dashbord')


@section('content')
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content mt-3">
        <div class="card">
            <div class="card-header">
              <h3 class="card-title">Projects Detail</h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-1">
                    <form action="{{ route('web.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="text-center">
                            <label for="profileImageInput">
                                <img id="profileImage" class="profile-user-img img-fluid"
                                src="{{ asset('setting/' . ($setting->profile_image ?? 'default.jpg')) }}"
                                alt="User profile picture"
                                style="width: 150px; height: 150px; cursor: pointer;">

                            </label>
                            <input type="file" id="profileImageInput" name="profile_image" accept="image/*" class="d-none">
                        </div>

                        <div class="form-group row">
                            <label for="nama" class="col-sm-3 col-form-label">Nama Web:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nama" name="nama" value="{{ $setting->nama ?? '' }}" placeholder="Masukkan Nama">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="alamat" class="col-sm-3 col-form-label">Alamat:</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" id="alamat" name="alamat" placeholder="Masukkan Alamat" rows="3">{{ $setting->alamat ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="row justify-content-center mt-5 mb-8">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-sm btn-primary w-100">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-12 col-md-12 col-lg-8 order-2 order-md-2">
                  <div class="row">
                    <div class="col-12 col-sm-4">
                      <div class="info-box bg-light">
                        <div class="info-box-content">
                          <span class="info-box-text text-center text-muted">Estimated budget</span>
                          <span class="info-box-number text-center text-muted mb-0">2300</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 col-sm-4">
                      <div class="info-box bg-light">
                        <div class="info-box-content">
                          <span class="info-box-text text-center text-muted">Total amount spent</span>
                          <span class="info-box-number text-center text-muted mb-0">2000</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 col-sm-4">
                      <div class="info-box bg-light">
                        <div class="info-box-content">
                          <span class="info-box-text text-center text-muted">Estimated project duration</span>
                          <span class="info-box-number text-center text-muted mb-0">20</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <h4>Recent Activity</h4>
                        <div class="post">
                          <div class="user-block">
                            <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image">
                            <span class="username">
                              <a href="#">Jonathan Burke Jr.</a>
                            </span>
                            <span class="description">Shared publicly - 7:45 PM today</span>
                          </div>
                          <!-- /.user-block -->
                          <p>
                            Lorem ipsum represents a long-held tradition for designers,
                            typographers and the like. Some people hate it and argue for
                            its demise, but others ignore.
                          </p>

                          <p>
                            <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 1 v2</a>
                          </p>
                        </div>

                        <div class="post clearfix">
                          <div class="user-block">
                            <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image">
                            <span class="username">
                              <a href="#">Sarah Ross</a>
                            </span>
                            <span class="description">Sent you a message - 3 days ago</span>
                          </div>
                          <!-- /.user-block -->
                          <p>
                            Lorem ipsum represents a long-held tradition for designers,
                            typographers and the like. Some people hate it and argue for
                            its demise, but others ignore.
                          </p>
                          <p>
                            <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 2</a>
                          </p>
                        </div>

                        <div class="post">
                          <div class="user-block">
                            <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image">
                            <span class="username">
                              <a href="#">Jonathan Burke Jr.</a>
                            </span>
                            <span class="description">Shared publicly - 5 days ago</span>
                          </div>
                          <!-- /.user-block -->
                          <p>
                            Lorem ipsum represents a long-held tradition for designers,
                            typographers and the like. Some people hate it and argue for
                            its demise, but others ignore.
                          </p>

                          <p>
                            <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 1 v1</a>
                          </p>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
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

@endsection
