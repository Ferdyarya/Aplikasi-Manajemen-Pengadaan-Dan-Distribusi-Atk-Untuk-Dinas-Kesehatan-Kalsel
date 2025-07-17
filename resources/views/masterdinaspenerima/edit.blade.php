@extends('layout.admin')

@section('content')


<!-- Required meta tags -->
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />

<title>Master Data Bidang Penerima</title>


<body>
    <div class="container-fluid">
        <div class="card" style="border-radius: 15px;">
          <div class="card-body">
              <h1 class="text-center mb-4">Edit Data Bidang Penerima</h1>
              <div class="container">
                  <div class="row justify-content-center">
                      <div class="col-8">
                          <div class="card" style="border-radius: 10px;">
                              <div class="card-body">
                                  <form method="POST" action="{{ route('masterdinaspenerima.update', $item->id) }}" enctype="multipart/form-data">
                                      @csrf
                                      @method('PUT')
                                      <div class="form-group">
                                        <label for="nama">Nama</label>
                                        <input value="{{ $item->nama }}" type="text" name="nama" class="form-control"
                                            id="exampleInputPassword1" placeholder="Masukan Nama" required>
                                     </div>
                                      {{-- <div class="form-group">
                                        <label for="alamat">Alamat</label>
                                        <input value="{{ $item->alamat }}" type="text" name="alamat" class="form-control"
                                            id="exampleInputPassword1" placeholder="Masukan Alamat" required>
                                     </div>
                                      <div class="form-group">
                                        <label for="daerah">Daerah</label>
                                        <input value="{{ $item->daerah }}" type="text" name="daerah" class="form-control"
                                            id="exampleInputPassword1" placeholder="Masukan daerah" required>
                                     </div>
                                      <div class="form-group">
                                        <label for="pimpinan">Pimpinan</label>
                                        <input value="{{ $item->pimpinan }}" type="text" name="pimpinan" class="form-control"
                                            id="exampleInputPassword1" placeholder="Masukan pimpinan" required>
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
