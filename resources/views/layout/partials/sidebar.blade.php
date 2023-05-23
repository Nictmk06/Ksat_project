<aside class="main-sidebar">
   
    <section class="sidebar">
 <?php  ?>

     <ul class="sidebar-menu" data-widget="tree">


       @foreach(session::get('menu') as $e)
      
       <?php  $pv = $e->module;    ?>
 
         <li class="treeview">
          <a href="#">
             <span>  {{$e->module}}   </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
</a>
 


 <ul class="treeview-menu">
  
 @foreach(session::get('option') as $e) 
  @if ($pv == $e->module)    
   @if ($e->subtitle == 'Y')  

   
   <li class="treeview">
    <a href="#">
                 {{$e->option}}  
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
</a> </li>
 @else  
        <li><a href={{$e->link}}>{{$e->option}}</a> </li> 
   @endif 
    @endif

    @endforeach


   </ul>

</li>
@endforeach
</ul>

 
<script>
window.onload = function(){  
	$.ajax({
		type: 'POST',
		url: 'labelchange.json',
		data: {
			'_token': '{{ csrf_token() }}'
		},
		success: function (res) {
			
			
			
			
			var count = Object.keys(res).length;
			var labels = document.getElementsByTagName('LABEL');
			for (var i = 0; i < labels.length; i++) {
				
				for(var j= 0; j < count; j++){
					if (labels[i].innerHTML.includes(Object.keys(res)[j]) ){
						if(labels[i].innerHTML.includes("<span")){
							var leng = labels[i].innerHTML.search("<span");
							var rep = labels[i].innerHTML.slice(labels[i].innerHTML.search("<span"));
							var fr = labels[i].innerHTML.slice(0,labels[i].innerHTML.search("<span"));
							//console.log(labels[i].innerHTML.slice(0,labels[i].innerHTML.search("<span")));
							//console.log(fr);
						}
						else{
							var rep = '';
							var fr = labels[i].innerHTML;
						}
						
						if (labels[i].innerHTML.includes('*')) {
							var newlabel = res[fr];
							labels[i].innerHTML = newlabel+rep;
						}
						else{
							var newlabel = res[fr];
							labels[i].innerHTML = newlabel;
						}
					}
				}
			}
			
			var placeholder = document.getElementsByTagName('input');;
			//console.log(placeholder);
			for(var i = 0; i < placeholder.length; i++){
				if(placeholder[i]['attributes']['placeholder']){
					var pl = document.getElementById(placeholder[i]['id']).placeholder;
					for(var j= 0; j < count; j++){
						if (pl.includes(Object.keys(res)[j]) ){
							var newlabel = res[pl];
							//console.log(Object.keys(res)[j]+'   -  >  '+newlabel);
							document.getElementById(placeholder[i]['id']).placeholder = newlabel;
						}
					}
				}
			}
			//document.body.innerHTML = document.body.innerHTML.replace('Hello', "hi");
			
			//document.body.innerHTML = document.body.innerHTML.replace('Application', 'Case');

		}
	});
	//document.body.innerHTML = document.body.innerHTML.replace(/Application/g, 'Case');
	//document.body.innerHTML = document.body.innerHTML.replace(/Applicant/g, 'Petitioner');
}

</script>

</section>
  </aside>
