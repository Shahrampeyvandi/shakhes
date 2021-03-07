@extends('layout.temp')
@section('content')

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <h5 class="text-center">افزودن اشتراک</h5>
                <hr />
            </div>
            <form id="add-plan" method="post" action="{{route('Panel.AddPlan')}}">
                @csrf

                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <input type="text" class="form-control" name="name" id="name" value=""
                                    placeholder="نام اشتراک">
                            </div>
                        </div>
                        <div class="row">

                            <div class="form-group form-inline col-md-4">
                                <label for="">مدت زمان اشتراک</label>
                                <div>
                                    <input type="number" class="form-control  mx-2" name="time" id="time" placeholder=""
                                        value="">
                                    <span>روز</span>
                                </div>
                            </div>
                            <div class="form-group form-inline col-md-4">
                                <label for="">قیمت</label>
                                <div>

                                    <input type="number" class="form-control  mx-2" name="price" id="price"
                                        placeholder="" value="">
                                    <span>تومان</span>
                                </div>
                            </div>
                            <div class="form-group form-inline col-md-4">
                                <label for="">تخفیف</label>
                                <div>

                                    <input type="number" class="form-control  mx-2" name="discount" id="discount"
                                        placeholder="" value="">
                                    <span>درصد</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="desc">توضیحات : </label>
                                <textarea class="form-control" name="desc" id="desc" cols="30" rows="8"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="desc">اطلاع رسانی به کاربران: </label>
                                <select class="custom-select" name="sendsms" id="sendsms">
                                    <option value="">لازم نیست</option>
                                    <option value="sms">پیامک</option>
                                    <option value="email">ایمیل</option>
                                    <option value="noty">نوتیفیکیشن</option>
                                </select>
                            </div>
                        </div>





                    </div>


                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">ثبت اشتراک </button>
                    </div>
                </div>
            </form>
            <hr>
            <div>

            </div>
        </div>
    </div>
</div>
@endsection
@section('css')
<style>
    label.error {
        font-size: 12px;
        color: red;
        /* position: absolute; */
        /* top: -50px; */
        /* right: 70px; */
        margin-left: 50px;
    }
</style>
@endsection