@extends('layout.temp')
@section('content')
{{-- @include('Includes.Panel.Modal',['url'=>url('/cp/delete')]) --}}

<div class="container-fluid panel-table mt-5">

    <div class="col-sm-12 col-sm-offset-3 col-md-12  ">
        <div class="card">
            <div class="card-body">
                <div class="wpb_wrapper py-3 d-flex justify-content-between">
                    <h6 class="  mt-15 mb-15 title__divider title__divider--line" style="margin-right: 0px;"><span
                            class="title__divider__wrapper">مشاهده تیکت <span
                                class="line brk-base-bg-gradient-right"></span>
                        </span>
                    </h6>

                </div>

                <div>
                    <h4>{{$ticket->subject}}</h4>
                    <div>
                        {!! $ticket->content !!}
                    </div>
                </div>
                <br>
                <br>
                <form id="" method="post" action="{{route('Panel.AnswerTicket')}}">
                    @csrf
                   @isset($ticket)
                       <input type="hidden" name="id" value="{{$ticket->id}}">
                   @endisset

                    <div class="row">
                        <div class="col-md-12">
                            {{-- <div class="row">
                               <div class="form-group col-md-12">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" 
                                        name="suspended"
                                        {{isset($ticket) && $ticket->status == 'suspended' ? 'checked' : ''}}
                                        id="customSwitch" >
                                        <label class="custom-control-label" for="customSwitch">حالت معلق</label>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="desc">پاسخ</label>
                                    <textarea class="form-control" name="text" id="text" cols="30" rows="8">@isset($ticket){!! $ticket->answer !!}@endisset</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class=" btn btn-success text-white">ارسال <i class="fas fa-edit"></i>
                    </button>


                </form>
            </div>
        </div>

    </div>


</div>


</div>
@endsection