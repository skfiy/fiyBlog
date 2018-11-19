var dialog = {
    // 错误弹出层
    error: function(message) {
        layer.open({
            content:message,
            icon:2,
            title : '错误提示',
        });
    },
    //成功弹出层1--有确定，有跳转链接
    success : function(message,url) {
        layer.open({
            content : message,
            icon : 1,
            yes : function(){
                location.href=url;
            },
        });
    },
    //成功弹出层2--有确定，无跳转链接
    success1 : function(message) {
        layer.open({
            content : message,
            icon : 1,
        });
    },
    //成功弹出层3--无按钮，无连接
    success2:function(message){
        layer.msg(message);
    },
    // 确认弹出层
    confirm : function(message, url) {
        layer.open({
            content : message,
            icon:3,
            btn : ['是','否'],
            yes : function(){
                location.href=url;
            },
        });
    },

    //无需跳转到指定页面的确认弹出层
    toconfirm : function(message) {
        layer.open({
            content : message,
            icon:3,
            btn : ['确定'],
        });
    },
}