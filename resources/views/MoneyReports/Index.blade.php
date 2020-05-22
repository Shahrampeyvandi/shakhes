@extends('layout.temp')
@section('content')
<div class="address-page">
    <h4>پنل کاربری</h4>
    <p>RFP / لیست RFP</p>
</div>
<div class="container-fluid panel-table">
    <div class="head-panel">
        <h4> لیست RFP</h4>
    </div>
    <div class="col-md-12 ">
        <div class="table-responsive">
            <table class="table table-bordered table-primary ">
                        <thead>
                        <tr>
                            <th scope="col">ردیف</th>
                            <th scope="col">عنوان تقاضا </th>
                            <th scope="col">تاریخ ثبت  </th>
                            <th scope="col"> تاریخ اعتبار تقاضا</th>
                            <th scope="col">وضعیت تایید</th>
                            <th scope="col">جزئیات</th>
                            <th scope="col">عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                      
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

<div class="modal fade bd-example-modal-lg"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">






        </div>
      </div>
    </div>
  </div>
@endsection




@section('js')
    
@endsection


@section('css')
    <style>

    .edit__profile__left{
       margin-top:50px;
     }
     .fa__links{
        font-size: 20px;
    background: blue;
    color: white;
    padding: 5px;
    border-radius: 5px;
     }
     .fa-instagram{
        background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);

     }

     .fa-telegram{
         background: #285AEB;
     }

     .fa-whatsapp{
        background: #075e54;
     }

     .fa-linkedin{
         background: gray;
     }
     @media (min-width: 992px){
.modal-lg {
    width: 1000px !important;
}
     }

 .badge{
    background-color: #74a1d0 !important;
 }
    </style>
@endsection
