@extends('layouts.app')
@section('title', 'kelas')
@section('heading', 'kelas')
@section('styles')
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary btn-sm btn-add">
                <i class="fas fa-plus"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <td>No</td>
                            <td>Name</td>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kelasTransport as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->name }}</td>
                                <td>
                                    <form action="{{ route('kelas.destroy', $data->id) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <a href="javascript:void()" class="btn btn-warning btn-sm btn-circle btn-edit"
                                            data-id="{{ $data->id }}" data-name="{{ $data->name }}"><i
                                                class="fas fa-edit"></i></a>
                                        <button type="submit" class="btn btn-danger btn-sm btn-circle"
                                            onclick="return confirm('Yakin');">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah kelas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="modalForm" action="{{ route('kelas.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Name kelas" required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Kembali
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitButton">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



@endsection
@section('script')
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });

        $(".btn-add").click(function() {
            $("#modal").modal("show");
            $(".modal-title").html("Tambah kelas");
            $("#modalForm").attr("action", "{{ route('kelas.store') }}");
            $("#formMethod").val("");
            $("#id").val("");
            $("#name").val("");
            $("#submitButton").html("Tambah");
        });

        $("#dataTable").on("click", ".btn-edit", function() {
            let id = $(this).data("id");
            let name = $(this).data("name");
            $("#modal").modal("show");
            $(".modal-title").html("Edit kelas");
            $("#modalForm").attr("action", "{{ route('kelas.update', '') }}/" + id);
            $("#formMethod").val("PUT");
            $("#id").val(id);
            $("#name").val(name);
            $("#submitButton").html("Update");
        });
    </script>
@endsection
