@extends('layouts.admin.base')


@section('content')


    <div class="content-wrapper" style="min-height: 1126px;">


        <!-- Main content -->
        <section class="content">

            <div class="row">

                <!-- /.col -->
                <div class="col-md-9">
                    <form class="form-horizontal" method="POST" action="{{route('coupon.issue_coupon.save_coupon',$id)}}">
                        @csrf
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">

                            <li class=""><a href="#settings1" data-toggle="tab" aria-expanded="false">Discount Coupon</a>
                            </li>
                            <li class=""><a href="#settings2" data-toggle="tab" aria-expanded="false">Deductions Coupon</a>
                            </li>
                        </ul>
                        <div class="tab-content">

                            <!-- /.tab-pane -->
                            <div class="tab-pane active" id="settings1">

                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">Discount</label>

                                        <div class="col-sm-10">
                                            <input type="number" min="0" max="1" step="0.01" class="form-control" id="inputName" placeholder="Discount" name="discount">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">Number</label>

                                        <div class="col-sm-10">
                                            <input type="number" min="1" max="1000000" step="1" class="form-control" id="inputName" placeholder="Number"  name="discountNumber">
                                        </div>
                                    </div>

                                <div class="form-group">
                                    <label for="inputName" class="col-sm-2 control-label">Effect time</label>

                                    <div class="col-sm-10">
                                        <input type="number" min="1" max="1000000" step="1" class="form-control" id="inputName" placeholder="Time"  name="discountTime">
                                    </div>
                                </div>

                            </div>
                            <!-- /.tab-pane -->

                            <div class="tab-pane" id="settings2">
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">Deductions</label>

                                        <div class="col-sm-10">
                                            <input type="number"min="0" max="1000000"  class="form-control" id="inputName" placeholder="Deductions" name="deductions">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">Number</label>

                                        <div class="col-sm-10">
                                            <input type="number" min="1" max="1000000" step="1" class="form-control" id="inputName" placeholder="Number" name="deductionsNumber">
                                        </div>
                                    </div>

                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.nav-tabs-custom -->

                <!-- /.col -->
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                </div>
                </form>
            </div>
            <!-- /.row -->

        </section>
        <!-- /.content -->
    </div>


@endsection
@section('js')

    <script>

    </script>
@endsection