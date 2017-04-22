@extends('layouts/main')
@section('pageTitle', 'php在线反序列化工具 unserialize serialize')
@section('bodyClass', 'tools-unserialize')
@section('content')
<div class="row ttitle clearfix">
    <div class="col-xs-12 col-sm-6"><h3>php在线反序列化工具 unserialize serialize</h3></div>
    <div class="col-xs-12 col-sm-6">
        <dl class="list-unstyled pull-right">
            
        </dl>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <h4>输入需要反序列化的字符串：<a href="javascript:" id="unserialize-example">样例</a></h4>
        <div class="editor" id="input"></div>
    </div>
    <div class="col-md-6">
        <h4>输出结果：</h4>
        <div class="editor" id="output"></div>
    </div>
</div>
<div class="row mt10">
    <div class="col-md-6">
        <button type="button" class="btn btn-primary covert">反序列化</button>
        <button type="button" class="btn btn-warning" id="clear-input">清空</button>
    </div>
    <div class="col-md-6">
        <button type="button" class="btn btn-success" id="copy" data-clipboard-action="copy" data-clipboard-target="#output textarea">复制结果</button>
        <span class="powerby">
            php反序列化工具功能由@<a href="//1024tools.com">1024Tools</a>提供 编辑器由 @<a href="https://github.com/ajaxorg/ace" target="_blank">ace</a> 支持
        </span>
    </div>
</div>
<div class="mt10">
    <p id="msg"></p>
</div>
@stop

@section('footer')

<script src="{{statics_path()}}/libs/ace/ace.js"></script>
<script src="{{statics_path()}}/libs/clipboard.js/dist/clipboard.min.js"></script>
<script src="{{statics_path()}}/libs/xml/ObjTree.js"></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
    }
});
</script>
<script>
var input = ace.edit("input"),
    output = ace.edit("output");

input.setShowPrintMargin(false)
input.setTheme("ace/theme/tomorrow_night")
output.setShowPrintMargin(false);
output.setTheme("ace/theme/tomorrow_night")
output.setReadOnly(true)
input.focus();
$('.covert').click(function(e){
    var inputdata = input.getValue();
    inputdata = $.trim(inputdata)
    input.setValue(inputdata)
    if (inputdata) {
        $.ajax({
            type: 'post',
            url: '{{{URL::route("convert.unserialize.post")}}}',
            data: {'query': inputdata},
            success: function(data) {
                if (data.status == 1) {
                    output.setValue(data.result);
                    showmsg('success', '成功');
                } else {
                    showmsg('danger', data.error);
                }
            },
            error: function(){
                showmsg('danger', '网络错误');
            }
        })
    } else {
        showmsg('danger', '输入为空');
    }
    e.preventDefault();
});

$('#unserialize-example').click(function(e){
    input.setValue($('#unserialize-template').html());
    input.clearSelection();
    e.preventDefault();
});
$('#clear-input').click(function(e){
    input.setValue("");
    output.setValue("");
    input.focus();
    e.preventDefault();
});
// 复制相关的处理
var clipboard = new Clipboard('#copy');
clipboard.on('success', function(e) {
    showmsg("success", "复制成功")
    e.clearSelection();
});

clipboard.on('error', function(e) {
    showmsg("danger", "复制失败，请手动复制")
});

function showmsg(type, msg) {
    $('#msg').removeClass("bg-danger").removeClass("bg-success").addClass("bg-"+type).text(msg);
}
</script>
<script type="text/template" id="unserialize-template">a:2:{s:4:"tool";s:15:"php unserialize";s:6:"author";s:13:"1024tools.com";}</script>

@stop