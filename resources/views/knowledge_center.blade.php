@extends('layouts.app')
@push('styles')
    <style>
        div.dataTables_wrapper div.dataTables_paginate {
            text-align: center !important;
            float: none !important;
            margin-top: 1rem;
        }
    </style>
    
@endpush
@section('content')
    <div class="body-wrapper-inner" >
        <div class="container-fluid" >
            <!--  Row 1 -->
            <div class="row">

                <h1 class="display-4 text-center">
                    <a href="{{ route('admin.index') }}" style="text-decoration: none;">&lt;</a>
                    Knowledge Center
                    <a href="{{ route('bot.index') }}" style="text-decoration: none;">&gt;</a>
                </h1>
                <hr class="mt-3 mb-5">
                <div class="col-lg-12 mt-7">
                    <!-- Card -->
                    <div class="card">
                        <div class="card-body">
                            <div class=" d-flex justify-content-between align-items-center mb-3 ">
                                

                                
                            </div>
                        </div>

                        <div class="table-responsive container">
                            <table id="emailTable" class="table ">
                                <thead style="background-color: #bbcaf5;">
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Size</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($knowledge as $index => $knowledge)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $knowledge->title }}</td>
                                           <td>
                                            <form action="{{ route('knowledge.toggle', $knowledge->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $knowledge->status === 'Ready' ? 'btn-success' : 'btn-secondary' }}">
                                                    {{ $knowledge->status }}
                                                </button>
                                            </form>
                                            
                                            
                                           </td>
                                            
                                            <td>{{ $knowledge->size }}</td>
                                            <td>
                                                <a href="{{ route('knowledge.view', ['id' => $knowledge->id]) }}"
                                                    title="View">
                                                    <i class="bi bi-eye text-primary me-2"
                                                        style="cursor: pointer;"></i>
                                                </a>
                                                <form action="{{ route('knowledge.center.destroy', $knowledge->id) }}"
                                                    method="POST"
                                                    style="display: inline;"
                                                    onsubmit="return confirm('Are you sure you want to delete this file?');">
                                                  @csrf
                                                  @method('DELETE')
                                                  <button type="submit" class="btn btn-link p-0 m-0 align-baseline" title="Delete">
                                                      <i class="bi bi-trash text-danger" style="cursor: pointer;"></i>
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

            </div>

        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="uploadFile" tabindex="-1" aria-labelledby="uploadFileLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-sm rounded-3 p-5 text-center">

            <!-- Main Content -->
            <h5 class="fw-bold text-body">Upload knowledge source for your chatbot</h5>
            <p class="text-muted">
                The chatbot will use the information from the uploaded files as its primary source of knowledge
            </p>

            <!-- File Upload Box -->
            <div class="">
                <form action="{{ route('knowledge.store') }}" method="POST" enctype="multipart/form-data" class="dropzone" id="pdfDropzone">
                    @csrf

                    <!-- Title Input -->
                    <div class="mb-4 text-start">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Enter document title" required>
                    </div>

                    <div class="dz-message my-2" data-dz-message>
                        <i class="bi bi-file-earmark-text" style="font-size: 2rem;"></i>
                        <p class="mb-0 mt-2 text-muted">Drag PDF here or click to select</p>
                    </div>
                </form>
            </div>

            <!-- Footer Buttons -->
            <form action="{{ route('knowledge.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- <input type="text" name="title" required>
                <input type="file" name="file" accept=".pdf" required> --}}
            
                <div class="d-flex justify-content-between w-100 px-5 mt-4">
                    <a href="#" class="text-primary" data-bs-dismiss="modal">Cancel</a>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>

        </div>
    </div>
</div>

    
@endsection

@push('scripts')
   
   

    <script>
        $(document).ready(function() {
    var table = $('#emailTable').DataTable({
        paging: true,
        pageLength: 5,
        info: false,
        searching: true,
        lengthChange: false,
        language: {
            paginate: {
                previous: "&lt;",
                next: "&gt;"
            }
        },
        dom: ' <"top-row d-flex justify-content-between align-items-center mb-2 " <"left-btn">f>tp'
    });

    // Move the button into the .left-btn container
    $('.left-btn').html('<button class="btn btn-info btn-sm " data-bs-toggle="modal" data-bs-target="#uploadFile">+ Upload</button>');

    
});

    </script>
@endpush
