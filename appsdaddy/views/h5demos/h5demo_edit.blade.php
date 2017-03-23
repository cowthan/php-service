@extends('layouts.base')

@section('title', 'H5 edit')


@section('body')
    <style type="text/css">
        #header{

            width: 100%;
            height: 50px;
            background: #369;

        }

        #edit-zone{
            margin-top: 3px;
            padding: 0;

        }

        #edit-zone p{
            height: 30px;
            margin: 0 0;

        }

         #container-name{
            
            
            height: 80px;
            background: #153;
            padding:10px;

        }


        #container-html{
            
            margin-top: 10px;
            height: 300px;
            background: #257;

        }


        #container-css{
            
            
            height: 300px;
            background: #257;

        }
        #container-js{
            
            
            height: 300px;
            background: #580;

        }
        #run-zone{
            
            
            margin:10px;
            height: 800px;
            background: #ddd;

        }

        #bridge{
            width: 10%;
            height: 50px;
            margin: 0 auto;
            background: #0f0;
        }

        #bridge h1{
            color: #000;
            height: 50px;
            margin: 0 0;
            text-align: center;
            line-height: 50px;
        }

        #bridge:hover{
            background: #0c0;
            cursor: pointer;
        }

        .input-box{
            display: block;
            width: 100%;
            height: 270px;
            background: #333;
            color: #0f0;

            font-size:14px; 
            line-height:1.5em; 
            font-family:"微软雅黑",sans-serif;
        }


    </style>
    <div id="header">
            <div id="bridge">
                <h1>运行</h1>
            </div>
    </div>

        <div>
            <div style="width:45%;float:left" id="edit-zone">

                <div id="container-name" style="width:100%" >
                    <p>demo名称</p>
                    <input type="text" id="edit-name" value='{{$demo->demoName}}'/>
                </div>


                <div id="container-html" style="width:100%" >
                    <p>html</p>
                    <textarea id="edit-html" class="input-box">{{ $demo->h5Code }}</textarea>

                </div>

                <div id="container-css" style="width:100%" >
                    <p>css</p>
                    <textarea id="edit-css" class="input-box">{{ $demo->cssCode }}</textarea>

                </div>

                <div id="container-js" style="width:100%" >
                    <p>js</p>
                    <textarea id="edit-js" class="input-box">{{ $demo->jsCode }}</textarea>

                </div>
            </div>

            <div style="width:45%;float:left">
                


                <div id="run-zone" style="width:800px;border:1px solid red;margin-left:10px">
                    
                </div>
            </div>

            <div class="clear" style="clear:both"></div><!-- 清除float产生浮动 --> 
        </div>

        

        <!-- 
        <iframe id="resultfb0a9240f04ec2c179783c9f825601511471028655029" src="http://www.baidu.com" name="CodePen" allowfullscreen="true" sandbox="allow-scripts allow-pointer-lock allow-same-origin allow-popups allow-modals allow-forms" allowtransparency="true" class="result-iframe">
            
           <!doctype html>
           <html>
                <head></head>
                <body>
                    <h1>hahah</h1>
                </body>   
            </html>


        </iframe> -->
@endsection



@section('script')


<script>
    $(document).ready(function(){
        $("#bridge").click(function() {
            var tmpl = "<style>{css}</style>{html}<script>{js}" + "</scr" +"ipt>";
            tmpl = tmpl.replace("{html}", $("#edit-html").val());
            tmpl = tmpl.replace("{css}", $("#edit-css").val());
            tmpl = tmpl.replace("{js}", $("#edit-js").val());

               

            $("#run-zone").empty();
            $("#run-zone").append(tmpl)
            //eval(inputJs.val());

            

            $.get('save_demo?sid={{$sid}}', {
                    "id": {{$demo->id}},
                    "ownerId" : '{{$sid}}',
                    "demoName" : $('#edit-name').val(),
                    "demoImage" : '',
                    "h5Code" : $("#edit-html").val(),
                    "cssCode" : $("#edit-css").val(),
                    "jsCode" : $("#edit-js").val()
                }, function(data, status) {
                    var res = data; //JSON.parse(data);
                    //alert("dd");
                    if(status === "success"){
                        if(res.code == '0'){
                            layer.msg("成功了！");
                            // layer.msg(allPrpos(res.result));
                            //window.location.href="index.html?sid=" + res.result.sid;
                        }else{
                            layer.msg("保存失败：" + res.msg);
                            //fillPhotos(res.result);
                        }
                    }else{
                        layer.alert("失败--" + res.msg);
                    }
                });
            //alert(tmpl);

        });
    });

</script>
    
@endsection
