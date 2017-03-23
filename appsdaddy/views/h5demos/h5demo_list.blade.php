
@extends('layouts.master')

@section('title', '任务管理')


@section('content')
    <div class="row">
                  <div class="col-lg-12">
                      <section class="panel">
                          <header class="panel-heading">
                              Demo管理&nbsp;&nbsp;&nbsp;&nbsp;
                              <a href="h5demo_edit.html?sid={{$sid}}" target="_blank">
                                <button type="button" class="btn btn-primary">新demo</button>
                              </a>
                          </header>
                          <table class="table table-striped border-top" id="sample_1">
                          <thead>
                          <tr>
                              <th>序号</th>
                              <th class="hidden-phone">作者</th>
                              <th class="hidden-phone">demo</th>
                              <th class="hidden-phone">--</th>
                              <th class="hidden-phone">--</th>
                              <th class="hidden-phone">--</th>
                              <th class="hidden-phone">--</th>
                              <th class="hidden-phone">--</th>
                              <th class="hidden-phone">--</th>
                          </tr>
                          </thead>
                          <tbody>
                          @foreach ($demos as $demo)
                              <tr class="odd gradeX">
                                  <td>{{$demo->id}}</td>
                                  <td></td>
                                  <td style="cursor:pointer;"  onclick="openPhoto('{{$demo->id}}');">{{$demo->demoName}}</td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td>
                                      <button type="button" class="btn btn-danger" onclick="deleteById('{{$demo->id}}');">删除</button>
                                  </td>
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

      function openPhoto(id){
          window.open("h5demo_edit.html?sid={{$sid}}&id=" + id);  
            //location.href = ";
        }

      function deleteById(id){
          console.log('{{$sid}}');
          console.log(id);
          layer.confirm('你确定要删除吗？',
                  {icon: 3},
                  function(index){
                      ///确认的回调
                      layer.close(index);
                      $.get('deleteDemo', {
                          "id": id,
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