@extends('layouts.vertical', ['title' => 'Payments'])

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Payments</h4>
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Approx</a>
                        </li><!--end nav-item-->
                        <li class="breadcrumb-item active">Payments</li>
                    </ol>
                </div>
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div><!--end row-->

    <div class="row">
        <div class="col-md-4">
            <div class="card bg-globe-img">
                <div class="card-body">
                    <div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-16 fw-semibold">Balance</span>
                            <form class="">
                                <select id="dynamic-select" name="example-select" data-placeholder="Select an option"
                                        data-dynamic-select>
                                    <option value="1" data-img="/images/logos/m-card.png">xx25</option>
                                    <option value="2" data-img="/images/logos/ame-bank.png">xx56</option>
                                </select>
                            </form>
                        </div>

                        <h4 class="my-2 fs-24 fw-semibold">122.5692.00 <small class="font-14">BTC</small></h4>
                        <p class="mb-3 text-muted fw-semibold">
                            <span class="text-success"><i class="fas fa-arrow-up me-1"></i>11.1%</span> Outstanding
                            balance boost
                        </p>
                        <button type="submit" class="btn btn-soft-primary">Transfer</button>
                        <button type="button" class="btn btn-soft-danger">Request</button>
                    </div>
                    <div class="row mt-3">
                        <div class="col-4">
                            <div class="p-2 border-dashed border-theme-color rounded">
                                <h5 class="mt-1 mb-0 fw-medium">$82365.00</h5>
                                <small class="text-muted">BTC/USD</small>
                            </div>
                        </div><!--end col-->
                        <div class="col-4">
                            <div class="p-2 border-dashed border-theme-color rounded">
                                <h5 class="mt-1 mb-0 fw-medium">$15482.00</h5>
                                <small class="text-muted">EUR/USD</small>
                            </div>
                        </div><!--end col-->
                        <div class="col-4">
                            <div class="p-2 border-dashed border-theme-color rounded">
                                <h5 class="mt-1 mb-0 fw-medium">$95628.00</h5>
                                <small class="text-muted">GBP/USD</small>
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->
                    <p class="mb-0  mt-2 text-success fst-italic">The last transaction $2560.00 is Successful!</p>
                </div><!--end card-body-->
            </div><!--end card-->
        </div><!--end col-->
        <div class="col-md-6 col-lg-4">
            <div class="card bg-corner-img">
                <div class="card-body">
                    <div class="row d-flex justify-content-center">
                        <div class="col-9">
                            <p class="text-muted text-uppercase mb-0 fw-normal fs-13">Gross Volume</p>
                            <h4 class="mt-1 mb-0 fw-medium">$8659.50</h4>
                        </div>
                        <!--end col-->
                        <div class="col-3 align-self-center">
                            <div
                                class="d-flex justify-content-center align-items-center thumb-md border-dashed border-danger rounded mx-auto">
                                <i class="iconoir-send-dollars fs-22 align-self-center mb-0 text-danger"></i>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
            <div class="card bg-corner-img">
                <div class="card-body">
                    <div class="row d-flex justify-content-center">
                        <div class="col-9">
                            <p class="text-muted text-uppercase mb-0 fw-normal fs-13">Net Volume</p>
                            <h4 class="mt-1 mb-0 fw-medium">$5523.50</h4>
                        </div>
                        <!--end col-->
                        <div class="col-3 align-self-center">
                            <div
                                class="d-flex justify-content-center align-items-center thumb-md border-dashed border-danger rounded mx-auto">
                                <i class="iconoir-dollar-circle fs-22 align-self-center mb-0 text-danger"></i>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
            <div class="card bg-corner-img">
                <div class="card-body">
                    <div class="row d-flex justify-content-center">
                        <div class="col-9">
                            <p class="text-muted text-uppercase mb-0 fw-normal fs-13">Per Customer</p>
                            <h4 class="mt-1 mb-0 fw-medium">$450.50</h4>
                        </div>
                        <!--end col-->
                        <div class="col-3 align-self-center">
                            <div
                                class="d-flex justify-content-center align-items-center thumb-md border-dashed border-danger rounded mx-auto">
                                <i class="iconoir-user fs-22 align-self-center mb-0 text-danger"></i>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div><!--end col-->
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Balance Details</h4>
                        </div><!--end col-->
                    </div> <!--end row-->
                </div>
                <div class="card-body pt-0">
                    <div id="customers" class="apex-charts"></div>
                </div><!--end card-body-->
            </div><!--end card-->
        </div> <!--end col-->
    </div><!--end row-->

    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Payments History</h4>
                        </div><!--end col-->
                        <div class="col-auto">
                            <div class="dropdown">
                                <a href="#" class="btn bt btn-light dropdown-toggle" data-bs-toggle="dropdown"
                                   aria-haspopup="true" aria-expanded="false">
                                    <i class="icofont-calendar fs-5 me-1"></i> This Month<i
                                        class="las la-angle-down ms-1"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Today</a>
                                    <a class="dropdown-item" href="#">Last Week</a>
                                    <a class="dropdown-item" href="#">Last Month</a>
                                    <a class="dropdown-item" href="#">This Year</a>
                                </div>
                            </div>
                        </div><!--end col-->
                    </div> <!--end row-->
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                            <tr>
                                <th class="border-top-0">Members</th>
                                <th class="border-top-0">Invoice</th>
                                <th class="border-top-0">Payment Details</th>
                                <th class="border-top-0">Date</th>
                                <th class="border-top-0">AApprox</th>
                                <th class="border-top-0">Status</th>
                                <th class="border-top-0">Action</th>
                            </tr><!--end tr-->
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="/images/users/avatar-1.jpg" height="40"
                                             class="me-2 align-self-center rounded" alt="...">
                                        <div class="flex-grow-1 text-truncate align-self-center">
                                            <h6 class="m-0">William Hirsch</h6>
                                            <p class="fs-12 text-muted mb-0">USA</p>
                                        </div><!--end media body-->
                                    </div>
                                </td>
                                <td><a href="{{ route('any', 'apps-invoice')}}" class="fs-12 text-primary">INV-5784</a>
                                </td>
                                <td>Service Fee</td>
                                <td>20 July 2024 <span>03:25pm</span></td>
                                <td>$560</td>
                                <td><span class="badge bg-success-subtle text-success fs-11 fw-medium px-2">Paid</span>
                                </td>
                                <td>
                                    <a href="#"><i class="las la-print text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-download text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                </td>
                            </tr><!--end tr-->
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="/images/users/avatar-2.jpg" height="40"
                                             class="me-2 align-self-center rounded" alt="...">
                                        <div class="flex-grow-1 text-truncate align-self-center">
                                            <h6 class="m-0">Louise Black</h6>
                                            <p class="fs-12 text-muted mb-0">Germany</p>
                                        </div><!--end media body-->
                                    </div>
                                </td>
                                <td><a href="{{ route('any', 'apps-invoice')}}" class="fs-12 text-primary">INV-3256</a>
                                </td>
                                <td>UI/UX Project</td>
                                <td>15 July 2024 <span>012:35pm</span></td>
                                <td>$700</td>
                                <td><span class="badge bg-success-subtle text-success fs-11 fw-medium px-2">Paid</span>
                                </td>
                                <td>
                                    <a href="#"><i class="las la-print text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-download text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                </td>
                            </tr><!--end tr-->
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="/images/users/avatar-3.jpg" height="40"
                                             class="me-2 align-self-center rounded" alt="...">
                                        <div class="flex-grow-1 text-truncate align-self-center">
                                            <h6 class="m-0">Robert Douglas</h6>
                                            <p class="fs-12 text-muted mb-0">France</p>
                                        </div><!--end media body-->
                                    </div>
                                </td>
                                <td><a href="{{ route('any', 'apps-invoice')}}" class="fs-12 text-primary">INV-9865</a>
                                </td>
                                <td>Freelancer Fee</td>
                                <td>12 July 2024 <span>10:05am</span></td>
                                <td>$980</td>
                                <td><span
                                        class="badge bg-warning-subtle text-warning fs-11 fw-medium px-2">Pending</span>
                                </td>
                                <td>
                                    <a href="#"><i class="las la-print text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-download text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                </td>
                            </tr><!--end tr-->
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="/images/users/avatar-4.jpg" height="40"
                                             class="me-2 align-self-center rounded" alt="...">
                                        <div class="flex-grow-1 text-truncate align-self-center">
                                            <h6 class="m-0">Annabelle Reny</h6>
                                            <p class="fs-12 text-muted mb-0">Canada</p>
                                        </div><!--end media body-->
                                    </div>
                                </td>
                                <td><a href="{{ route('any', 'apps-invoice')}}" class="fs-12 text-primary">INV-9960</a>
                                </td>
                                <td>Monthly SIP Plan</td>
                                <td>30 June 2024 <span>11:12pm</span></td>
                                <td>$250</td>
                                <td><span class="badge bg-success-subtle text-success fs-11 fw-medium px-2">Paid</span>
                                </td>
                                <td>
                                    <a href="#"><i class="las la-print text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-download text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                </td>
                            </tr><!--end tr-->
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="/images/users/avatar-5.jpg" height="40"
                                             class="me-2 align-self-center rounded" alt="...">
                                        <div class="flex-grow-1 text-truncate align-self-center">
                                            <h6 class="m-0">Russell Regnier</h6>
                                            <p class="fs-12 text-muted mb-0">Russia</p>
                                        </div><!--end media body-->
                                    </div>
                                </td>
                                <td><a href="{{ route('any', 'apps-invoice')}}" class="fs-12 text-primary">INV-5847</a>
                                </td>
                                <td>Hotel Booking</td>
                                <td>19 June 2024 <span>07:15pm</span></td>
                                <td>$430</td>
                                <td><span class="badge bg-danger-subtle text-danger fs-11 fw-medium px-2">Cancle</span>
                                </td>
                                <td>
                                    <a href="#"><i class="las la-print text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-download text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                </td>
                            </tr><!--end tr-->
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="/images/users/avatar-6.jpg" height="40"
                                             class="me-2 align-self-center rounded" alt="...">
                                        <div class="flex-grow-1 text-truncate align-self-center">
                                            <h6 class="m-0">John Singer</h6>
                                            <p class="fs-12 text-muted mb-0">India</p>
                                        </div><!--end media body-->
                                    </div>
                                </td>
                                <td><a href="{{ route('any', 'apps-invoice')}}" class="fs-12 text-primary">INV-4111</a>
                                </td>
                                <td>Server Subscription</td>
                                <td>05 June 2024 <span>02:40pm</span></td>
                                <td>$600</td>
                                <td><span class="badge bg-success-subtle text-success fs-11 fw-medium px-2">Paid</span>
                                </td>
                                <td>
                                    <a href="#"><i class="las la-print text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-download text-secondary fs-18"></i></a>
                                    <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                </td>
                            </tr><!--end tr-->
                            </tbody>
                        </table> <!--end table-->
                    </div><!--end /div-->
                </div><!--end card-body-->
            </div><!--end card-->
        </div> <!--end col-->

    </div><!--end row-->

@endsection

@section('script')
    <script src="https://apexcharts.com/samples/assets/stock-prices.js"></script>
    @vite(['resources/js/pages/payment.init.js','resources/js/DynamicSelect.js'])
@endsection
