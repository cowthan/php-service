
@extends('layouts.master')

@section('title', 'Timeline')


@section('content')
    <div class="row">
        <div class="col-lg-12">
          <section class="panel">
              <header class="panel-heading" style="color:green;">
                  数据列表
              </header>
              <table class="table table-striped table-advance table-hover">
              <thead>
              <tr>
                  <th> <i class="icon-bullhorn"></i> 序号</th>

                  @foreach ($layout as $k => $field)

                      <th> <i class="icon-bullhorn"></i> {{ $field['name'] }}</th>

                  @endforeach

                  <th> <i class="icon-bullhorn"></i> </th>

              </tr>
              </thead>
              <tbody>
              @forelse ($list as $bean)
                  <tr class="odd gradeX" style="cursor:pointer;" onclick="openPhoto('{{$bean['id']}}');">
                      <td>{{$bean['id']}}</td>
                      @foreach ($layout as $k => $field)
                            @if($field['isBigData'])
                                <td> 数据量有点大 </td>
                            @else
                                <td> {{$bean[$k]}} </td>
                            @endif

                      @endforeach
                      <td>
                          <button class="btn btn-success btn-xs"><i class="icon-ok"></i></button>
                          <button class="btn btn-primary btn-xs"><i class="icon-pencil"></i></button>
                          <button class="btn btn-danger btn-xs"><i class="icon-trash "></i></button>
                      </td>
                  </tr>
              @empty
                  <p>No Timeline</p>
              @endforelse
              </tbody>
              </table>
          </section>
        </div>

        <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading" style="color:green;">
                编辑区
            </header>

            <form role="form" class="form-horizontal" style="padding:10px;">

                @foreach($layout as $k => $f)

                    @if($f['type'] == "text")
                        <div class="form-group">
                            <label for="{{ $k }}" class="col-sm-2 control-label">{{ $f['name'] }}</label>
                            <div class="col-sm-10"><input type="text" class="form-control" id="{{ $k }}" /></div>
                        </div>
                    @elseif($f['type'] == "text")

                    @else
                        <div class="form-group">
                            <label for="{{ $k }}" class="col-sm-2 control-label">{{ $f['name'] }}</label>
                            <div class="col-sm-10"><input type="text" class="form-control" id="{{ $k }}" /></div>
                        </div>
                    @endif

                @endforeach

                <div class="form-group">

                    <label for="exampleInputEmail1">
                        Email address
                    </label>
                    <input type="email" class="form-control" id="exampleInputEmail1" />
                </div>
                <div class="form-group">

                    <label for="exampleInputPassword1">
                        Password
                    </label>
                    <input type="password" class="form-control" id="exampleInputPassword1" />
                </div>
                <div class="form-group">

                    <label for="exampleInputFile">
                        File input
                    </label>
                    <input type="file" id="exampleInputFile" />
                    <p class="help-block">
                        Example block-level help text here.
                    </p>
                </div>
                <div class="checkbox">

                    <label>
                        <input type="checkbox" /> Check me out
                    </label>
                </div>
                <button type="submit" class="btn btn-default">
                    Submit
                </button>
            </form>
        </section>
        </div>

    </div>
@endsection

@section('script')

    <script type="text/javascript" src="{{ $assets }}towers/assets/data-tables/jquery.dataTables.js"></script>
    <script type="text/javascript" src="{{ $assets }}towers/assets/data-tables/DT_bootstrap.js"></script>
    <script src="{{ $assets }}towers/js/dynamic-table.js"></script>

    <script>

      $(document).ready(function(){

      });

      function openPhoto(taskId){
            layer.open({
              type: 2,
              title: "任务详情-" + taskId,
              area: ['630px', '560px'],
              shade: 0.8,
              closeBtn: true,
              shadeClose: true,
              content: 'task_info.html?id=' + taskId //http://player.youku.com/embed/XMjY3MzgzODg0
          });
        }

      function deleteById(id){
          console.log('{{$sid}}');
          console.log(id);
          layer.confirm('你确定要删除吗？',
                  {icon: 3},
                  function(index){
                      ///确认的回调
                      layer.close(index);
                      $.get('deleteTask', {
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