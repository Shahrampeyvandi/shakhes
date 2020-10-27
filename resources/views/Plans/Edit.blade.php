@extends('layout.temp')

@section('content')


<div class="card">
    <div class="card-title">
        <h5 class="text-center">ویرایش</h5>
        <hr>
    </div>
    <div class="card-body">
        <form id="add-plan" method="post" action="{{route('Panel.EditPlan',$plan->id)}}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group col-md-6">
                             <label for="">نام اشتراک</label>
                            <input type="text" class="form-control" name="name" id="name" value="{{$plan->name}}"
                                placeholder="نام اشتراک">
                        </div>
                    </div>
                    <div class="row">

                        <div class="form-group form-inline col-md-4">
                            <label for="">مدت زمان اشتراک</label>
                            <div>
                                <input type="number" class="form-control  mx-2" name="time" id="time" placeholder=""
                                    value="{{$plan->days}}">
                                <span>روز</span>
                            </div>
                        </div>
                        <div class="form-group form-inline col-md-4">
                            <label for="">قیمت</label>
                            <div>

                                <input type="number" class="form-control  mx-2" name="price" id="price" placeholder=""
                                    value="{{$plan->price}}">
                                <span>تومان</span>
                            </div>
                        </div>
                         <div class="form-group form-inline col-md-4">
                                <label for="">تخفیف</label>
                                <div>

                                    <input type="number" class="form-control  mx-2" name="discount" id="discount"
                                        placeholder="" value="{{$plan->discount}}">
                                    <span>تومان</span>
                                </div>
                            </div>

                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="desc">توضیحات : </label>
                            <textarea class="form-control" name="desc" id="desc" cols="30"
                                rows="8">{!!$plan->description!!}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class=" btn btn-success text-white">ذخیره <i class="fas fa-edit"></i></button>


        </form>
    </div>
</div>
@endsection