@extends('layout.temp')
@section('content')



<main class="main-content">
    <div class="container-fluid">


        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h5 class="text-center">افزودن آموزش</h5>
                    <hr />
                </div>
                <form id="upload-file" method="post" action="{{route('Education.Add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">

                            <h6 class="card-title">عنوان </h6>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <input type="text" class="form-control" name="title" id="title" placeholder="">
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
                                    <div class="form-row">
                                        <div class="col-md-3">
                                            <label for=""> پوستر : </label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="file" name="image" class="" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 right-side">
                            <div class="cat">
                                <h6 class="card-title">دسته بندی ها: </h6>
                                <input type="text" class="form-control mb-2" name="category" id="category"
                                    placeholder="جدید">
                                <button class="btn btn-outline-primary btn-sm mb-2"
                                    onclick="addCategory(event)">افزودن</button>
                                <div class="cat-wrapper">
                                    @foreach ($categories as $key=>$category)
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="{{$key+1}}" name="category" value="{{$category->name}}"
                                            class="custom-control-input">
                                        <label class="custom-control-label" for="{{$key+1}}">{{$category->name}}</label>
                                    </div>
                                    @endforeach

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">شماره قسمت</label>
                                    <input type="number" class="form-control" placeholder="0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary">تایید </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</main>




@endsection


@section('js')
<script src="{{asset('vendor/ckeditor/ckeditor.js')}}"></script>
<script>
    CKEDITOR.replace('desc',{
            contentsLangDirection: 'rtl',
            extraPlugins: 'uploadimage',
            filebrowserUploadUrl: '{{route('UploadImage')}}?type=file',
            imageUploadUrl: '{{route('UploadImage')}}?type=image',
        });

    function addCategory(event) {
       event.preventDefault()
        let val = $(event.target).prev().val();
        let id = Math.random();
        let wrapper = $(event.target).next();
        wrapper.append(`
         <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" 
                            id="${id}" 
                            name="category"
                            value="${val}"
                             class="custom-control-input">
                            <label class="custom-control-label" for="${id}">${val}</label>
                        </div>
         `);
    
}
</script>
@endsection