@extends('layouts.vertical', ['title' => 'Gallery'])

@section('css')
@vite(['node_modules/tobii/dist/css/tobii.min.css'])
@endsection

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
            <h4 class="page-title">Gallery</h4>
            <div class="">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Approx</a>
                    </li><!--end nav-item-->
                    <li class="breadcrumb-item"><a href="#">Pages</a>
                    </li><!--end nav-item-->
                    <li class="breadcrumb-item active">Gallery</li>
                </ol>
            </div>
        </div><!--end page-title-box-->
    </div><!--end col-->
</div><!--end row-->
<div class="row justify-content-center">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Gallery</h4>
                    </div><!--end col-->
                </div> <!--end row-->
            </div><!--end card-header-->
            <div class="card-body pt-0">
                <div id="grid" class="row g-0">
                    <div class="col-md-4 col-lg-3 picture-item">
                        <a href="/images/extra/card/img-1.jpg" class="lightbox">
                            <img src="/images/extra/card/img-1.jpg" alt="" class="img-fluid" />
                        </a>
                    </div>
                    <div class="col-md-4 col-lg-3 picture-item picture-item--overlay">
                        <a href="/images/extra/card/img-2.jpg" class="lightbox">
                            <img src="/images/extra/card/img-2.jpg" alt="" class="img-fluid" />
                        </a>
                    </div>
                    <div class="col-md-4 col-lg-3 picture-item">
                        <a href="/images/extra/card/img-3.jpg" class="lightbox">
                            <img src="/images/extra/card/img-3.jpg" alt="" class="img-fluid" />
                        </a>
                    </div>
                    <div class="col-md-4 col-lg-3 picture-item picture-item--h2">
                        <a href="/images/extra/card/img-4.jpg" class="lightbox">
                            <img src="/images/extra/card/img-4.jpg" alt="" class="img-fluid" />
                        </a>
                    </div>
                    <div class="col-md-4 col-lg-3 picture-item">
                        <a href="/images/extra/card/img-5.jpg" class="lightbox">
                            <img src="/images/extra/card/img-5.jpg" alt="" class="img-fluid" />
                        </a>
                    </div>
                    <div class="col-md-4 col-lg-3 picture-item picture-item--overlay">
                        <a href="/images/extra/card/img-6.jpg" class="lightbox">
                            <img src="/images/extra/card/img-6.jpg" alt="" class="img-fluid" />
                        </a>
                    </div>
                    <div class="col-md-4 col-lg-3 picture-item picture-item--h2">
                        <a href="/images/extra/card/img-2.jpg" class="lightbox">
                            <img src="/images/extra/card/img-2.jpg" alt="" class="img-fluid" />
                        </a>
                    </div>
                    <div class="col-md-4 col-lg-3 picture-item picture-item--h2">
                        <a href="/images/extra/card/img-1.jpg" class="lightbox">
                            <img src="/images/extra/card/img-1.jpg" alt="" class="img-fluid" />
                        </a>
                    </div>
                </div>
            </div><!--end card-body-->
        </div><!--end card-->
    </div> <!--end col-->
</div><!--end row-->

@endsection

@section('script-bottom')
@vite(['resources/js/pages/profile.init.js'])
@endsection