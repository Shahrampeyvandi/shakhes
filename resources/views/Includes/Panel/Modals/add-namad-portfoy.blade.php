<div class="modal fade" id="namadModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">افزودن نماد جدید</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form  method="post" action="{{route('Portfoy.AddNamad')}}" enctype="multipart/form-data">
        @csrf
        
        <input type="hidden" name="id" id="id">
        <div class="modal-body">
         
          <div class="wrapper">
            <div class="row wrapper-content">
                <div class="form-group col-md-8">
                    <select class="form-control js-example-basic-single" name="namad" id="namads[]" required>
                        @foreach (\App\Models\Namad\Namad::OrderBy('symbol','ASC')->get() as $item)
                        <option value="{{$item->id}}">{{$item->symbol}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
        
                    <input type="number" class="form-control" name="persent" id="" placeholder="تعداد" required />
                </div>
        
            </div>
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
          <button type="submit" class="btn btn-primary btn--submit">ذخیره</button>
        </div>
      </form>
    </div>
  </div>
</div>