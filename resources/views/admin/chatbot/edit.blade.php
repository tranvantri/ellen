@extends('admin.layout.index')
@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Sửa câu hỏi
                    {{-- <small>Thêm</small> --}}
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
                @endif
                <!-- kết thúc thông báo thêm thành cong -->

            </div>
            <form action="admin/chatbot/edit/{{$ask->id}}" method="POST" autocomplete="off">
                {{ csrf_field() }}
                <div class="col-lg-7" style="padding-bottom:120px">   

                    <div class="form-group">
                        <label>Câu hỏi</label>
                        <input class="form-control" type="text" name="ask" required="" value="{{$ask->ask}}" placeholder="Nhập câu hỏi"/>
                    </div>
                    <div class="form-group">
                        <label>Câu trả lời</label>
                        <div id="group-input">
                            <?php $answer = json_decode($ask->answer)?>
                            @if(count($answer) > 0)
                                @foreach($answer as $value)
                                    <input class="form-control group-input" required="" style="margin-top: 4px;" type="text" name="answer[]" placeholder="Nhập câu trả lời" value="{{$value}}" />
                                @endforeach
                            @else
                                <input class="form-control group-input" required="" style="margin-top: 4px;" type="text" name="answer[]" placeholder="Nhập câu trả lời"/>
                            @endif
                        </div>
                        <button type="button" id="them-cauhoi" class="btn btn-primary" style="margin-top: 4px;"><i class="fa fa-plus"></i></button>
                        <button type="button" id="xoa-cauhoi" class="btn btn-warning" style="margin-top: 4px;"><i class="fa fa-minus"></i></button>
                    </div>
                    
                    <button type="submit" class="btn btn-warning"  id="submit">Sửa câu hỏi</button>
                                   
                </div>
                
                
            <form>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
@endsection
