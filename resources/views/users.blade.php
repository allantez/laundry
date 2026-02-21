@extends('layouts.vertical', ['title' => 'Users'])

@section('css')
@vite(['node_modules/simple-datatables/dist/style.css'])
@endsection

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Users</h4>
            <div class="">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Approx</a>
                    </li><!--end nav-item-->
                    <li class="breadcrumb-item active">Users</li>
                </ol>
            </div>
        </div><!--end page-title-box-->
    </div><!--end col-->
</div><!--end row-->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Users Details</h4>
                    </div><!--end col-->
                    <div class="col-auto">
                        <button class="btn bg-primary text-white" data-bs-toggle="modal" data-bs-target="#addUser"><i class="fas fa-plus me-1"></i> Add User</button>
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end card-header-->
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table mb-0" id="datatable_1">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile No</th>
                                <th>Registered On</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="/images/users/avatar-1.jpg" class="me-2 thumb-md align-self-center rounded" alt="...">
                                        <div class="flex-grow-1 text-truncate">
                                            <h6 class="m-0">Unity Pugh</h6>
                                            <p class="fs-12 text-muted mb-0">USA</p>
                                        </div><!--end media body-->
                                    </div>
                                </td>
                                <td><a href="#" class="text-body text-decoration-underline">dummy@gmail.com</a></td>
                                <td>+1 234 567 890</td>
                                <td>22 August 2024</td>
                                <td><span class="badge rounded text-success bg-success-subtle">Active</span></td>
                                <td class="text-end">
                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="/images/users/avatar-1.jpg" class="me-2 thumb-md align-self-center rounded" alt="...">
                                        <div class="flex-grow-1 text-truncate">
                                            <h6 class="m-0">Scott Holland</h6>
                                            <p class="fs-12 text-muted mb-0">USA</p>
                                        </div><!--end media body-->
                                    </div>
                                </td>
                                <td><a href="#" class="text-body text-decoration-underline">extradummy@gmail.com</a></td>
                                <td>+1 234 567 890</td>
                                <td>20 August 2024</td>
                                <td><span class="badge rounded text-success bg-success-subtle">Active</span></td>
                                <td class="text-end">
                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="/images/users/avatar-2.jpg" class="me-2 thumb-md align-self-center rounded" alt="...">
                                        <div class="flex-grow-1 text-truncate">
                                            <h6 class="m-0">Karen Savage</h6>
                                            <p class="fs-12 text-muted mb-0">Canada</p>
                                        </div><!--end media body-->
                                    </div>
                                </td>
                                <td><a href="#" class="text-body text-decoration-underline">dummy@gmail.com</a></td>
                                <td>+1 234 567 890</td>
                                <td>15 August 2024</td>
                                <td><span class="badge rounded text-success bg-success-subtle">Active</span></td>
                                <td class="text-end">
                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="/images/users/avatar-3.jpg" class="me-2 thumb-md align-self-center rounded" alt="...">
                                        <div class="flex-grow-1 text-truncate">
                                            <h6 class="m-0">Steven Sharp</h6>
                                            <p class="fs-12 text-muted mb-0">France</p>
                                        </div><!--end media body-->
                                    </div>
                                </td>
                                <td><a href="#" class="text-body text-decoration-underline">extradummy@gmail.com</a></td>
                                <td>+1 234 567 890</td>
                                <td>08 August 2024</td>
                                <td><span class="badge rounded text-secondary bg-secondary-subtle">Inactive</span></td>
                                <td class="text-end">
                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="/images/users/avatar-4.jpg" class="me-2 thumb-md align-self-center rounded" alt="...">
                                        <div class="flex-grow-1 text-truncate">
                                            <h6 class="m-0">Teresa Himes</h6>
                                            <p class="fs-12 text-muted mb-0">India</p>
                                        </div><!--end media body-->
                                    </div>
                                </td>
                                <td><a href="#" class="text-body text-decoration-underline">extradummy@gmail.com</a></td>
                                <td>+1 234 567 890</td>
                                <td>02 August 2024</td>
                                <td><span class="badge rounded text-success bg-success-subtle">Active</span></td>
                                <td class="text-end">
                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="/images/users/avatar-5.jpg" class="me-2 thumb-md align-self-center rounded" alt="...">
                                        <div class="flex-grow-1 text-truncate">
                                            <h6 class="m-0">Ralph Denton</h6>
                                            <p class="fs-12 text-muted mb-0">UK</p>
                                        </div><!--end media body-->
                                    </div>
                                </td>
                                <td><a href="#" class="text-body text-decoration-underline">dummy@gmail.com</a></td>
                                <td>+1 234 567 890</td>
                                <td>28 July 2024</td>
                                <td><span class="badge rounded text-secondary bg-secondary-subtle">Inactive</span></td>
                                <td class="text-end">
                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="/images/users/avatar-1.jpg" class="me-2 thumb-md align-self-center rounded" alt="...">
                                        <div class="flex-grow-1 text-truncate">
                                            <h6 class="m-0">Unity Pugh</h6>
                                            <p class="fs-12 text-muted mb-0">Germany</p>
                                        </div><!--end media body-->
                                    </div>
                                </td>
                                <td><a href="#" class="text-body text-decoration-underline">extradummy@gmail.com</a></td>
                                <td>+1 234 567 890</td>
                                <td>19 July 2024</td>
                                <td><span class="badge rounded text-success bg-success-subtle">Active</span></td>
                                <td class="text-end">
                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="/images/users/avatar-1.jpg" class="me-2 thumb-md align-self-center rounded" alt="...">
                                        <div class="flex-grow-1 text-truncate">
                                            <h6 class="m-0">Scott Holland</h6>
                                            <p class="fs-12 text-muted mb-0">USA</p>
                                        </div><!--end media body-->
                                    </div>
                                </td>
                                <td><a href="#" class="text-body text-decoration-underline">dummy@gmail.com</a></td>
                                <td>+1 234 567 890</td>
                                <td>14 July 2024</td>
                                <td><span class="badge rounded text-success bg-success-subtle">Active</span></td>
                                <td class="text-end">
                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="/images/users/avatar-2.jpg" class="me-2 thumb-md align-self-center rounded" alt="...">
                                        <div class="flex-grow-1 text-truncate">
                                            <h6 class="m-0">Karen Savage</h6>
                                            <p class="fs-12 text-muted mb-0">USA</p>
                                        </div><!--end media body-->
                                    </div>
                                </td>
                                <td><a href="#" class="text-body text-decoration-underline">extradummy@gmail.com</a></td>
                                <td>+1 234 567 890</td>
                                <td>10 July 2024</td>
                                <td><span class="badge rounded text-success bg-success-subtle">Active</span></td>
                                <td class="text-end">
                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="/images/users/avatar-3.jpg" class="me-2 thumb-md align-self-center rounded" alt="...">
                                        <div class="flex-grow-1 text-truncate">
                                            <h6 class="m-0">Steven Sharp</h6>
                                            <p class="fs-12 text-muted mb-0">Russia</p>
                                        </div><!--end media body-->
                                    </div>
                                </td>
                                <td><a href="#" class="text-body text-decoration-underline">dummy@gmail.com</a></td>
                                <td>+1 234 567 890</td>
                                <td>21 June 2024</td>
                                <td><span class="badge rounded text-secondary bg-secondary-subtle">Inactive</span></td>
                                <td class="text-end">
                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="/images/users/avatar-4.jpg" class="me-2 thumb-md align-self-center rounded" alt="...">
                                        <div class="flex-grow-1 text-truncate">
                                            <h6 class="m-0">Teresa Himes</h6>
                                            <p class="fs-12 text-muted mb-0">USA</p>
                                        </div><!--end media body-->
                                    </div>
                                </td>
                                <td><a href="#" class="text-body text-decoration-underline">extradummy@gmail.com</a></td>
                                <td>+1 234 567 890</td>
                                <td>12 June 2024</td>
                                <td><span class="badge rounded text-success bg-success-subtle">Active</span></td>
                                <td class="text-end">
                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="/images/users/avatar-5.jpg" class="me-2 thumb-md align-self-center rounded" alt="...">
                                        <div class="flex-grow-1 text-truncate">
                                            <h6 class="m-0">Ralph Denton</h6>
                                            <p class="fs-12 text-muted mb-0">ShriSri Lanka</p>
                                        </div><!--end media body-->
                                    </div>
                                </td>
                                <td><a href="#" class="text-body text-decoration-underline">dummy@gmail.com</a></td>
                                <td>+1 234 567 890</td>
                                <td>17 May 2024</td>
                                <td><span class="badge rounded text-secondary bg-secondary-subtle">Inactive</span></td>
                                <td class="text-end">
                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="/images/users/avatar-2.jpg" class="me-2 thumb-md align-self-center rounded" alt="...">
                                        <div class="flex-grow-1 text-truncate">
                                            <h6 class="m-0">Karen Savage</h6>
                                            <p class="fs-12 text-muted mb-0">USA</p>
                                        </div><!--end media body-->
                                    </div>
                                </td>
                                <td><a href="#" class="text-body text-decoration-underline">extradummy@gmail.com</a></td>
                                <td>+1 234 567 890</td>
                                <td>07 May 2024</td>
                                <td><span class="badge rounded text-success bg-success-subtle">Active</span></td>
                                <td class="text-end">
                                    <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

<div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserLabel">Add User Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user text-muted thumb-xl rounded me-2 border-dashed"></i>
                        <div class="flex-grow-1 text-truncate">
                            <label class="btn btn-primary text-light">
                                Add Avatar <input type="file" hidden="">
                            </label>
                        </div><!--end media body-->
                    </div>
                </div>
                <div class=" mb-2">
                    <label for="fullName">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text" id="fullName"><i class="far fa-user"></i></span>
                        <input type="text" class="form-control" placeholder="Name" aria-label="FullName">
                    </div>
                </div>
                <div class=" mb-2">
                    <label for="email">Email</label>
                    <div class="input-group">
                        <span class="input-group-text" id="email"><i class="far fa-envelope"></i></span>
                        <input type="email" class="form-control" placeholder="Email address" aria-label="email">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="ragisterDate">Register Date</label>
                            <div class="input-group">
                                <span class="input-group-text" id="ragisterDate"><i class="far fa-calendar"></i></span>
                                <input type="text" class="form-control" placeholder="00/2024" aria-label="ragisterDate">
                            </div>
                        </div>
                    </div><!--end col-->
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="mobilleNo">Mobille No</label>
                            <div class="input-group">
                                <span class="input-group-text" id="mobilleNo"><i class="fas fa-phone"></i></span>
                                <input type="text" class="form-control" placeholder="+1 234 567 890" aria-label="mobilleNo">
                            </div>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary w-100">Add User</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script-bottom')
@vite(['resources/js/pages/datatable.init.js'])
@endsection