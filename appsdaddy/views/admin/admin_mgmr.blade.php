@extends('layouts.master')

@section('title', '管理员管理')


@section('content')
    <div class="row">
                  <div class="col-lg-12">
                      <section class="panel">
                          <header class="panel-heading">
                              管理员配置&nbsp;&nbsp;&nbsp;&nbsp;
                              <a href="add_admin.html?sid={{$sid}}" target="_blank"><button type="button" class="btn btn-primary">添加管理员</button></a>
                          </header>
                          <table class="table table-striped border-top" id="sample_1">
                          <thead>
                          <tr>
                              <th>序号</th>
                              <th class="hidden-phone">账号</th>
                              <th class="hidden-phone">密码</th>
                              <th class="hidden-phone">姓名</th>
                              <th class="hidden-phone">备注</th>
                              <th class="hidden-phone">删除</th>
                          </tr>
                          </thead>
                          <tbody>
                          @foreach ($admins as $admin)
                              <tr class="odd gradeX">
                                  <td>{{$admin->id}}</td>
                                  <td>{{$admin->username}}</td>
                                  <td class="hidden-phone">{{$admin->password}}</td>
                                  <td class="hidden-phone">{{$admin->realname}}</td>
                                  <td class="center hidden-phone">{{$admin->company}}</td>
                                  @if ($admin->username == 'jack-daddy')
                                      <td><button type="button" class="btn btn-danger">无法删除</button>  </td>
                                  @else
                                      <td>
                                          <button type="button" class="btn btn-danger" onclick="deleteById('{{$admin->id}}');">删除</button>
                                      </td>
                                  @endif
                              </tr>
                          @endforeach

                          </tbody>
                          </table>
                      </section>
                  </div>
              </div>
@endsection

@section('script')


    <script type="text/javascript" src="{{ URL::asset('/') }}/assets/towers/assets/data-tables/jquery.dataTables.js"></script>
    <script type="text/javascript" src="{{ URL::asset('/') }}/assets/towers/assets/data-tables/DT_bootstrap.js"></script>
    <script src="{{ URL::asset('/') }}/assets/towers/js/dynamic-table.js"></script>

    <script>

      $(document).ready(function(){

      });

      function deleteById(id){
            console.log('{{$sid}}');
          console.log(id);
          layer.confirm('你确定要删除吗？',
                  {icon: 3},
                  function(index){
                      ///确认的回调
                      layer.close(index);
                      $.get('deleteAdmin', {
                          "taskId": id,
                          "sid": '{{$sid}}'
                      }, function(data, status) {
                          var res = data; //JSON.parse(data);
                          console.log(res);
                          if(status === "success"){
                              if(res.code == 0){
                                  layer.msg("成功了！");
                                  location.reload();
                              }else{
                                  layer.alert("失败--" + res.message);
                              }
                          }else{
                              layer.alert("失败--" + status);
                          }
                      });
                  },
                  function(index){
                      ///取消的回调
                      layer.close(index);
                  }
          );

      }


  </script>

@endsection