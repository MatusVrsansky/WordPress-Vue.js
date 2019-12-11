
// import "../Sass/critical.scss";
//
// import "./Game.js";
// import "./Square.js"
//
// console.log('ficime');
//
// // current index of question
// var index = 1;
//
//
//
//
// $ = jQuery;
// var my_images = JSON.parse(passed_object);
// var custom_fields = JSON.parse(cars_array);
//
//
// for(var i = 0; i < my_images.length; i++){
//      // console.log(my_images[i].valueOf('answer_a'));
//      // console.log("Post title: "+my_images[i].post_title);
// }
//
//
// //$("#quiz").children("label").text("new Text")
// // console.log($("#quiz").children("label").text());
//
// $ = jQuery;
//
//
//
//
// $(document).ready(function () {
//     $('.btn-block').on('click', function (event) {
//         event.preventDefault();// keep link from executing
//
//         var $item = $(this).closest("btn-lg");
//         var className = $item.context.classList[1];
//
//        // var number = $('input[name="question_right_answer"]').val();
//          if(parseInt($item.context.innerText) === parseInt($('input[name="question_right_answer"]').val())) {
//             $("."+className).css({"border-style": "solid", "border-color": "lime", "border-width": "4px"});
//              setArrowVisible();
//          }
//
//          else {
//              $("."+className).css({"border-style": "solid", "border-color": "red", "border-width": "4px"});
//              getRightAnswerClass();
//              setArrowVisible();
//          }
//     })
//
//     $('#arrow-next').on('click', function (event) {
//         event.preventDefault();// keep link from executing
//
//         $('#arrow-next').hide();
//
//         // wanna answer on 5 questions
//         if(index + 1 <= 5) {
//
//             $("#arrow-back").show();
//
//             $("#question-title").text(my_images[index].post_title);
//
//             $('.element-animation1').text(custom_fields[index].answer_a);
//             $('.element-animation2').text(custom_fields[index].answer_b);
//             $('.element-animation3').text(custom_fields[index].answer_c);
//             $('.element-animation4').text(custom_fields[index].answer_d);
//
//             $('input[name="question_right_answer"]').val(custom_fields[index].right_answer);
//
//
//             $(".choice").each(function(){
//                 var className =  $(this).context.classList[1];
//                 $("."+className).css({"border-style": "", "border-color": "", "border-width": ""});
//             });
//
//             index+=1;
//             $('#question-number').text(index);
//         }
//
//         else {
//             $('#quiz').hide();
//         }
//     })
// });
//
//
// function getRightAnswerClass() {
//     $(".choice").each(function(){
//         var className =  $(this).context.classList[1];
//
//         if(parseInt($(this).context.innerText) === parseInt($('input[name="question_right_answer"]').val())) {
//             $("."+className).css({"border-style": "solid", "border-color": "lime", "border-width": "4px"});
//         }
//     });
// }
//
// function setArrowVisible() {
//     $('#arrow-next').show();
// }
//
//
//
