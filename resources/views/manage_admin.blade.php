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
                    <a href="{{ route('dashboard') }}" style="text-decoration: none;">&lt;</a>
                    Manage Admin
                    <a href="{{ route('knowledge.index') }}" style="text-decoration: none;">&gt;</a>
                </h1>
                <hr class="mt-3 mb-5">
                <div class="col-lg-12 mt-7">
                    <!-- Card -->
                    <div class="card">
                        <div class="card-body">
                            <div class=" d-flex justify-content-between align-items-center mb-3 ">
                                <h1>List of Admins</h1>

                                
                            </div>
                        </div>

                        <div class="table-responsive container">
                            <table id="emailTable" class="table ">
                                <thead style="background-color: #bbcaf5;">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Date Joined</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($admins as $index => $user)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->role->role_name }}</td>
                                            <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                
                                                <a href="{{ route('admin.delete', ['id' => $user->id]) }}"
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this user?')">
                                                    <i class="bi bi-trash text-danger" style="cursor: pointer;"></i>
                                                </a>
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


    {{-- modal --}}

    <div class="modal fade" id="addAdmin" tabindex="-1" aria-labelledby="addAdminLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content text-center p-5" style="border-radius: 16px;">
                <form action="{{ route('admin.store') }}" method="POST">
                    @csrf <!-- if using Blade -->
                    
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <h3 class="fw-bold">Add Admin</h3>
    
                        <div class="row mt-4 text-start">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
    
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
    
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
    
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select name="role_id" id="role" class="form-select" required>
                                    <option value="" disabled selected>Choose role</option>
                                    <option value="1">Super Admin</option>
                                    <option value="2">Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
    
                    <!-- Navigation Buttons -->
                    <div class="d-flex justify-content-between px-5 pb-3">
                        <a href="#" class="text-primary" data-bs-dismiss="modal">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Submit</button>
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
    $('.left-btn').html('<button class="btn btn-info btn-sm " data-bs-toggle="modal" data-bs-target="#addAdmin">+ Add Admin</button>');

    
});

    </script>
@endpush
