@extends('admin.layout.index')
@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Chatbot
                    <small><i class="fa fa-download"></i> Import</small>
                    <small><a href="{{  route('downloadExcel') }}" class="btn btn-success btn-them btn-export"><i class="fa fa-upload"></i> Export</a></small>
                </h1>                
            </div>
            <!-- /.col-lg-12 -->
            <div class="col-lg-12">
                @if(count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach($errors->all() as $err)
                        {{$err}}<br>
                    @endforeach
                </div>
                @endif
                @if(session('thongbao'))
                <div class="alert alert-success">{{session('thongbao')}}</div>
                @endif
            </div>
            <form action="{{ route('importExcel') }}" enctype="multipart/form-data"  method="POST">
                {{ csrf_field() }}
                <div class="col-lg-7" style="padding-bottom:120px">   

                    <div class="form-group">
                        <label>Chọn file</label>
                        <input class="form-control" type="file" name="import_file"  required />
                    </div>                                    
                    <button type="submit" id="submit"  class="btn btn-warning chatbot-excel">Tải lên</button>
                </div>
                
            </form>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<div class="momo"></div>
<h3 id="loadding-chatbot" class="text-center"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Chờ giây lát</h3>
@endsection
