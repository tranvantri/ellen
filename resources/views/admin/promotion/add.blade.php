@extends('admin.layout.index')
@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Khuyến mãi
                    <small>Thêm</small>
                </h1>
            </div>
            <!-- /.col-lg-12 -->
            <div class="col-lg-12">

                <!-- thông báo errors -->
                @if(count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach($errors->all() as $err)
                        {{$err}}<br>
                    @endforeach
                </div>
                @endif

                <!-- thông báo thêm thành công -->
                @if(session('thongbao')) 
                <div class="alert alert-success">{{session('thongbao')}}</div>
                    @if(session('prom')->enable ==1)
                        <div id="sendNotifiPromotion" data-active="{{session('prom')}}"></div>
                    @endif
                @endif
                <!-- kết thúc thông báo thêm thành cong -->

            </div>
            <form action="admin/promotion/add" method="POST" id="formPromosition">
                {{ csrf_field() }}
                <div class="col-lg-7" style="padding-bottom:120px">   

                    <div class="form-group">
                        <label>Tên khuyến mãi</label>
                        <input class="form-control" type="text" name="Ten" placeholder="Tên khuyến mãi" required minlength="3" maxlength="100" value="{{ old('Ten') }}" />
                    </div>
                    
                    <div class="form-group">
                        <label>Phần trăm giảm giá(%)</label>
                        <input class="form-control" type="number" maxlength="2" minlength="1" name="per_decr"  value="{{ old('sale', 0) }}"/>
                    </div>
                    
                    <div class="form-group">
                        <label>Ngày bắt đầu khuyến mãi</label>
                        <input type='text' class="form-control" id='datetimepicker1' name="start_date_sale" />
                    </div>

                    <div class="form-group">
                        <label>Ngày kết thúc khuyến mãi</label>
                        <input type='text' class="form-control" id='datetimepicker2' name="end_date_sale" />
                    </div>
                    
                    <div class="form-group">
                        <label>Trạng thái</label>
                        <label class="radio-inline">
                            <input name="enable" value="1" checked type="radio">Hoạt động
                        </label>
                        <label class="radio-inline">
                            <input name="enable" value="0" type="radio">Khóa
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Ảnh</label>                        
                        <input id="ckfinder-input-pro" type="hidden" placeholder="Chọn hình ảnh" required maxlength="190" name="image">
                        <div><img id="img-pro" src="upload\images\image-icon.png"  alt="" class="img-edit img-fluid"></div>
                        <div class="input-group-btn">
                          <button id="ckfinder-popup-pro" data-input="ckfinder-input-pro" data-preview="img-pro" class="btn btn-info" type="button">Chọn ảnh</button>
                        </div>                       
                    </div>
                    

                    <button type="submit" class="btn btn-warning"  id="submit">Thêm</button>
                                   
                </div>
                
            </form>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>

@endsection
