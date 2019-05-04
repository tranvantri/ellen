@extends('admin.layout.index')
@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Quản lý câu hỏi
                    <small>Danh sách</small>
                    <small><a href="admin/chatbot/add" class="btn btn-success btn-them"><i class="fa fa-plus"></i> Thêm</a></small>
                </h1> 
                @if(session('thongbao'))
                <div class="alert alert-success">{{session('thongbao')}}</div>
                @endif
                @if(session('loi'))
                <div class="alert alert-danger">{{session('loi')}}</div>
                @endif
            </div>
            <!-- /.col-lg-12 -->
            
            <table class="table table-striped table-bordered table-hover table-list" id="dataTables-example">
                <thead>
                    <tr align="center">
                        <th>Câu hỏi</th>
                        <th>Trả lời</th>
                        <th>Delete</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                	@foreach($askAndAns as $child)
                    <tr class="odd gradeX">
                        <td>{{$child->ask}}</td>
                        <td>
                            <?php $answer = json_decode($child->answer)?>
                            @if(count($answer) >0)
                            @foreach($answer as $child2)
                                - {{$child2}}<br/>
                            @endforeach
                            @endif
                        </td>                          
                        
                        <td class="center"><i class="fa fa-trash-o  fa-fw"></i><a href="admin/chatbot/delete/{{$child->id}}"> Delete</a></td>
                        <td class="center"><i class="fa fa-pencil fa-fw"></i> <a href="admin/chatbot/edit/{{$child->id}}">Edit</a></td>
                    </tr> 
                    @endforeach                  
                </tbody>
            </table>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>

{{-- <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog my-modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Thêm</h4>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered table-hover table-list" id="dataTables-history">
                <thead>
                    <tr align="center">
                        <th>Mã QT viên</th>
                        <th>Tên QT viên</th>
                        <th>Điện thoại</th>
                        <th>Thao tác</th>
                        <th>Tên size</th>
                        <th>Ngày</th>
                    </tr>
                </thead>
                <tbody id="sizeHistory">                       

                </tbody>
            </table>
            
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
        </div>
      </div>
    </div>
  </div> --}}
@endsection