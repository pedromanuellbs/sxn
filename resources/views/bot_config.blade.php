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
    <div class="body-wrapper-inner">
        <div class="container-fluid">
            <!--  Row 1 -->
            <div class="row">

                <h1 class="display-4 text-center"><a href="{{ route('knowledge.index') }}" style="text-decoration: none;">&lt;</a>
                    Bot Configuration <a href="{{ route('chat.index') }}" style="text-decoration: none;">&gt;</a></h1>
                <hr class="mt-3 mb-5">
                <div class="col-lg-12 mt-7">
                    <!-- Card -->
                    <div class="card">
                        <div class="card-body">
                            
                        </div>

                        <div class="table-responsive container">
                            <table id="emailTable" class="table">
                                <thead style="background-color: #bbcaf5;">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Tone</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($botConfig as $index => $bot)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $bot->name }}</td>
                                            <td>{{ $bot->tone }}</td>
                                            <td>
                                                <form action="{{ route('bot.config.toggle', $bot->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm {{ $bot->status === 'Active' ? 'btn-success' : 'btn-secondary' }}">
                                                        {{ $bot->status }}
                                                    </button>
                                                </form>
                                                
                                            </td>
                                            
                                            <td>
                                               
                                                <form action="{{ route('bot.config.destroy', ['id' => $bot->id]) }}"
                                                    method="POST"
                                                    style="display: inline;"
                                                    onsubmit="return confirm('Are you sure you want to delete this bot?');">
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

    <div class="modal fade" id="botUploadModal" tabindex="-1" aria-labelledby="botUploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 shadow-sm rounded-3 p-5 text-center">
    
                <!-- Main Content -->
                <h5 class="fw-bold text-body">Bot Configuration</h5>
                    <p class="text-muted">Set the bot’s name, tone, and functionality.</p>
    
                <!-- File Upload Box -->
                <div class="">
                    <form action="{{ route('bot.config.store') }}" method="POST" enctype="multipart/form-data" >
                        @csrf
    
                        <!-- Title Input -->
                        <div class="my-4">
                            
                            <input type="text" id="botName"  class="form-control mb-2" name="name" placeholder="Bot Name">
                            <select class="form-select" name="tone" id="botTone">
                                <option selected>Select tone</option>
                                <option>Formal</option>
                                <option>Casual</option>
                                <option>Friendly</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between w-100 px-5 mt-4">
                            <a href="#" class="text-primary" data-bs-dismiss="modal">Cancel</a>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </form>
                </div>
    
                <!-- Footer Buttons -->
                
    
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
            $('.left-btn').html(
                '<button class="btn btn-info btn-sm " data-bs-toggle="modal" data-bs-target="#botUploadModal">+ Upload</button>'
                );


        });
    </script>
@endpush
