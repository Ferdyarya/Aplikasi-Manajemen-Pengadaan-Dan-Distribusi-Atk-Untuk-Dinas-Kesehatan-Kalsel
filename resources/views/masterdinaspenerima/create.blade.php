@extends('layout.admin')

@section('content')

<!-- Required meta tags -->
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />

<title>Master Data Dinas Penerima</title>


<body>
    <div class="container-fluid">
        <div class="card" style="border-radius: 15px;">
          <div class="card-body">
              <h1 class="text-center mb-4">Tambah Bidang Penerima</h1>
              <div class="container">
                  <div class="row justify-content-center">
                      <div class="col-8">
                          <div class="card" style="border-radius: 10px;">
                              <div class="card-body">
                                  <form method="POST" action="{{ route('masterdinaspenerima.store') }}" enctype="multipart/form-data">
                                      @csrf
                                      <div class="form-group">
                                          <label for="namadinas">Nama Dinas</label>
                                          <input type="text" name="namadinas" class="form-control @error('namadinas') is-invalid @enderror" id="namadinas"
                                              aria-describedby="emailHelp" placeholder="Masukan namadinas" value="{{ old('namadinas') }}" required>
                                          @error('namadinas')
                                              <div class="invalid-feedback">{{ $message }}</div>
                                          @enderror
                                      </div>
                                      {{-- <div class="form-group">
                                          <label for="alamat">Alamat</label>
                                          <input type="text" name="alamat" class="form-control @error('alamat') is-invalid @enderror" id="alamat"
                                              aria-describedby="emailHelp" placeholder="Masukan Alamat" value="{{ old('alamat') }}" required>
                                          @error('alamat')
                                              <div class="invalid-feedback">{{ $message }}</div>
                                          @enderror
                                      </div>
                                      <div class="form-group">
                                          <label for="daerah">Daerah</label>
                                          <input type="text" name="daerah" class="form-control @error('daerah') is-invalid @enderror" id="daerah"
                                              aria-describedby="emailHelp" placeholder="Masukan Daerah" value="{{ old('daerah') }}" required>
                                          @error('daerah')
                                              <div class="invalid-feedback">{{ $message }}</div>
                                          @enderror
                                      </div>
                                      <div class="form-group">
                                          <label for="pimpinan">Pimpinan</label>
                                          <input type="text" name="pimpinan" class="form-control @error('pimpinan') is-invalid @enderror" id="pimpinan"
                                              aria-describedby="emailHelp" placeholder="Masukan Pimpinan" value="{{ old('pimpinan') }}" required>
                                          @error('pimpinan')
                                              <div class="invalid-feedback">{{ $message }}</div>
                                          @enderror
                                      </div> --}}
                                      <button type="submit" class="btn btn-primary">Submit</button>
                                  </form>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
        </div>
      </div>
</body>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
</script>
@endsection
