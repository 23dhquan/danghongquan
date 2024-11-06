@extends('welcome')
@section('title', 'Thêm Tiền Điện')

@section('content')
    <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">fiu tơ ở đây</h4>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive mt-4">
                            <table
                                id="basic-table"
                                class="table table-striped mb-0"
                                role="grid"
                            >
                                <thead>
                                <tr>
                                    <th>Companies</th>
                                    <th>Members</th>
                                    <th>Budget</th>
                                    <th>Status</th>
                                    <th>Completion</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img
                                                class="rounded img-fluid avatar-40 me-3 bg-soft-primary"
                                                src="../../assets/images/shapes/01.png"
                                                alt="profile"
                                            />
                                            <h6>Soft UI XD Version</h6>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="iq-media-group iq-media-group-1">
                                            <a href="#" class="iq-media-1">
                                                <div class="icon iq-icon-box-3 rounded-pill">
                                                    SP
                                                </div>
                                            </a>
                                            <a href="#" class="iq-media-1">
                                                <div class="icon iq-icon-box-3 rounded-pill">
                                                    PP
                                                </div>
                                            </a>
                                            <a href="#" class="iq-media-1">
                                                <div class="icon iq-icon-box-3 rounded-pill">
                                                    MM
                                                </div>
                                            </a>
                                        </div>
                                    </td>
                                    <td>$14000</td>
                                    <td><div class="text-info">Pending</div></td>
                                    <td>
                                        <div class="d-flex align-items-center mb-2">
                                            <h6>60%</h6>
                                        </div>
                                        <div
                                            class="progress bg-soft-info shadow-none w-100"
                                            style="height: 6px"
                                        >
                                            <div
                                                class="progress-bar bg-info"
                                                data-toggle="progress-bar"
                                                role="progressbar"
                                                aria-valuenow="60"
                                                aria-valuemin="0"
                                                aria-valuemax="100"
                                            ></div>
                                        </div>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
