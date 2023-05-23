$(document).ready(function(){




 var a = $('#startDt').val();

$('.date').datepicker({
     format: "dd-mm-yyyy",
     currentText:"now",
     placement:"bottom",
     startDate:a,
      autoclose: true,
      orientation: "bottom",
      endDate: "today"

  });


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#hearingDate1').change(function (e) { //date picker hearing date
  e.preventDefault();
  var formData = new FormData();
  formData.append('hearingDate1', $('#hearingDate1').val());
   $.ajax({
              type:'POST',
                url: "getHearingDetails1",
                data: formData,
        dataType: "JSON",
        cache:false,
        contentType: false,
        processData: false,
        success: function (data) {
         if(data.length==0){
        alert("No Bench available for the Date");


         }
         else{
         $('select[name="dd_courthall"]').empty();
         $('select[name="dd_courthall"]').append("<option value=''>Bench No</option>");
         for(i=0;i<data.length;i++){

         //$('select[name="dd_courthall"]').append("<option value=\""+data[i].courthallno+"\">"+data[i].courthallno+"</option>");
         $('select[name="dd_courthall"]').append("<option value=\""+data[i].benchcode+","+data[i].listno+"\">"+data[i].judgelist+"</option>");


        }
      }
         console.log(data);
       } ,
   error: function(response){
 console.log(response);
}
});
});// Date picker heaing date end


if($('#dd_courthall').val()!=""){
  var formData = new FormData();
   formData.append('hearingDate1', $('#hearingDate1').val());
   formData.append('benchno', $("#dd_courthall").val().split(',')[0]);
  formData.append('listno', $("#dd_courthall").val().split(',')[1]);

   $.ajax({
              type:'POST',
                url: "getcourtHall",
                data: formData,
        dataType: "JSON",
        cache:false,
        contentType: false,
        processData: false,
        success: function (data) {
          $('#thead').show();

         for(i=0;i<data.length;i++){


        var c=i+1;

    $('#mytable').append("<tr><td >"+c+"</td><td ><button type='button' class='btn btn-info btn-lg' data-toggle='modal'  data-target='#editpopup' value="+data[i].applicationid+"  onClick='callmodal(this.value)'>"+data[i].applicationid+"</button> </td><td style='width:120px'>"+data[i].courtdirection+"</td><td >"+data[i].caseremarks+"</td><td>"+data[i].officenote+"</td><td >"+data[i].ordertypedesc+"</td><td >"+data[i].status+"</td></tr>");
         //$('select[name="dd_bench"]').append("<option value=\""+data[i].benchcode+"\">"+data[i].judgeshortname+"</option>");
        }
        //alert(data[0]);
         console.log(data);
       } ,
   error: function(response){
 console.log(response);
}
});
}

$('#dd_courthall').change(function (e) { //date picker change call hall selection
  e.preventDefault();
  var formData = new FormData();
   formData.append('hearingDate1', $('#hearingDate1').val());
  //var courthallno =$("#dd_courthall option:selected").text();
 // alert (courthallno);

  formData.append('benchno', $("#dd_courthall option:selected").val().split(',')[0]);
  formData.append('listno', $("#dd_courthall option:selected").val().split(',')[1]);

   $.ajax({
              type:'POST',
                url: "getcourtHall",
                data: formData,
        dataType: "JSON",
        cache:false,
        contentType: false,
        processData: false,
        success: function (data) {
           $('#mytable').empty();

          $('#mytable').append("<tr><th >S.No</th><th>Application</th><th style='width:120px'>Court direction</th><th style='width:20px'>Case remaks</th><th>Office note</th><th >Order Passed</th><th >Final case stage</th></tr>");

          // $('select[name="dd_bench"]').empty();
       //  $('select[name="dd_bench"]').append("<option value=''>Bench No</option>");
         for(i=0;i<data.length;i++){
          //alert(data[i].benchcode);
         // alert(data[i].judgeshortname);
         // alert(i);

        var c=i+1;
//alert(data[i]);
    // $('#tdid').setAttribute("width","30%");
    $('#mytable').append("<tr><td >"+c+"</td><td style='width:30px'><button type='button' class='btn btn-info btn-lg' data-toggle='modal'  data-target='#editpopup' value="+data[i].applicationid+"  onClick='callmodal(this.value)'>"+data[i].applicationid+"</button> </td><td style='width:120px'>"+data[i].courtdirection+"</td><td style='width:20px'>"+data[i].caseremarks+"</td><td >"+data[i].officenote+"</td><td>"+data[i].ordertypedesc+"</td><td '>"+data[i].status+"</td></tr>");
         //$('select[name="dd_bench"]').append("<option value=\""+data[i].benchcode+"\">"+data[i].judgeshortname+"</option>");
        }
        //alert(data[0]);
         console.log(data);
       } ,
   error: function(response){
 console.log(response);
}
});
});// Courthall slection end

/*$('#dd_bench').change(function (e) { //bench no selection
  e.preventDefault();
  var formData = new FormData();
   formData.append('hearingDate1', $('#hearingDate1').val());
   formData.append('courthallno', $("#dd_courthall option:selected").text());
   formData.append('benchno', $("#dd_bench option:selected").val());
   $.ajax({
              type:'POST',
                url: "getBench",
                data: formData,
        dataType: "JSON",
        cache:false,
        contentType: false,
        processData: false,
        success: function (data) {
           $('select[name="listno"]').empty();
         $('select[name="listno"]').append("<option value=''>List No</option>");
         for(i=0;i<data.length;i++){
         // alert(data[i].list);
        //  alert(i);
         $('select[name="listno"]').append("<option value=\""+data[i].list+"\">"+data[i].list+"</option>");
        }
        //alert(data[0]);
         console.log(data);
       } ,
   error: function(response){
 console.log(response);
}
});
});*/
// bench no slection end



/*$('#listno').change(function (e) { //bench no selection
  e.preventDefault();
  var formData = new FormData();
   formData.append('hearingDate1', $('#hearingDate1').val());
    formData.append('courthallno', $("#dd_courthall option:selected").text());
    formData.append('benchno', $("#dd_bench option:selected").val());
    formData.append('list_no', $("#listno option:selected").text());
   $.ajax({
              type:'POST',
                url: "getList",
                data: formData,
        dataType: "JSON",
        cache:false,
        contentType: false,
        processData: false,
        success: function (data) {
          //console.log(data);
      $('#thead').show();

   for(i=0;i<data.length;i++){
     var c=i+1;
//alert(data[i]);
    $('#mytable').append("<tr><td>"+c+"</td><td><button type='button' class='btn btn-info btn-lg' data-toggle='modal'  data-target='#editpopup' value="+data[i].applicationid+"  onClick='callmodal(this.value)'>"+data[i].applicationid+"</button> </td><td>"+data[i].c_direction+"</td><td>"+data[i].caseremarks+"</td><td>"+data[i].officenote+"</td><td>"+data[i].casestatus+"</td><td>"+data[i].orderyn+"</td></tr>");

      }

     console.log(data);
       } ,
   error: function(response){
 console.log(response);
}
});
});*/
// bench no slection end


});
