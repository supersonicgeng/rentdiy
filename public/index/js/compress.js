/**
 * Created by Administrator on 2017/8/22 0022.
 */
//判断是否存在画布
function isCanvasSupported() {
    var elem = document.createElement('canvas');
    return !!(elem.getContext && elem.getContext('2d'));
}

//压缩方法
function compress(event, callback) {
    if ( typeof (FileReader) === 'undefined') {
        console.log("当前浏览器内核不支持base64图标压缩");
        //调用上传方式  不压缩
    } else {
        try {
            var file = event.currentTarget.files[0];
            if(!/image\/\w+/.test(file.type)){
                alert("请确保文件为图像类型");
                return false;
            }
            var reader = new FileReader();
            reader.onload = function (e) {
                var image = $('<img/>');
                image.load(function () {
                    console.log("开始压缩");
                    var square = 700;
                    var canvas = document.createElement('canvas');
                    canvas.width = square;
                    canvas.height = square;
                    var context = canvas.getContext('2d');
                    context.clearRect(0, 0, square, square);
                    var imageWidth;
                    var imageHeight;
                    var offsetX = 0;
                    var offsetY = 0;
                    if (this.width > this.height) {
                        imageWidth = Math.round(square * this.width / this.height);
                        imageHeight = square;
                        offsetX = - Math.round((imageWidth - square) / 2);
                    } else {
                        imageHeight = Math.round(square * this.height / this.width);
                        imageWidth = square;
                        offsetY = - Math.round((imageHeight - square) / 2);
                    }
                    context.drawImage(this, offsetX, offsetY, imageWidth, imageHeight);
                    var data = canvas.toDataURL('image/jpeg');
                    //压缩完成执行回调
                    callback(data);
                });
                image.attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        } catch(e) {
            console.log("压缩失败!");
            //调用上传方式  不压缩
        }
    }
}