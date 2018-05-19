
// function startPage(url){
//     htmlobj=$.ajax(
//         {
//         url:url,
//         async:false,
//         dataType:'xml',   
//         }
//         );
//         replaceHtml(htmlobj);
// }    
// var buttonform=function () {
//         $("#add_admin").click(function(){
//             $(".dia_form").toggle()
//         });
// };
// var replaceHtml=function (htmlobj){
//     var html=htmlobj.responseText;
//     html=html.replace(new RegExp("\\/","g"),"/");
//     html=html.replace(new RegExp("\\\\","gm"),"");
//     html=html.replace(new RegExp("rn","gm"),"");
//     html=html.replace(new RegExp("^\"","g"),"");
//     html=html.replace(new RegExp("\"$","g"),"");
//     console.log(html);
//     $('#rightside').prepend(html);
//     html=null;
// }

// var sourch=function (url){
//     //console.log(button);
//    // $(document).append($("#sourch"));
//     htmlojb=$.ajax(
//         {
//             url:url,
//             type:"GET",
//             dataType:"xml",
//             async:false,
//             data:$("#sourch").serialize(),
          
//         }
//     );
//     replaceHtml(htmlobj);
// }
var startPage=function (url){
    $("#content").attr("src",url);
}
